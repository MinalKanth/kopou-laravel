@extends('layouts.app')

@section('title', $product['name'].' — KOPOU')
@section('meta_description', $product['short_description'])

@section('content')
@php
    $hasDiscount = !empty($product['sale_price']) && $product['sale_price'] < $product['price'];
    $discountPct = $hasDiscount ? round((1 - $product['sale_price'] / $product['price']) * 100) : 0;
    $badgeClass = ['BESTSELLER' => 'bestseller', 'ORGANIC' => 'organic', 'LIMITED' => 'limited'];
@endphp

<div class="container" style="padding-block: 1.6rem 0;">
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <a href="{{ route('categories.show', $product['category_slug']) }}">{{ $product['category'] }}</a>
        <span>/</span>
        <span>{{ $product['name'] }}</span>
    </nav>
</div>

<section class="section pdp" data-product="{{ json_encode($product) }}">
    <div class="container pdp-layout">

        {{-- Gallery --}}
        <div class="pdp-gallery">
            <div class="pdp-gallery-main">
                <div class="product-badges">
                    @foreach ($product['badges'] as $badge)
                        <span class="badge {{ $badgeClass[$badge] ?? '' }}">{{ $badge }}</span>
                    @endforeach
                </div>
                <img src="{{ $product['gallery'][0] }}" alt="{{ $product['name'] }}" id="pdp-main-image" data-zoomable>
            </div>
            @if (count($product['gallery']) > 1)
                <div class="pdp-thumbs">
                    @foreach ($product['gallery'] as $i => $img)
                        <button class="pdp-thumb {{ $i === 0 ? 'active' : '' }}" data-thumb="{{ $img }}" aria-label="View image {{ $i + 1 }}">
                            <img src="{{ $img }}" alt="">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="pdp-info">
            <div class="product-cat">{{ $product['category'] }} &middot; Origin: {{ $product['origin'] }}</div>
            <h1 class="pdp-title">{{ $product['name'] }}</h1>

            <div class="product-rating" style="margin-top:0.6rem;">
                <span class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                <span>{{ number_format($product['rating'], 1) }} &middot; {{ $product['review_count'] }} reviews</span>
            </div>

            <div class="pdp-price-row">
                <span class="pdp-price">&#8377;{{ number_format($hasDiscount ? $product['sale_price'] : $product['price'], 0) }}</span>
                @if ($hasDiscount)
                    <span class="product-price-old">&#8377;{{ number_format($product['price'], 0) }}</span>
                    <span class="product-discount">{{ $discountPct }}% off</span>
                @endif
            </div>

            <p class="pdp-short-desc">{{ $product['short_description'] }}</p>

            @if (!empty($product['variants']))
                <div class="pdp-variants">
                    <h4>Size</h4>
                    <div class="variant-row">
                        @foreach ($product['variants'] as $i => $variant)
                            <button type="button" class="variant-pill {{ $i === 0 ? 'active' : '' }}"
                                data-variant-id="{{ $variant['id'] }}"
                                data-price="{{ $variant['sale_price'] ?? $variant['price'] }}">
                                {{ $variant['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="pdp-qty-row">
                <div class="qty-stepper">
                    <button type="button" data-qty-decrease aria-label="Decrease quantity">&minus;</button>
                    <input type="number" value="1" min="1" max="{{ max(1, $product['stock_quantity']) }}" id="pdp-qty" aria-label="Quantity">
                    <button type="button" data-qty-increase aria-label="Increase quantity">+</button>
                </div>
                @if ($product['stock_quantity'] > 0 && $product['stock_quantity'] <= 6)
                    <span class="stock-low">Only {{ $product['stock_quantity'] }} left</span>
                @elseif ($product['stock_quantity'] === 0)
                    <span class="stock-low">Out of stock</span>
                @endif
            </div>

            <div class="pdp-cta-row">
                <button class="btn btn-outline" style="flex:1;" data-quick-add data-product-name="{{ $product['name'] }}" @disabled($product['stock_quantity'] === 0)>Add to Cart</button>
                <button class="btn btn-primary" style="flex:1;" @disabled($product['stock_quantity'] === 0)>Buy Now</button>
                <form action="{{ route('wishlist.toggle', $product['slug']) }}" method="POST">
                    @csrf
                    <button class="wishlist-btn wishlist-btn--static" type="submit" aria-label="Add to wishlist">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
                    </button>
                </form>
            </div>

            <div class="pdp-pincode">
                <label for="pincode">Check delivery to your PIN code</label>
                <div class="pincode-row">
                    <input type="text" id="pincode" inputmode="numeric" maxlength="6" placeholder="e.g. 781001" pattern="[0-9]{6}">
                    <button type="button" class="btn btn-outline" data-pincode-check>Check</button>
                </div>
                <p class="pincode-result" data-pincode-result></p>
            </div>

            <ul class="pdp-facts">
                <li><strong>Origin:</strong> {{ $product['origin'] }}</li>
                @if ($product['brand'])<li><strong>Sold by:</strong> {{ $product['brand'] }}</li>@endif
                @if ($product['material'])<li><strong>Material:</strong> {{ $product['material'] }}</li>@endif
                <li><strong>SKU:</strong> {{ $product['sku'] }}</li>
            </ul>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="container pdp-tabs-wrap">
        <div class="pdp-tabs" role="tablist">
            <button class="pdp-tab active" data-tab="description" role="tab" aria-selected="true">Description</button>
            <button class="pdp-tab" data-tab="specs" role="tab" aria-selected="false">Specifications</button>
            <button class="pdp-tab" data-tab="shipping" role="tab" aria-selected="false">Shipping &amp; Returns</button>
            <button class="pdp-tab" data-tab="reviews" role="tab" aria-selected="false">Reviews ({{ $product['review_count'] }})</button>
        </div>

        <div class="pdp-tab-panel" data-panel="description">
            <p>{{ $product['description'] }}</p>
            @if ($product['care_instructions'])
                <p><strong>Care:</strong> {{ $product['care_instructions'] }}</p>
            @endif
        </div>

        <div class="pdp-tab-panel" data-panel="specs" hidden>
            <table class="spec-table">
                @foreach ($product['specifications'] as $label => $value)
                    <tr><th>{{ $label }}</th><td>{{ $value }}</td></tr>
                @endforeach
            </table>
        </div>

        <div class="pdp-tab-panel" data-panel="shipping" hidden>
            <p>Dispatched within 2 business days from Assam. Estimated delivery 4&ndash;8 business days depending on your PIN code, shown at checkout.</p>
            <p>Returns accepted within 7 days of delivery for eligible, unused products in original packaging. Perishable food items are not eligible for return once opened.</p>
        </div>

        <div class="pdp-tab-panel" data-panel="reviews" hidden>
            <p style="color:var(--forest); opacity:.75;">Review submission and listing will be wired up once the account and orders system (Phase 5/8) is in place. This tab is reserved so the layout won't shift later.</p>
        </div>
    </div>

    {{-- Related --}}
    @if (!empty($related))
        <div class="container" style="margin-top: 3.5rem;">
            <div class="section-head"><h2 style="font-size: var(--step-2);">You May Also Like</h2></div>
            <div class="product-grid">
                @foreach ($related as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Gallery thumbnail switching
    const mainImg = document.getElementById('pdp-main-image');
    document.querySelectorAll('[data-thumb]').forEach((btn) => {
        btn.addEventListener('click', () => {
            mainImg.src = btn.getAttribute('data-thumb');
            document.querySelectorAll('[data-thumb]').forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });

    // Variant selection
    const priceEl = document.querySelector('.pdp-price');
    document.querySelectorAll('.variant-pill').forEach((pill) => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('.variant-pill').forEach((p) => p.classList.remove('active'));
            pill.classList.add('active');
            const price = parseFloat(pill.getAttribute('data-price'));
            if (priceEl && !Number.isNaN(price)) {
                priceEl.textContent = '\u20B9' + price.toLocaleString('en-IN');
            }
        });
    });

    // Quantity stepper
    const qtyInput = document.getElementById('pdp-qty');
    document.querySelector('[data-qty-decrease]')?.addEventListener('click', () => {
        qtyInput.value = Math.max(1, parseInt(qtyInput.value || '1', 10) - 1);
    });
    document.querySelector('[data-qty-increase]')?.addEventListener('click', () => {
        const max = parseInt(qtyInput.getAttribute('max') || '99', 10);
        qtyInput.value = Math.min(max, parseInt(qtyInput.value || '1', 10) + 1);
    });

    // Tabs
    document.querySelectorAll('.pdp-tab').forEach((tab) => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.pdp-tab').forEach((t) => { t.classList.remove('active'); t.setAttribute('aria-selected', 'false'); });
            document.querySelectorAll('.pdp-tab-panel').forEach((p) => p.hidden = true);
            tab.classList.add('active');
            tab.setAttribute('aria-selected', 'true');
            document.querySelector(`[data-panel="${tab.dataset.tab}"]`).hidden = false;
        });
    });

    // Pincode checker
    // NOTE: this is a client-side placeholder. Phase 7 (checkout) replaces
    // this with a real serviceability lookup against the shipping backend —
    // never trust a client-computed delivery promise for the real checkout.
    document.querySelector('[data-pincode-check]')?.addEventListener('click', () => {
        const val = document.getElementById('pincode').value.trim();
        const resultEl = document.querySelector('[data-pincode-result]');
        if (!/^\d{6}$/.test(val)) {
            resultEl.textContent = 'Enter a valid 6-digit PIN code.';
            resultEl.style.color = 'var(--terracotta-deep)';
            return;
        }
        resultEl.textContent = `Delivery available to ${val}. Estimated 4\u20138 business days.`;
        resultEl.style.color = 'var(--forest)';
    });
});
</script>
@endpush
