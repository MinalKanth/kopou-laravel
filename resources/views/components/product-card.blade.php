{{--
    Product card component.
    Expects: $product => the array shape from App\Models\Product::toDisplayArray()
    (id, slug, name, category, origin, price, sale_price, rating, review_count,
     stock_quantity, badges, image, gallery, short_description, ...).

    The full array is also serialized into data-product so app.js can drive
    wishlist / quick-add / quick-view without needing a separate JS-side
    product list — the server-rendered card is the single source of truth.
--}}
@php
    $badgeClass = [
        'BESTSELLER' => 'bestseller',
        'ORGANIC' => 'organic',
        'LIMITED' => 'limited',
    ];
    $hasDiscount = !empty($product['sale_price']) && $product['sale_price'] < $product['price'];
    $discountPct = $hasDiscount ? round((1 - $product['sale_price'] / $product['price']) * 100) : 0;
    $hoverImage = $product['gallery'][1] ?? $product['image'];
@endphp

<article class="product-card" data-id="{{ $product['id'] }}" data-product="{{ json_encode($product) }}">
    <div class="product-media">
        <div class="product-badges">
            @foreach ($product['badges'] as $badge)
                <span class="badge {{ $badgeClass[$badge] ?? '' }}">{{ $badge }}</span>
            @endforeach
        </div>

        <button class="wishlist-btn" aria-label="Add {{ $product['name'] }} to wishlist" aria-pressed="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/>
            </svg>
        </button>

        <img class="img-primary" src="{{ $product['image'] }}" alt="{{ $product['name'] }}" loading="lazy" width="480" height="600">
        <img class="img-hover" src="{{ $hoverImage }}" alt="" loading="lazy">

        <div class="product-media-light"></div>

        <div class="product-quick-row">
            <button class="quick-add" data-product-name="{{ $product['name'] }}">Add to Cart</button>
            <button class="quick-view">Quick View</button>
        </div>
    </div>

    <div class="product-body">
        <div class="product-cat">{{ $product['category'] }}</div>
        <h3 class="product-name">
            <a href="{{ url('/products/'.$product['slug']) }}">{{ $product['name'] }}</a>
        </h3>

        <div class="product-rating">
            <span class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
            <span>{{ number_format($product['rating'], 1) }} ({{ $product['review_count'] }})</span>
        </div>

        <div class="product-price-row">
            <span class="product-price">&#8377;{{ number_format($hasDiscount ? $product['sale_price'] : $product['price'], 0) }}</span>
            @if ($hasDiscount)
                <span class="product-price-old">&#8377;{{ number_format($product['price'], 0) }}</span>
                <span class="product-discount">{{ $discountPct }}% off</span>
            @endif
        </div>

        @if ($product['stock_quantity'] > 0 && $product['stock_quantity'] <= 6)
            <div class="stock-low">Only {{ $product['stock_quantity'] }} left</div>
        @endif

        @if (!empty($product['origin']))
            <div class="product-origin-tag">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 3 6v6c0 5 4 8.5 9 10 5-1.5 9-5 9-10V6l-9-4Z"/></svg>
                {{ $product['origin'] }}
            </div>
        @endif
    </div>
</article>
