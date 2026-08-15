@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="thread-rule"></div>
        <span class="eyebrow auth-eyebrow">Welcome Back</span>
        <h1 class="auth-title">Sign in to KOPOU</h1>
        <p class="auth-sub">Track orders, manage your wishlist, and check out faster.</p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @error('email')
            <div class="alert alert-error">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-field">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>

            <div class="form-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <div class="form-row-inline">
                <label class="form-checkbox">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="{{ route('password.request') }}" style="font-size:0.82rem; color: var(--terracotta-deep);">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>

        <p class="auth-footer-link">New to KOPOU? <a href="{{ route('register') }}">Create an account</a></p>
    </div>
</div>
@endsection
