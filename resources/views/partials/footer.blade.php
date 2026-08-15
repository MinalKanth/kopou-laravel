<footer class="footer">
    <span class="footer-mark" aria-hidden="true" data-footer-mark>KOPOU</span>
    <div class="thread-rule thread-rule--onDark" data-thread-drift></div>
    <div class="container footer-grid">
        <div class="footer-brand">
            <a href="{{ route('home') }}" class="nav-logo" data-hoverable>KOPOU <span>Assam</span></a>
            <p>Authentic Assam tea, handloom, handicrafts and delicacies, sourced directly and delivered across India.</p>
            <div class="footer-social" style="margin-top:1.4rem;">
                <a href="#" aria-label="Instagram" data-hoverable><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg></a>
                <a href="#" aria-label="Facebook" data-hoverable><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M14 9h3V6h-3a4 4 0 0 0-4 4v2H7v3h3v6h3v-6h3l1-3h-4v-2a1 1 0 0 1 1-1Z"/></svg></a>
                <a href="#" aria-label="Pinterest" data-hoverable><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M9 17c1-3 1.3-5.2 1.3-6.6a1.9 1.9 0 0 1 3.7.4c0 1.4-1 3.4-1.5 4.4a1.6 1.6 0 0 0 2.9 1.3c1-1 1.5-2.5 1.5-4a4.7 4.7 0 0 0-5-4.8 5.2 5.2 0 0 0-5.4 5c0 1 .3 1.8.8 2.4"/></svg></a>
            </div>
        </div>

        <div class="footer-col">
            <h5>Shop</h5>
            <a href="{{ route('products.index') }}" data-hoverable>Assam Tea</a>
            <a href="{{ route('products.index') }}" data-hoverable>Handloom &amp; Textiles</a>
            <a href="{{ route('products.index') }}" data-hoverable>Handicrafts</a>
            <a href="{{ route('products.index') }}" data-hoverable>Food &amp; Delicacies</a>
            <a href="{{ route('products.index') }}" data-hoverable>Gift Boxes</a>
        </div>

        <div class="footer-col">
            <h5>Support</h5>
            <a href="#" data-hoverable>Track Your Order</a>
            <a href="#" data-hoverable>Shipping &amp; Delivery</a>
            <a href="#" data-hoverable>Returns &amp; Refunds</a>
            <a href="#" data-hoverable>Contact Us</a>
            <a href="#" data-hoverable>FAQs</a>
        </div>

        <div class="footer-col">
            <h5>Company</h5>
            <a href="{{ route('home') }}#heritage" data-hoverable>Our Story</a>
            <a href="#" data-hoverable>Our Artisans</a>
            <a href="#" data-hoverable>Sustainability</a>
            <a href="#" data-hoverable>Terms of Service</a>
            <a href="#" data-hoverable>Privacy Policy</a>
        </div>
    </div>

    <div class="container footer-bottom">
        <span>&copy; {{ date('Y') }} KOPOU. All rights reserved.</span>
        <span>Made with care for Assam's makers.</span>
    </div>
</footer>
