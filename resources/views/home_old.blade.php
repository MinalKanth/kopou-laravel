@extends('layouts.app')

@section('title', 'KOPOU — Authentic Assam, Delivered Across India')

@section('content')

{{-- ============ HERO ============ --}}
<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow">Assam, Delivered</span>
        <h1 class="hero-title">From Assam,<br><em>With Authenticity.</em></h1>
        <p class="hero-sub">Tea, handloom silk, handicrafts and traditional delicacies — sourced directly from Assam's growers and artisans, delivered anywhere in India.</p>

        <div class="hero-cta-row">
            <a href="#categories" class="btn btn-primary">Explore Assam</a>
            <a href="#bestsellers" class="btn btn-ghost">Shop Best Sellers</a>
        </div>

        <div class="hero-stats">
            <div><strong>3,200+</strong><span>Orders delivered pan-India</span></div>
            <div><strong>60+</strong><span>Partner artisans &amp; estates</span></div>
            <div><strong>4.8&#9733;</strong><span>Average customer rating</span></div>
        </div>
    </div>
    <div class="hero-visual">
        <img src="/images/hero-tea-garden.jpg" alt="Tea garden at sunrise in Assam, rows of tea bushes stretching toward the hills">
    </div>
</section>

{{-- ============ CATEGORIES ============ --}}
<section class="section" id="categories">
    <div class="container">
        <div class="section-head reveal">
            <h2>Shop by Category</h2>
            <p>Five categories, one standard: every product traced back to where it was grown, spun or hand-finished in Assam.</p>
        </div>

        <div class="cat-grid">
            @foreach ($categories as $cat)
                <a href="{{ url('/categories/'.$cat['slug']) }}" class="cat-card reveal">
                    <img src="{{ $cat['image'] }}" alt="{{ $cat['name'] }}" loading="lazy">
                    <div class="cat-card-label">
                        <h3>{{ $cat['name'] }}</h3>
                        <span class="count">{{ $cat['product_count'] }} products</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ BESTSELLERS ============ --}}
<section class="section section--tint" id="bestsellers">
    <div class="container">
        <div class="section-head reveal">
            <h2>Best Sellers</h2>
            <p>The products customers across India keep coming back for.</p>
        </div>

        <div class="product-grid">
            @foreach ($bestsellers as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

{{-- ============ FEATURED / ALL DUMMY PRODUCTS ============ --}}
<section class="section">
    <div class="container">
        <div class="section-head reveal">
            <h2>Featured This Week</h2>
            <p>A running edit across tea, handloom, craft and pantry — refreshed weekly.</p>
        </div>

        <div class="product-grid">
            @foreach ($featured as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

{{-- ============ HERITAGE / STORY ============ --}}
<section class="section section--tint">
    <div class="container heritage">
        <div class="heritage-media reveal">
            <img src="/images/heritage-weaver.jpg" alt="Weaver at a traditional loom in Sualkuchi, Assam, working on a silk gamosa">
            <div class="thread-rule"></div>
        </div>
        <div class="heritage-copy reveal">
            <span class="eyebrow">Our Story</span>
            <h2>From the Heart of Assam.</h2>
            <p>KOPOU connects households across India directly with the tea gardens of Dibrugarh, the looms of Sualkuchi, the bell-metal workshops of Sarthebari, and the kitchens that still make pickle and pitha the way their grandmothers did.</p>
            <p>Every seller on KOPOU is based in Assam. Every product carries its origin, its maker, and — where it applies — the estate or village it came from.</p>

            <div class="heritage-facts">
                <div><strong>60+</strong><span>Growers &amp; artisans</span></div>
                <div><strong>28</strong><span>Districts represented</span></div>
                <div><strong>100%</strong><span>Assam-origin catalog</span></div>
            </div>
        </div>
    </div>
</section>

{{-- ============ TRUST STRIP ============ --}}
<section class="section">
    <div class="container">
        <div class="section-head reveal">
            <h2>Why Buy From KOPOU</h2>
        </div>

        <div class="trust-grid reveal">
            <div class="trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 2 3 6v6c0 5 4 8.5 9 10 5-1.5 9-5 9-10V6l-9-4Z"/><path d="m8 12 3 3 5-6"/></svg>
                <h4>Verified origin</h4>
                <p>Sourced directly from Assam growers and artisans.</p>
            </div>
            <div class="trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a4 4 0 0 1 8 0v2"/></svg>
                <h4>Secure payments</h4>
                <p>Encrypted checkout, verified server-side on every order.</p>
            </div>
            <div class="trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 11h13v7H3z"/><path d="M16 13h3l2 3v2h-5z"/><circle cx="7" cy="20" r="1.4"/><circle cx="17.5" cy="20" r="1.4"/></svg>
                <h4>Pan-India delivery</h4>
                <p>Shipped to every serviceable PIN code in the country.</p>
            </div>
            <div class="trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 4h16v4H4z"/><path d="M4 8v12h16V8"/><path d="M9 12h6"/></svg>
                <h4>Quality checked</h4>
                <p>Every batch inspected before it leaves Assam.</p>
            </div>
            <div class="trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M21 12a9 9 0 1 1-3-6.7"/><path d="M21 3v6h-6"/></svg>
                <h4>Easy returns</h4>
                <p>Straightforward return window on eligible products.</p>
            </div>
        </div>
    </div>
</section>

{{-- ============ REVIEWS ============ --}}
<section class="section section--tint">
    <div class="container">
        <div class="section-head reveal">
            <h2>What Customers Say</h2>
        </div>

        <div class="review-track reveal">
            <div class="review-card">
                <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                <p>"The orthodox tea tasted nothing like what I'd get at a supermarket. You can tell it's single-estate."</p>
                <div class="review-who">
                    <div class="review-avatar">RM</div>
                    <div><strong>Rhea M.</strong><span>Mumbai, Maharashtra</span></div>
                </div>
            </div>
            <div class="review-card">
                <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                <p>"Ordered a gamosa for a family function — the weave quality was better than anything I'd found locally."</p>
                <div class="review-who">
                    <div class="review-avatar">AS</div>
                    <div><strong>Arjun S.</strong><span>Bengaluru, Karnataka</span></div>
                </div>
            </div>
            <div class="review-card">
                <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                <p>"Packaging was careful and the honey came with a card naming the village it was foraged near. Nice touch."</p>
                <div class="review-who">
                    <div class="review-avatar">PN</div>
                    <div><strong>Priya N.</strong><span>Delhi NCR</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ NEWSLETTER ============ --}}
<section class="section section--dark">
    <div class="container newsletter reveal">
        <h2>Get first access to new harvests.</h2>
        <form class="newsletter-form" action="#" method="POST">
            @csrf
            <input type="email" name="email" placeholder="you@email.com" required aria-label="Email address">
            <button type="submit" class="btn btn-primary">Subscribe</button>
        </form>
    </div>
</section>

@endsection
