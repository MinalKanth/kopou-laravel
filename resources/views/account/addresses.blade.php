@extends('layouts.account')
@section('title', 'Saved Addresses')

@section('account-content')
<h1>Saved Addresses</h1>

<div class="address-grid">
    @foreach ($addresses as $address)
        <div class="address-card {{ $address->is_default ? 'is-default' : '' }}">
            @if ($address->is_default)<span class="default-tag">Default</span>@endif
            <strong>{{ $address->full_name }}</strong> <span style="opacity:0.6; font-size:0.78rem;">({{ $address->label }})</span>
            <p style="font-size:0.85rem; margin-top:0.4rem; opacity:0.8;">
                {{ $address->line1 }}{{ $address->line2 ? ', '.$address->line2 : '' }}<br>
                {{ $address->city }}, {{ $address->state }} — {{ $address->pincode }}<br>
                Phone: {{ $address->phone }}
            </p>
            <div class="address-card-actions">
                @unless ($address->is_default)
                    <form action="{{ route('account.addresses.update', $address) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="full_name" value="{{ $address->full_name }}">
                        <input type="hidden" name="phone" value="{{ $address->phone }}">
                        <input type="hidden" name="line1" value="{{ $address->line1 }}">
                        <input type="hidden" name="line2" value="{{ $address->line2 }}">
                        <input type="hidden" name="city" value="{{ $address->city }}">
                        <input type="hidden" name="state" value="{{ $address->state }}">
                        <input type="hidden" name="pincode" value="{{ $address->pincode }}">
                        <input type="hidden" name="is_default" value="1">
                        <button type="submit">Make Default</button>
                    </form>
                @endunless
                <form action="{{ route('account.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Remove this address?');">
                    @csrf @method('DELETE')
                    <button type="submit" style="color:var(--terracotta-deep);">Remove</button>
                </form>
            </div>
        </div>
    @endforeach
</div>

<h3 style="margin-top:2.4rem; font-size:1rem;">Add a New Address</h3>
<form action="{{ route('account.addresses.store') }}" method="POST" class="checkout-form-grid" style="margin-top:1rem; max-width:640px;">
    @csrf
    <div class="form-field"><label>Label</label><input type="text" name="label" placeholder="Home / Work"></div>
    <div class="form-field"><label>Full name</label><input type="text" name="full_name" required></div>
    <div class="form-field"><label>Phone</label><input type="tel" name="phone" required></div>
    <div class="form-field"><label>PIN code</label><input type="text" name="pincode" maxlength="10" required></div>
    <div class="form-field full"><label>Address line 1</label><input type="text" name="line1" required></div>
    <div class="form-field full"><label>Address line 2</label><input type="text" name="line2"></div>
    <div class="form-field"><label>City</label><input type="text" name="city" required></div>
    <div class="form-field"><label>State</label><input type="text" name="state" required></div>
    <div class="form-field full">
        <button type="submit" class="btn btn-dark">Save Address</button>
    </div>
</form>
@endsection
