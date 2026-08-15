@extends('layouts.app')

@section('title', ($title ?? 'Account').' — KOPOU')

@section('content')
<section class="section" style="padding-block: 0.5rem 4rem;">
    <div class="container account-layout">
        <aside class="account-sidebar">
            <div class="account-sidebar-head">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ auth()->user()->email }}</span>
            </div>
            <nav class="account-nav">
                <a href="{{ route('account.dashboard') }}" class="{{ request()->routeIs('account.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('account.orders.index') }}" class="{{ request()->routeIs('account.orders.*') ? 'active' : '' }}">My Orders</a>
                <a href="{{ route('wishlist.index') }}">Wishlist <span class="badge-count">{{ count(session('wishlist_slugs', [])) }}</span></a>
                <a href="{{ route('account.addresses.index') }}" class="{{ request()->routeIs('account.addresses.*') ? 'active' : '' }}">Saved Addresses</a>
                <a href="{{ route('account.profile.edit') }}" class="{{ request()->routeIs('account.profile.edit') ? 'active' : '' }}">Profile</a>
                <a href="{{ route('account.security.edit') }}" class="{{ request()->routeIs('account.security.edit') ? 'active' : '' }}">Security</a>
                <a href="#" class="disabled-link" title="Coming soon">Notifications</a>
                <a href="#" class="disabled-link" title="Coming soon">Coupons</a>
                <a href="#" class="disabled-link" title="Coming soon">Reviews</a>
                <a href="#">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </a>
            </nav>
        </aside>

        <div class="account-main">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @yield('account-content')
        </div>
    </div>
</section>
@endsection
