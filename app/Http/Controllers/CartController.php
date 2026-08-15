<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    /** Current cart contents, for the drawer to hydrate on page load. */
    public function index(Request $request): JsonResponse
    {
        $cart = $this->cartService->currentCart($request);

        return response()->json($this->cartService->toDrawerArray($cart));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1|max:20',
        ]);

        $product = Product::active()->findOrFail($validated['product_id']);

        $available = $product->inventory?->available_quantity ?? 0;
        if ($available < 1) {
            return response()->json(['message' => 'This product is out of stock.'], 422);
        }

        $cart = $this->cartService->addItem(
            $request,
            $product,
            $validated['quantity'] ?? 1,
            $validated['variant_id'] ?? null
        );

        return response()->json($this->cartService->toDrawerArray($cart));
    }

    public function update(Request $request, CartItem $item): JsonResponse
    {
        $this->authorizeItem($request, $item);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:20',
        ]);

        $this->cartService->updateQuantity($item->cart, $item, $validated['quantity']);

        return response()->json($this->cartService->toDrawerArray($item->cart->fresh()));
    }

    public function destroy(Request $request, CartItem $item): JsonResponse
    {
        $this->authorizeItem($request, $item);

        $cart = $item->cart;
        $this->cartService->removeItem($item);

        return response()->json($this->cartService->toDrawerArray($cart->fresh()));
    }

    /** Make sure the cart item being touched actually belongs to this visitor's cart. */
    private function authorizeItem(Request $request, CartItem $item): void
    {
        $cart = $this->cartService->currentCart($request);
        abort_unless($item->cart_id === $cart->id, 403);
    }
}
