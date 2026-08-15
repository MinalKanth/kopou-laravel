<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KOPOU — Authentic Assam, Delivered Across India')</title>
    <meta name="description" content="@yield('meta_description', 'Curated Assam tea, handloom silk, handicrafts and traditional delicacies, delivered pan-India. Sourced directly, quality checked, authentically Assamese.')">

    {{-- Fonts: Fraunces (display) + Inter (body/UI) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=Inter+Tight:wght@500;600&display=swap" rel="stylesheet">

    {{-- GSAP + Lenis: loaded as plain globals before the Vite bundle so
         app.js (a module script, deferred by the browser) can find
         window.gsap / window.ScrollTrigger / window.Lenis when it runs. --}}
    <script src="https://unpkg.com/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://unpkg.com/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body data-wishlist="{{ $wishlistSlugsJson ?? '[]' }}">

    {{-- Preloader --}}
    <div class="preloader" data-preloader>
        <div class="preloader-inner">
            <span class="preloader-logo">KOPOU<span>.</span></span>
            <span class="preloader-sub">Assam, Delivered.</span>
            <div class="preloader-thread"><div class="preloader-thread-fill"></div></div>
        </div>
    </div>

    {{-- Custom cursor --}}
    <div class="cursor" data-cursor></div>
    <div class="cursor-ring" data-cursor-ring><span class="cursor-label" data-cursor-label></span></div>

    {{-- Scroll progress --}}
    <div class="scroll-progress"><div class="scroll-progress-bar" data-scroll-bar></div></div>

    @include('partials.navigation')

    {{-- Search overlay --}}
    <div class="search-overlay" data-search-overlay role="dialog" aria-modal="true" aria-label="Search">
        <div class="search-panel">
            <form class="search-input-row" action="{{ route('search') }}" method="GET" data-search-form>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" name="q" placeholder="Search tea, silk, gamosa, honey…" data-search-input autocomplete="off">
                <button type="button" class="search-close" data-search-close aria-label="Close search"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
            </form>
            <p class="search-hint">Press <kbd>Esc</kbd> to close</p>
            <div class="search-suggest">
                <a href="{{ route('search', ['q' => 'tea']) }}">Orthodox black tea</a>
                <a href="{{ route('search', ['q' => 'muga silk']) }}">Muga silk</a>
                <a href="{{ route('search', ['q' => 'gamosa']) }}">Gamosa</a>
                <a href="{{ route('search', ['q' => 'bell metal']) }}">Bell metal</a>
                <a href="{{ route('search', ['q' => 'honey']) }}">Forest honey</a>
                <a href="{{ route('search', ['q' => 'gift box']) }}">Gift boxes</a>
            </div>
        </div>
    </div>

    {{-- Mini cart drawer — server-backed cart (Phase 6): hydrated via
         GET /cart on load, mutated via /cart/items endpoints. --}}
    <div class="cart-drawer-overlay" data-cart-overlay></div>
    <aside class="cart-drawer" data-cart-drawer role="dialog" aria-modal="true" aria-label="Shopping cart">
        <div class="cart-drawer-head">
            <h3>Your Bag</h3>
            <button data-cart-close aria-label="Close cart"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
        </div>
        <div class="cart-drawer-body" data-cart-body>
            <div class="cart-empty">Your bag is empty. Add something from Assam.</div>
        </div>
        <div class="cart-drawer-foot">
            <div class="cart-subtotal-row"><span>Subtotal</span><span data-cart-subtotal>&#8377;0</span></div>
            <a href="{{ route('checkout.show') }}" class="btn btn-dark checkout-pay-btn" data-cart-checkout-link>Checkout</a>
        </div>
    </aside>

    {{-- Quick view modal --}}
    <div class="qv-overlay" data-qv-overlay role="dialog" aria-modal="true" aria-label="Product quick view">
        <div class="qv-modal" data-qv-modal></div>
    </div>

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>
