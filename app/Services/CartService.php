<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Resolves the "current" cart for a request — a DB-backed cart tied to
 * the logged-in user, or a DB-backed cart tied to a guest session id.
 * On login, the guest cart's lines are merged into the user's cart so
 * nothing added before signing in is lost.
 */
class CartService
{
    private const SESSION_KEY = 'guest_cart_id';

    public function currentCart(Request $request): Cart
    {
        if ($request->user()) {
            return Cart::firstOrCreate(['user_id' => $request->user()->id]);
        }

        $cartId = $request->session()->get(self::SESSION_KEY);
        if ($cartId) {
            $cart = Cart::whereNull('user_id')->find($cartId);
            if ($cart) {
                return $cart;
            }
        }

        $cart = Cart::create([
            'session_id' => (string) Str::uuid(),
        ]);
        $request->session()->put(self::SESSION_KEY, $cart->id);

        return $cart;
    }

    public function addItem(Request $request, Product $product, int $quantity = 1, ?int $variantId = null): Cart
    {
        $cart = $this->currentCart($request);

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {
            $item->increment('quantity', max(1, $quantity));
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_variant_id' => $variantId,
                'quantity' => max(1, $quantity),
            ]);
        }

        return $cart->fresh('items.product.images');
    }

    public function updateQuantity(Cart $cart, CartItem $item, int $quantity): void
    {
        if ($quantity < 1) {
            $item->delete();
            return;
        }
        $item->update(['quantity' => $quantity]);
    }

    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }

    /** Called right after a successful login: fold the guest session cart into the user's cart. */
    public function mergeGuestCartIntoUser(Request $request, $user): void
    {
        $cartId = $request->session()->get(self::SESSION_KEY);
        if (!$cartId) {
            return;
        }

        $guestCart = Cart::whereNull('user_id')->find($cartId);
        if (!$guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($guestCart->items as $guestItem) {
            $existing = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->where('product_variant_id', $guestItem->product_variant_id)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $guestItem->quantity);
            } else {
                $userCart->items()->create([
                    'product_id' => $guestItem->product_id,
                    'product_variant_id' => $guestItem->product_variant_id,
                    'quantity' => $guestItem->quantity,
                ]);
            }
        }

        $guestCart->items()->delete();
        $guestCart->delete();
        $request->session()->forget(self::SESSION_KEY);
    }

    /** Serializable shape the cart drawer / checkout JS expects. */
    public function toDrawerArray(Cart $cart): array
    {
        $cart->load(['items.product.images', 'items.variant']);

        return [
            'items' => $cart->items->map(function (CartItem $item) {
                $product = $item->product;
                return [
                    'item_id' => $item->id,
                    'product_id' => $product->id,
                    'variant_id' => $item->product_variant_id,
                    'name' => $product->name.($item->variant ? ' — '.$item->variant->label : ''),
                    'slug' => $product->slug,
                    'image' => $product->images->first()->url ?? '/images/placeholder.jpg',
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'line_total' => $item->line_total,
                ];
            })->values(),
            'subtotal' => $cart->subtotal,
            'total_quantity' => $cart->total_quantity,
        ];
    }
}
