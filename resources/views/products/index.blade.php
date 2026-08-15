@extends('layouts.app')

@section('title', $heading.' — KOPOU')

@section('content')
<div class="container" style="padding-block: 1.6rem 0;">
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <span>{{ $heading }}</span>
    </nav>
</div>

<section class="section" style="padding-top:1.2rem;">
    <div class="container plp-layout">

        <aside class="plp-filters" aria-label="Filters">
            <form method="GET" action="{{ url()->current() }}">
                @if (!empty($filters['q']))
                    <input type="hidden" name="q" value="{{ $filters['q'] }}">
                @endif

                <div class="filter-group">
                    <h4>Category</h4>
                    <ul>
                        <li><a href="{{ route('products.index') }}" class="{{ empty($filters['category']) ? 'active' : '' }}">All</a></li>
                        @foreach ($categories as $cat)
                            <li>
                                <a href="{{ route('categories.show', $cat['slug']) }}"
                                   class="{{ ($filters['category'] ?? null) === $cat['slug'] ? 'active' : '' }}">
                                    {{ $cat['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="filter-group">
                    <h4>Price</h4>
                    <div class="filter-price-row">
                        <input type="number" name="min_price" placeholder="Min" min="0" value="{{ $filters['min_price'] ?? '' }}" aria-label="Minimum price">
                        <span>&ndash;</span>
                        <input type="number" name="max_price" placeholder="Max" min="0" value="{{ $filters['max_price'] ?? '' }}" aria-label="Maximum price">
                    </div>
                    <button type="submit" class="btn btn-outline" style="width:100%; margin-top:0.8rem;">Apply</button>
                </div>
            </form>
        </aside>

        <div class="plp-main">
            <div class="plp-toolbar">
                <span class="plp-count">{{ $total }} {{ Str::plural('product', $total) }}</span>

                <form method="GET" action="{{ url()->current() }}" class="plp-sort">
                    @foreach ($filters as $key => $value)
                        @if ($key !== 'sort' && $value !== null)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <label for="sort">Sort</label>
                    <select name="sort" id="sort" onchange="this.form.submit()">
                        <option value="popular" @selected(($filters['sort'] ?? 'popular') === 'popular')>Popular</option>
                        <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Newest</option>
                        <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Price: Low to High</option>
                        <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Price: High to Low</option>
                        <option value="rating" @selected(($filters['sort'] ?? '') === 'rating')>Rating</option>
                    </select>
                </form>
            </div>

            @if (empty($products))
                <div class="plp-empty">
                    <h3>No products match those filters</h3>
                    <p>Try widening the price range or clearing the category filter.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-outline">Clear filters</a>
                </div>
            @else
                <div class="product-grid">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                @if ($lastPage > 1)
                    <nav class="pagination" aria-label="Pagination">
                        @for ($i = 1; $i <= $lastPage; $i++)
                            <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}"
                               class="{{ $i === $page ? 'active' : '' }}"
                               aria-current="{{ $i === $page ? 'page' : 'false' }}">{{ $i }}</a>
                        @endfor
                    </nav>
                @endif
            @endif
        </div>
    </div>
</section>
@endsection
