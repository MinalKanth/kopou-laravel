<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Render the homepage.
     *
     * Phase 4 update: this now reads from MySQL via Eloquent instead of
     * App\Data\DummyCatalog. Product::toDisplayArray() reproduces the
     * exact same array shape the dummy data used, so home.blade.php and
     * <x-product-card> did not need to change at all.
     */
    public function index(): View
    {
        $categories = Category::active()
            ->orderBy('sort_order')
            ->withCount('products')
            ->get()
            ->map(fn (Category $c) => [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'product_count' => $c->products_count,
                'image' => $c->image,
                'description' => $c->description,
            ])
            ->all();

        $baseQuery = fn () => Product::active()->with(['category', 'images', 'variants', 'inventory']);

        $featured = $baseQuery()->featured()->latest()->take(8)->get()
            ->map(fn (Product $p) => $p->toDisplayArray())->all();

        $bestsellers = $baseQuery()->bestseller()->latest()->take(8)->get()
            ->map(fn (Product $p) => $p->toDisplayArray())->all();

        return view('home', compact('categories', 'featured', 'bestsellers'));
    }
}
