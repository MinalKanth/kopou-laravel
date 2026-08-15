@extends('layouts.account')
@section('title', 'Dashboard')

@section('account-content')
<h1>Welcome back, {{ explode(' ', $user->name)[0] }}.</h1>
<p class="account-lead">Here's what's happening with your KOPOU account.</p>

<div class="account-stats">
    <div class="account-stat"><strong>{{ $stats['orders'] }}</strong><span>Orders placed</span></div>
    <div class="account-stat"><strong>{{ $stats['wishlist'] }}</strong><span>Wishlist items</span></div>
    <div class="account-stat"><strong>{{ $stats['addresses'] }}</strong><span>Saved addresses</span></div>
</div>

<div class="account-panel">
    <h3>Account details</h3>
    <p style="color:var(--forest); opacity:.8; font-size:0.9rem;">
        Signed in as <strong>{{ $user->email }}</strong>
        @if (!$user->hasVerifiedEmail())
            &mdash; <span style="color: var(--terracotta-deep);">email not verified</span>
        @endif
    </p>
    <p style="margin-top:1rem;">
        <a href="{{ route('account.profile.edit') }}" class="btn btn-outline">Edit Profile</a>
        <a href="{{ route('account.security.edit') }}" class="btn btn-outline" style="margin-left:0.6rem;">Security Settings</a>
    </p>
</div>
@endsection
