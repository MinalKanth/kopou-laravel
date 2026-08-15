<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    private const PER_PAGE = 8;

    /**
     * Product listing page. Phase 4 update: filtering/sorting/pagination
     * now run as real Eloquent query constraints + ->paginate() instead
     * of operating on a PHP array. The Blade view (products/index) did
     * not change — it still receives a flat $products list and a page
     * count, just backed by MySQL now.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'category'  => 'nullable|string|max:100|exists:categories,slug',
            'q'         => 'nullable|string|max:100',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'sort'      => 'nullable|in:popular,newest,price_asc,price_desc,rating',
            'page'      => 'nullable|integer|min:1',
        ]);

        $query = Product::active()->with(['category', 'images', 'variants', 'inventory']);

        if (!empty($validated['q'])) {
            $query->search($validated['q']);
        }
        if (!empty($validated['category'])) {
            $query->inCategory($validated['category']);
        }
        if (!empty($validated['min_price'])) {
            $query->where(fn ($q) => $q->where('sale_price', '>=', $validated['min_price'])
                ->orWhere(fn ($q2) => $q2->whereNull('sale_price')->where('price', '>=', $validated['min_price'])));
        }
        if (!empty($validated['max_price'])) {
            $query->where(fn ($q) => $q->where('sale_price', '<=', $validated['max_price'])
                ->orWhere(fn ($q2) => $q2->whereNull('sale_price')->where('price', '<=', $validated['max_price'])));
        }

        match ($validated['sort'] ?? 'popular') {
            'price_asc'  => $query->orderByRaw('COALESCE(sale_price, price) asc'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) desc'),
            'rating'     => $query->orderByDesc('rating'),
            'newest'     => $query->orderByDesc('id'),
            default      => $query->orderByDesc('review_count'), // 'popular'
        };

        $paginator = $query->paginate(self::PER_PAGE, ['*'], 'page', $validated['page'] ?? 1);

        return view('products.index', [
            'products'   => $paginator->getCollection()->map(fn (Product $p) => $p->toDisplayArray())->all(),
            'categories' => Category::active()->orderBy('sort_order')->get(['name', 'slug'])->toArray(),
            'total'      => $paginator->total(),
            'page'       => $paginator->currentPage(),
            'lastPage'   => $paginator->lastPage(),
            'filters'    => $validated,
            'heading'    => $this->resolveHeading($validated),
        ]);
    }

    public function category(Request $request, string $slug): View
    {
        Category::active()->where('slug', $slug)->firstOrFail();
        $request->merge(['category' => $slug]);
        return $this->index($request);
    }

    public function show(string $slug): View
    {
        $product = Product::active()
            ->with(['category', 'images', 'variants', 'inventory'])
            ->where('slug', $slug)
            ->first();

        abort_if(!$product, Response::HTTP_NOT_FOUND);

        $related = Product::active()
            ->with(['category', 'images', 'variants', 'inventory'])
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(4)
            ->get()
            ->map(fn (Product $p) => $p->toDisplayArray())
            ->all();

        return view('products.show', [
            'product' => $product->toDisplayArray(),
            'related' => $related,
        ]);
    }

    private function resolveHeading(array $filters): string
    {
        if (!empty($filters['q'])) {
            return 'Search results for "'.$filters['q'].'"';
        }
        if (!empty($filters['category'])) {
            $category = Category::where('slug', $filters['category'])->first();
            return $category->name ?? 'Products';
        }
        return 'All Products';
    }
}
