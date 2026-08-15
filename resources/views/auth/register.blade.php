@extends('layouts.auth')

@section('title', 'Create Account')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="thread-rule"></div>
        <span class="eyebrow auth-eyebrow">Join KOPOU</span>
        <h1 class="auth-title">Create your account</h1>
        <p class="auth-sub">Free to join. Faster checkout, order tracking, and a saved wishlist.</p>

        @if ($errors->any())
            <div class="alert alert-error">Please fix the errors below and try again.</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-field">
                <label for="name">Full name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="{{ $errors->has('name') ? 'has-error' : '' }}">
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-field">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="{{ $errors->has('email') ? 'has-error' : '' }}">
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-field">
                <label for="phone">Phone (optional)</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel">
            </div>

            <div class="form-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="new-password" class="{{ $errors->has('password') ? 'has-error' : '' }}">
                <p class="form-hint">At least 10 characters, with upper &amp; lowercase letters and a number.</p>
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-field">
                <label for="password_confirmation">Confirm password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary">Create Account</button>
        </form>

        <p class="auth-footer-link">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
    </div>
</div>
@endsection
