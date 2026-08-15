<div class="announce">
    <div class="announce-track">
        <span>Free delivery across India on orders above &#8377;999 &nbsp;&middot;&nbsp; Every product traced to its Assam origin</span>
        <span>Free delivery across India on orders above &#8377;999 &nbsp;&middot;&nbsp; Every product traced to its Assam origin</span>
    </div>
</div>

<div class="nav-wrap" data-nav>
    <div class="container nav">
        <a href="{{ route('home') }}" class="nav-logo" data-hoverable>KOPOU <span>Assam</span></a>

        <nav class="nav-links" aria-label="Primary">
            <a href="{{ route('products.index') }}" data-hoverable>Tea</a>
            <a href="{{ route('products.index') }}" data-hoverable>Handloom</a>
            <a href="{{ route('products.index') }}" data-hoverable>Handicrafts</a>
            <a href="{{ route('products.index') }}" data-hoverable>Food &amp; Delicacies</a>
            <a href="{{ route('products.index') }}" data-hoverable>Gift Boxes</a>
            <a href="{{ route('home') }}#heritage" data-hoverable>Our Story</a>
        </nav>

        <div class="nav-actions">
            <button class="nav-icon-btn" aria-label="Search" data-search-open data-hoverable data-cursor-text="Search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            </button>
            <a href="{{ route('wishlist.index') }}" class="nav-icon-btn" aria-label="Wishlist" data-hoverable>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
            </a>
            <button class="nav-icon-btn" aria-label="Open cart" data-cart-open data-hoverable data-cursor-text="Cart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6 5 2H2"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>
                <span class="cart-count" data-cart-count>0</span>
            </button>
            <a href="{{ auth()->check() ? route('account.dashboard') : route('login') }}" class="nav-icon-btn" aria-label="Account" data-hoverable>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20c1.8-4 5-6 8-6s6.2 2 8 6"/></svg>
            </a>
            <button class="nav-burger" data-burger aria-label="Open menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>
    </div>
    <div class="thread-rule" data-thread-drift></div>
</div>

<div class="mobile-drawer" data-drawer aria-hidden="true">
    <button class="mobile-drawer-close" data-drawer-close aria-label="Close menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="26" height="26"><path d="M6 6l12 12M18 6 6 18"/></svg>
    </button>
    <a href="{{ route('products.index') }}">Tea</a>
    <a href="{{ route('products.index') }}">Handloom</a>
    <a href="{{ route('products.index') }}">Handicrafts</a>
    <a href="{{ route('products.index') }}">Food &amp; Delicacies</a>
    <a href="{{ route('products.index') }}">Gift Boxes</a>
    <a href="{{ route('home') }}#heritage">Our Story</a>
</div>
