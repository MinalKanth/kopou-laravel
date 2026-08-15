<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    private const SESSION_KEY = 'wishlist_slugs';

    /**
     * Show the wishlist. Still session-backed for guests (Phase 5/6 adds
     * `wishlists` / `wishlist_items` tables and merges this into the
     * account on login). Phase 4 update: looks products up from MySQL.
     */
    public function index(Request $request): View
    {
        $slugs = $request->session()->get(self::SESSION_KEY, []);

        $products = Product::active()
            ->with(['category', 'images', 'variants', 'inventory'])
            ->whereIn('slug', $slugs)
            ->get()
            ->map(fn (Product $p) => $p->toDisplayArray())
            ->all();

        return view('wishlist.index', ['products' => $products]);
    }

    public function toggle(Request $request, string $slug): RedirectResponse
    {
        abort_if(!Product::active()->where('slug', $slug)->exists(), 404);

        $slugs = $request->session()->get(self::SESSION_KEY, []);
        if (in_array($slug, $slugs, true)) {
            $slugs = array_values(array_diff($slugs, [$slug]));
        } else {
            $slugs[] = $slug;
        }
        $request->session()->put(self::SESSION_KEY, $slugs);

        return back();
    }
}
