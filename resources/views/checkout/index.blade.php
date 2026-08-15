@extends('layouts.app')

@section('title', 'Checkout — KOPOU')

@section('content')
<div class="container" style="padding-block: 2.4rem 4rem;">
    <h1 style="font-size: var(--step-3); margin-bottom: 1.8rem;">Checkout</h1>

    <div class="checkout-layout">
        <div>
            <div class="checkout-panel">
                <h2>Delivery Address</h2>

                @if ($addresses->isNotEmpty())
                    <div id="saved-addresses">
                        @foreach ($addresses as $address)
                            <label class="saved-address-option {{ $loop->first ? 'selected' : '' }}">
                                <input type="radio" name="address_choice" value="{{ $address->id }}" {{ $loop->first ? 'checked' : '' }}>
                                <span>
                                    <strong>{{ $address->full_name }}</strong> ({{ $address->label }})<br>
                                    {{ $address->line1 }}{{ $address->line2 ? ', '.$address->line2 : '' }},
                                    {{ $address->city }}, {{ $address->state }} — {{ $address->pincode }}<br>
                                    Phone: {{ $address->phone }}
                                </span>
                            </label>
                        @endforeach
                        <label class="saved-address-option" id="new-address-option">
                            <input type="radio" name="address_choice" value="new">
                            <span><strong>Use a new address</strong></span>
                        </label>
                    </div>
                @endif

                <form id="checkout-address-form" class="checkout-form-grid" style="{{ $addresses->isNotEmpty() ? 'display:none; margin-top:1.2rem;' : 'margin-top:0.4rem;' }}">
                    <div class="form-field full">
                        <label for="full_name">Full name</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>
                    <div class="form-field">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-field">
                        <label for="pincode">PIN code</label>
                        <input type="text" id="pincode_field" name="pincode" maxlength="10" required>
                    </div>
                    <div class="form-field full">
                        <label for="line1">Address line 1</label>
                        <input type="text" id="line1" name="line1" required>
                    </div>
                    <div class="form-field full">
                        <label for="line2">Address line 2 (optional)</label>
                        <input type="text" id="line2" name="line2">
                    </div>
                    <div class="form-field">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    <div class="form-field">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" required>
                    </div>
                    <div class="form-field full" style="display:flex; align-items:center; gap:0.5rem;">
                        <input type="checkbox" id="save_address" name="save_address" value="1" style="width:auto;" checked>
                        <label for="save_address" style="margin:0;">Save this address to my account</label>
                    </div>
                </form>

                <div class="checkout-error" id="checkout-error"></div>
            </div>
        </div>

        <div class="checkout-panel">
            <h2>Order Summary</h2>
            <div id="checkout-items">
                @foreach ($cartData['items'] as $item)
                    <div class="checkout-item-mini">
                        <img src="{{ $item['image'] }}" alt="">
                        <div>
                            <div>{{ $item['name'] }}</div>
                            <div class="qty">Qty {{ $item['quantity'] }} &middot; &#8377;{{ number_format($item['line_total'], 0) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="checkout-summary-line"><span>Subtotal</span><span>&#8377;{{ number_format($cartData['subtotal'], 0) }}</span></div>
            <div class="checkout-summary-line"><span>Shipping</span><span>{{ $shippingFee > 0 ? '₹'.number_format($shippingFee, 0) : 'Free' }}</span></div>
            <div class="checkout-summary-total"><span>Total</span><span>&#8377;{{ number_format($cartData['subtotal'] + $shippingFee, 0) }}</span></div>

            <button class="btn btn-dark checkout-pay-btn" id="pay-btn">Pay with Razorpay</button>
            <p style="font-size:0.75rem; opacity:0.6; margin-top:0.8rem;">By placing this order you agree to KOPOU's terms of sale. Payments are processed securely by Razorpay.</p>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
(function () {
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const payBtn = document.getElementById('pay-btn');
    const errorEl = document.getElementById('checkout-error');
    const savedAddresses = document.getElementById('saved-addresses');
    const newAddressForm = document.getElementById('checkout-address-form');

    if (savedAddresses) {
        savedAddresses.querySelectorAll('input[name="address_choice"]').forEach((radio) => {
            radio.addEventListener('change', () => {
                savedAddresses.querySelectorAll('.saved-address-option').forEach((el) => el.classList.remove('selected'));
                radio.closest('.saved-address-option').classList.add('selected');
                newAddressForm.style.display = radio.value === 'new' ? 'grid' : 'none';
            });
        });
    }

    function showError(msg) {
        errorEl.textContent = msg;
        errorEl.classList.add('show');
    }

    payBtn.addEventListener('click', function () {
        errorEl.classList.remove('show');
        payBtn.setAttribute('disabled', 'disabled');
        payBtn.textContent = 'Processing…';

        const chosen = savedAddresses ? savedAddresses.querySelector('input[name="address_choice"]:checked')?.value : 'new';
        const payload = {};

        if (chosen && chosen !== 'new') {
            payload.address_id = chosen;
        } else {
            const fd = new FormData(newAddressForm);
            for (const [k, v] of fd.entries()) payload[k] = v;
            payload.save_address = newAddressForm.querySelector('#save_address')?.checked ? 1 : 0;
            if (!payload.full_name || !payload.phone || !payload.line1 || !payload.city || !payload.state || !payload.pincode) {
                showError('Please fill in your delivery address.');
                resetButton();
                return;
            }
        }

        fetch('{{ route('checkout.store') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload),
        })
        .then(async (res) => {
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Could not start checkout.');
            return data;
        })
        .then((data) => {
            const options = {
                key: data.razorpay_key,
                amount: data.amount,
                currency: data.currency,
                name: 'KOPOU',
                description: 'Order ' + data.order_number,
                order_id: data.razorpay_order_id,
                prefill: {
                    name: data.customer_name,
                    email: data.customer_email,
                    contact: data.customer_phone,
                },
                theme: { color: '#3a5544' },
                handler: function (response) {
                    fetch('{{ route('checkout.verify') }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({
                            order_id: data.order_id,
                            razorpay_order_id: response.razorpay_order_id,
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_signature: response.razorpay_signature,
                        }),
                    })
                    .then(async (res) => {
                        const verifyData = await res.json();
                        if (!res.ok) throw new Error(verifyData.message || 'Payment verification failed.');
                        window.location.href = verifyData.redirect;
                    })
                    .catch((err) => { showError(err.message); resetButton(); });
                },
                modal: {
                    ondismiss: function () {
                        fetch(`/checkout/${data.order_id}/cancel`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                        });
                        resetButton();
                    },
                },
            };
            const rzp = new Razorpay(options);
            rzp.on('payment.failed', function () {
                fetch(`/checkout/${data.order_id}/cancel`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                });
                showError('Payment failed. Please try again.');
                resetButton();
            });
            rzp.open();
        })
        .catch((err) => { showError(err.message); resetButton(); });
    });

    function resetButton() {
        payBtn.removeAttribute('disabled');
        payBtn.textContent = 'Pay with Razorpay';
    }
})();
</script>
@endsection
