<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with(['category', 'inventory'])
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', ['products' => $products, 'q' => $request->q]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $product = Product::create([
            ...$validated,
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
        ]);

        Inventory::create([
            'product_id' => $product->id,
            'stock_quantity' => $validated['stock_quantity'],
            'reserved_quantity' => 0,
            'low_stock_threshold' => $validated['low_stock_threshold'] ?? 5,
        ]);

        if (!empty($validated['image_url'])) {
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $validated['image_url'],
                'sort_order' => 0,
            ]);
        }

        return redirect()->route('admin.products.index')->with('status', 'Product created.');
    }

    public function edit(Product $product): View
    {
        $product->load(['inventory', 'images']);

        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validated($request, $product->id);

        $product->update([
            ...$validated,
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
        ]);

        $product->inventory()->updateOrCreate([], [
            'stock_quantity' => $validated['stock_quantity'],
            'low_stock_threshold' => $validated['low_stock_threshold'] ?? 5,
        ]);

        if (!empty($validated['image_url'])) {
            $product->images()->updateOrCreate(
                ['sort_order' => 0],
                ['path' => $validated['image_url']]
            );
        }

        return redirect()->route('admin.products.index')->with('status', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('status', 'Product deleted.');
    }

    private function validated(Request $request, ?int $productId = null): array
    {
        return $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:280|unique:products,slug,'.($productId ?? 'NULL').',id',
            'sku' => 'required|string|max:100|unique:products,sku,'.($productId ?? 'NULL').',id',
            'brand' => 'nullable|string|max:150',
            'origin' => 'nullable|string|max:150',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'weight' => 'nullable|string|max:50',
            'material' => 'nullable|string|max:150',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,inactive',
            'is_featured' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'image_url' => 'nullable|string|max:500',
        ]);
    }
}
