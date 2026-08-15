@extends('layouts.auth')

@section('title', 'Verify Your Email')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="thread-rule"></div>
        <span class="eyebrow auth-eyebrow">One More Step</span>
        <h1 class="auth-title">Verify your email</h1>
        <p class="auth-sub">We sent a verification link to <strong>{{ auth()->user()->email }}</strong>. Click it to activate your account.</p>

        @if (session('status') === 'verification-link-sent')
            <div class="alert alert-success">A new verification link has been sent.</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-outline">Resend Verification Email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="margin-top: 1rem;">
            @csrf
            <button type="submit" class="btn btn-ghost" style="width:100%; color: var(--forest-deep); border-color: var(--line-strong);">Logout</button>
        </form>
    </div>
</div>
@endsection
