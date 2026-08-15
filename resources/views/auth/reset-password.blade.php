@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="thread-rule"></div>
        <span class="eyebrow auth-eyebrow">Account Recovery</span>
        <h1 class="auth-title">Set a new password</h1>

        @if ($errors->any())
            <div class="alert alert-error">Please fix the errors below and try again.</div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-field">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required autofocus>
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-field">
                <label for="password">New password</label>
                <input type="password" id="password" name="password" required autocomplete="new-password">
                <p class="form-hint">At least 10 characters, with upper &amp; lowercase letters and a number.</p>
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-field">
                <label for="password_confirmation">Confirm new password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
</div>
@endsection
