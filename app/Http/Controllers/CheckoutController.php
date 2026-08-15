<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class CheckoutController extends Controller
{
    private const SHIPPING_FEE = 0; // free shipping for now; wire up rules here later

    public function __construct(
        private readonly CartService $cartService,
        private readonly RazorpayService $razorpay,
    ) {
    }

    /** Address form + order summary. */
    public function show(Request $request): View|RedirectResponse
    {
        $cart = $this->cartService->currentCart($request);
        $cart->load('items');

        if ($cart->items->isEmpty()) {
            return redirect()->route('products.index')->with('status', 'Your bag is empty — add something first.');
        }

        return view('checkout.index', [
            'cartData' => $this->cartService->toDrawerArray($cart),
            'addresses' => $request->user()?->addresses()->orderByDesc('is_default')->get() ?? collect(),
            'shippingFee' => self::SHIPPING_FEE,
            'razorpayKey' => $this->razorpay->publicKey(),
        ]);
    }

    /**
     * Create the local order (pending_payment), reserve stock, and open a
     * Razorpay order for the same amount. Returns what checkout.js needs
     * to launch the payment modal.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'address_id' => 'nullable|integer|exists:addresses,id',
            'full_name' => 'required_without:address_id|string|max:150',
            'phone' => 'required_without:address_id|string|max:20',
            'line1' => 'required_without:address_id|string|max:255',
            'line2' => 'nullable|string|max:255',
            'city' => 'required_without:address_id|string|max:100',
            'state' => 'required_without:address_id|string|max:100',
            'pincode' => 'required_without:address_id|string|max:10',
            'save_address' => 'nullable|boolean',
        ]);

        $user = $request->user();
        abort_unless($user, 401);

        $cart = $this->cartService->currentCart($request);
        $cart->load('items.product', 'items.variant');
        abort_if($cart->items->isEmpty(), 422, 'Your bag is empty.');

        $shipping = $this->resolveShippingAddress($validated, $user);

        try {
            $order = DB::transaction(function () use ($cart, $user, $shipping) {
                // Lock in stock before we ever talk to the payment gateway.
                foreach ($cart->items as $item) {
                    Inventory::reserve($item->product_id, $item->quantity);
                }

                $subtotal = $cart->subtotal;
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => $user->id,
                    'status' => 'pending_payment',
                    'payment_status' => 'pending',
                    'payment_method' => 'razorpay',
                    'subtotal' => $subtotal,
                    'shipping_fee' => self::SHIPPING_FEE,
                    'total' => $subtotal + self::SHIPPING_FEE,
                    ...$shipping,
                ]);

                foreach ($cart->items as $item) {
                    $product = $item->product;
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'variant_label' => $item->variant?->label,
                        'image' => $product->images->first()->url ?? null,
                        'unit_price' => $item->unit_price,
                        'quantity' => $item->quantity,
                        'line_total' => $item->line_total,
                    ]);
                }

                return $order;
            });
        } catch (RuntimeException $e) {
            // Typically "insufficient stock" from Inventory::reserve().
            return response()->json(['message' => $e->getMessage()], 422);
        }

        try {
            $razorpayOrder = $this->razorpay->createOrder($order->order_number, (float) $order->total);
        } catch (RuntimeException $e) {
            $this->releaseOrder($order);
            return response()->json(['message' => $e->getMessage()], 502);
        }

        $order->update(['razorpay_order_id' => $razorpayOrder['id']]);

        return response()->json([
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'razorpay_key' => $this->razorpay->publicKey(),
            'razorpay_order_id' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'], // paise, as returned by Razorpay
            'currency' => $razorpayOrder['currency'],
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $shipping['shipping_phone'],
        ]);
    }

    /** Called by checkout.js after Razorpay's modal reports success. */
    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $order = Order::where('id', $validated['order_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        abort_if($order->razorpay_order_id !== $validated['razorpay_order_id'], 422, 'Order mismatch.');

        $valid = $this->razorpay->verifySignature(
            $validated['razorpay_order_id'],
            $validated['razorpay_payment_id'],
            $validated['razorpay_signature']
        );

        if (!$valid) {
            $order->update(['payment_status' => 'failed']);
            return response()->json(['message' => 'Payment verification failed.'], 422);
        }

        DB::transaction(function () use ($order, $validated) {
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Inventory::finalize($item->product_id, $item->quantity);
                }
            }

            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid',
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
                'placed_at' => now(),
            ]);

            $cart = $this->cartService->currentCart(request());
            $cart->items()->delete();
        });

        return response()->json(['redirect' => route('checkout.success', $order)]);
    }

    public function success(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_unless($order->payment_status === 'paid', 404);

        return view('checkout.success', ['order' => $order->load('items')]);
    }

    /** Payment failed/cancelled client-side: release the stock hold, mark order failed. */
    public function cancel(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $this->releaseOrder($order);

        return response()->json(['ok' => true]);
    }

    private function releaseOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Inventory::release($item->product_id, $item->quantity);
                }
            }
            $order->update(['status' => 'cancelled', 'payment_status' => 'failed']);
        });
    }

    private function resolveShippingAddress(array $validated, $user): array
    {
        if (!empty($validated['address_id'])) {
            $address = Address::where('user_id', $user->id)->findOrFail($validated['address_id']);
            return [
                'shipping_name' => $address->full_name,
                'shipping_phone' => $address->phone,
                'shipping_line1' => $address->line1,
                'shipping_line2' => $address->line2,
                'shipping_city' => $address->city,
                'shipping_state' => $address->state,
                'shipping_pincode' => $address->pincode,
            ];
        }

        if (!empty($validated['save_address'])) {
            Address::create([
                'user_id' => $user->id,
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'line1' => $validated['line1'],
                'line2' => $validated['line2'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'],
                'pincode' => $validated['pincode'],
                'is_default' => !$user->addresses()->exists(),
            ]);
        }

        return [
            'shipping_name' => $validated['full_name'],
            'shipping_phone' => $validated['phone'],
            'shipping_line1' => $validated['line1'],
            'shipping_line2' => $validated['line2'] ?? null,
            'shipping_city' => $validated['city'],
            'shipping_state' => $validated['state'],
            'shipping_pincode' => $validated['pincode'],
        ];
    }
}
