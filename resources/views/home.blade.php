@extends('layouts.app')

@section('title', 'KOPOU — Assam, Delivered. A Flagship Digital House for Assamese Craft.')
@section('meta_description', 'KOPOU brings tea, handloom silk, handicraft and delicacies from Assam\'s growers and artisans direct to your door — every product traced to where it was made.')

@section('content')

@php
    // Fallback imagery for categories whose `image` column isn't populated yet
    // (see README: product/category photography is still placeholder-only).
    $catFallbackImages = [
        'https://images.unsplash.com/photo-1563822249366-3efb23b8e0c9?q=80&w=900&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1528459801416-a9e53bbf4e17?q=80&w=900&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1610701596007-11502861dcfa?q=80&w=900&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1490474418585-ba9bad8fd0ea?q=80&w=900&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1607344645866-009c320b63e0?q=80&w=900&auto=format&fit=crop',
    ];
@endphp

{{-- ============ HERO ============ --}}
<section class="hero" data-hero>
    <div class="hero-bg">
        <img src="https://images.unsplash.com/photo-1544787219-7f47ccb76574?q=80&w=1600&auto=format&fit=crop" alt="Misty tea plantation at sunrise in Assam" loading="eager">
    </div>
    <canvas class="hero-canvas" data-hero-canvas aria-hidden="true"></canvas>
    <div class="hero-spotlight" data-hero-spotlight aria-hidden="true"></div>
    <div class="hero-copy">
        <div class="hero-copy-inner">
            <span class="eyebrow" data-hero-anim="eyebrow">Assam, Delivered</span>
            <h1 class="hero-title">
                <span class="line"><span data-hero-anim="line">From Assam,</span></span>
                <span class="line"><span data-hero-anim="line"><em>With Authenticity.</em></span></span>
            </h1>
            <p class="hero-sub" data-hero-anim="sub">Tea, handloom silk, handicrafts and traditional delicacies — sourced directly from Assam's growers and artisans, delivered anywhere in India.</p>
            <div class="hero-cta-row" data-hero-anim="cta">
                <a href="#categories" class="btn btn-primary" data-hoverable>Explore Assam</a>
                <a href="#bestsellers" class="btn btn-ghost" data-hoverable>Shop Best Sellers</a>
            </div>
            <div class="hero-stats" data-hero-anim="stats">
                <div><strong data-counter="3200" data-suffix="+">0</strong><span>Orders delivered pan-India</span></div>
                <div><strong data-counter="60" data-suffix="+">0</strong><span>Partner artisans &amp; estates</span></div>
                <div><strong data-counter="4.8" data-decimals="1" data-suffix="&#9733;">0</strong><span>Average customer rating</span></div>
            </div>
        </div>
    </div>
    <div class="hero-scrollcue"><span>Scroll to travel through Assam</span><div class="hero-scrollcue-line"></div></div>
</section>

{{-- ============ CATEGORIES ============ --}}
<section class="section" id="categories">
    <div class="container">
        <div class="section-head">
            <h2 class="split-heading" data-split-heading>Shop by Category</h2>
            <p class="rv rv-up-sm" data-rv>Five categories, one standard: every product traced back to where it was grown, spun or hand-finished in Assam.</p>
        </div>
    </div>
    <div class="container">
        <div class="cat-rail rv rv-scale" data-rv>
            @foreach ($categories as $i => $cat)
                <a href="{{ url('/categories/'.$cat['slug']) }}" class="cat-panel" data-hoverable data-cursor-text="View">
                    <div class="cat-panel-thread"></div>
                    <img src="{{ $cat['image'] ?? ($catFallbackImages[$i % count($catFallbackImages)]) }}" alt="{{ $cat['name'] }}" loading="lazy">
                    <div class="cat-panel-content">
                        <span class="cat-panel-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }} &middot; {{ $cat['product_count'] }} products</span>
                        <h3 class="cat-panel-name">{{ $cat['name'] }}</h3>
                        @if (!empty($cat['description']))
                            <span class="cat-panel-loc">{{ $cat['description'] }}</span>
                        @endif
                        <span class="cat-panel-arrow">Shop now <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ BESTSELLERS ============ --}}
<section class="section section--tint" id="bestsellers">
    <div class="container">
        <div class="section-head">
            <h2 class="split-heading" data-split-heading>Best Sellers</h2>
            <p class="rv rv-up-sm" data-rv>The products customers across India keep coming back for.</p>
        </div>
        <div class="product-grid" data-stagger-grid>
            @foreach ($bestsellers as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

{{-- ============ FEATURED ============ --}}
<section class="section">
    <div class="container">
        <div class="section-head">
            <h2 class="split-heading" data-split-heading>Featured This Week</h2>
            <p class="rv rv-up-sm" data-rv>A running edit across tea, handloom, craft and pantry — refreshed weekly.</p>
        </div>
        <div class="product-grid" data-stagger-grid>
            @foreach ($featured as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

{{-- ============ HERITAGE — pinned scroll story ============ --}}
<section class="section--dark heritage-pin" id="heritage">
    <div class="heritage-stage" data-heritage-stage>
        <div class="heritage-sticky">
            <div class="ambient-drift" data-ambient-drift aria-hidden="true"></div>
            <div class="heritage-media-stack">
                <div class="heritage-media-frame active" data-frame="0"><img src="https://images.unsplash.com/photo-1544787219-7f47ccb76574?q=80&w=1600&auto=format&fit=crop" alt="Tea garden at dawn, Dibrugarh"></div>
                <div class="heritage-media-frame" data-frame="1"><img src="https://images.unsplash.com/photo-1528459801416-a9e53bbf4e17?q=80&w=1600&auto=format&fit=crop" alt="Weaver at a traditional loom in Sualkuchi"></div>
                <div class="heritage-media-frame" data-frame="2"><img src="https://images.unsplash.com/photo-1610701596007-11502861dcfa?q=80&w=1600&auto=format&fit=crop" alt="Bell-metal workshop in Sarthebari"></div>
                <div class="heritage-media-frame" data-frame="3"><img src="https://images.unsplash.com/photo-1490474418585-ba9bad8fd0ea?q=80&w=1600&auto=format&fit=crop" alt="A home kitchen in rural Assam"></div>
            </div>
            <div class="heritage-copy-stack">
                <div class="heritage-frame-copy active" data-copy="0">
                    <span class="eyebrow">01 &middot; Dibrugarh</span>
                    <h2>Where the tea begins.</h2>
                    <p>Before sunrise, pickers move through gardens that have grown Assam's orthodox tea for generations. Every leaf we sell can be traced back to the estate that grew it.</p>
                </div>
                <div class="heritage-frame-copy" data-copy="1">
                    <span class="eyebrow">02 &middot; Sualkuchi</span>
                    <h2>Where silk is spoken.</h2>
                    <p>On the banks of the Brahmaputra, entire households weave muga and pat silk on looms passed down through families. Nothing here is mass-produced.</p>
                </div>
                <div class="heritage-frame-copy" data-copy="2">
                    <span class="eyebrow">03 &middot; Sarthebari</span>
                    <h2>Where metal becomes memory.</h2>
                    <p>Bell-metal artisans in Sarthebari hand-hammer kahi and bota the way their guild has for centuries — each piece slightly, deliberately, imperfect.</p>
                </div>
                <div class="heritage-frame-copy" data-copy="3">
                    <span class="eyebrow">04 &middot; Home Kitchens</span>
                    <h2>Where flavor is kept honest.</h2>
                    <p>Our pickles, pithas and honey come from home kitchens and small producers across Assam's 28 districts — recipes that haven't changed to suit a factory.</p>
                    <div class="heritage-facts">
                        <div><strong data-counter="60" data-suffix="+">0</strong><span>Growers &amp; artisans</span></div>
                        <div><strong data-counter="28" data-suffix="">0</strong><span>Districts represented</span></div>
                        <div><strong data-counter="100" data-suffix="%">0</strong><span>Assam-origin catalog</span></div>
                    </div>
                </div>
            </div>
            <div class="heritage-progress" data-heritage-progress>
                <span class="active"></span><span></span><span></span><span></span>
            </div>
        </div>
    </div>
</section>

{{-- ============ ORIGIN THREAD ============ --}}
<section class="section origin-strip">
    <div class="container">
        <div class="section-head" style="margin-bottom:2.4rem;">
            <h2 class="split-heading" data-split-heading>Every Product Has an Origin</h2>
            <p class="rv rv-up-sm" data-rv>The thread that runs through everything we sell — from the person who made it to your door.</p>
        </div>
    </div>
    <div class="origin-track rv rv-scale" data-rv data-origin-track>
        <div class="origin-node" data-hoverable data-origin-node><div class="origin-node-dot"></div><span>Maker</span></div>
        <div class="origin-node" data-hoverable data-origin-node><div class="origin-node-dot"></div><span>Village</span></div>
        <div class="origin-node" data-hoverable data-origin-node><div class="origin-node-dot"></div><span>District</span></div>
        <div class="origin-node" data-hoverable data-origin-node><div class="origin-node-dot"></div><span>Assam</span></div>
        <div class="origin-node" data-hoverable data-origin-node><div class="origin-node-dot"></div><span>Your Home</span></div>
    </div>
</section>

{{-- ============ TRUST STRIP ============ --}}
<section class="section">
    <div class="container">
        <div class="section-head"><h2 class="split-heading" data-split-heading>Why Buy From KOPOU</h2></div>
        <div class="trust-grid" data-stagger-grid>
            <div class="trust-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 2 3 6v6c0 5 4 8.5 9 10 5-1.5 9-5 9-10V6l-9-4Z"/><path d="m8 12 3 3 5-6"/></svg><h4>Verified origin</h4><p>Sourced directly from Assam growers and artisans.</p></div>
            <div class="trust-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a4 4 0 0 1 8 0v2"/></svg><h4>Secure payments</h4><p>Encrypted checkout, verified server-side on every order.</p></div>
            <div class="trust-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 11h13v7H3z"/><path d="M16 13h3l2 3v2h-5z"/><circle cx="7" cy="20" r="1.4"/><circle cx="17.5" cy="20" r="1.4"/></svg><h4>Pan-India delivery</h4><p>Shipped to every serviceable PIN code in the country.</p></div>
            <div class="trust-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 4h16v4H4z"/><path d="M4 8v12h16V8"/><path d="M9 12h6"/></svg><h4>Quality checked</h4><p>Every batch inspected before it leaves Assam.</p></div>
            <div class="trust-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M21 12a9 9 0 1 1-3-6.7"/><path d="M21 3v6h-6"/></svg><h4>Easy returns</h4><p>Straightforward return window on eligible products.</p></div>
        </div>
    </div>
</section>

{{-- ============ REVIEWS ============ --}}
<section class="section section--tint">
    <div class="container">
        <div class="section-head"><h2 class="split-heading" data-split-heading>What Customers Say</h2></div>
    </div>
    <div class="review-rail-wrap rv rv-up" data-rv data-review-wrap data-hoverable data-cursor-text="Drag">
        <div class="review-rail" data-review-rail>
            <div class="review-card"><div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div><p>"The orthodox tea tasted nothing like what I'd get at a supermarket. You can tell it's single-estate."</p><div class="review-who"><img class="review-avatar" src="https://i.pravatar.cc/100?img=47" alt=""><div><strong>Rhea M.</strong><span>Mumbai, Maharashtra</span></div></div></div>
            <div class="review-card"><div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div><p>"Ordered a gamosa for a family function — the weave quality was better than anything I'd found locally."</p><div class="review-who"><img class="review-avatar" src="https://i.pravatar.cc/100?img=12" alt=""><div><strong>Arjun S.</strong><span>Bengaluru, Karnataka</span></div></div></div>
            <div class="review-card"><div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div><p>"Packaging was careful and the honey came with a card naming the village it was foraged near. Nice touch."</p><div class="review-who"><img class="review-avatar" src="https://i.pravatar.cc/100?img=32" alt=""><div><strong>Priya N.</strong><span>Delhi NCR</span></div></div></div>
            <div class="review-card"><div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div><p>"Genuinely didn't expect a bell-metal bowl bought online to feel this well made. It's becoming our everyday serving piece."</p><div class="review-who"><img class="review-avatar" src="https://i.pravatar.cc/100?img=8" alt=""><div><strong>Karan V.</strong><span>Pune, Maharashtra</span></div></div></div>
            <div class="review-card"><div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div><p>"The muga stole is stunning — you can feel it's handwoven. Worth every rupee."</p><div class="review-who"><img class="review-avatar" src="https://i.pravatar.cc/100?img=25" alt=""><div><strong>Ananya D.</strong><span>Kolkata, West Bengal</span></div></div></div>
        </div>
    </div>
    <div class="container"><p class="review-drag-hint">Drag to explore</p></div>
</section>

{{-- ============ NEWSLETTER ============ --}}
<section class="section section--dark" data-ambient-section>
    <div class="ambient-drift" data-ambient-drift aria-hidden="true"></div>
    <div class="container newsletter rv rv-up" data-rv>
        <h2 class="split-heading" data-split-heading>Get first access to new harvests.</h2>
        <form class="newsletter-form" action="#" method="POST">
            @csrf
            <input type="email" name="email" placeholder="you@email.com" required aria-label="Email address">
            <button type="submit" class="btn btn-primary" data-hoverable>Subscribe</button>
        </form>
    </div>
</section>

@endsection
