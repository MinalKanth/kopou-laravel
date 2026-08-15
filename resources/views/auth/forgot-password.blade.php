@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="thread-rule"></div>
        <span class="eyebrow auth-eyebrow">Account Recovery</span>
        <h1 class="auth-title">Reset your password</h1>
        <p class="auth-sub">Enter the email on your account and we'll send a reset link.</p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @error('email')
            <div class="alert alert-error">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-field">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </form>

        <p class="auth-footer-link"><a href="{{ route('login') }}">Back to sign in</a></p>
    </div>
</div>
@endsection
