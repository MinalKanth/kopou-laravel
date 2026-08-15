@extends('layouts.account')
@section('title', 'Security')

@section('account-content')
<h1>Security</h1>
<p class="account-lead">Manage your password and account access.</p>

<div class="account-panel">
    <h3>Change password</h3>
    <form method="POST" action="{{ route('account.security.password') }}">
        @csrf
        @method('PUT')

        <div class="form-field">
            <label for="current_password">Current password</label>
            <input type="password" id="current_password" name="current_password" required autocomplete="current-password" class="{{ $errors->has('current_password') ? 'has-error' : '' }}">
            @error('current_password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-field">
            <label for="password">New password</label>
            <input type="password" id="password" name="password" required autocomplete="new-password" class="{{ $errors->has('password') ? 'has-error' : '' }}">
            <p class="form-hint">At least 10 characters, with upper &amp; lowercase letters and a number.</p>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-field">
            <label for="password_confirmation">Confirm new password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary">Update Password</button>
    </form>
</div>

<div class="account-panel danger">
    <h3>Delete account</h3>
    <p style="color:var(--forest); opacity:.8; font-size:0.88rem; margin-bottom:1.2rem;">
        This deactivates your account and signs you out everywhere. Confirm with your password.
    </p>
    <form method="POST" action="{{ route('account.security.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone from here.');">
        @csrf
        @method('DELETE')

        <div class="form-field">
            <label for="delete_password">Password</label>
            <input type="password" id="delete_password" name="password" required class="{{ $errors->has('password') ? 'has-error' : '' }}">
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary" style="background: var(--terracotta-deep);">Delete My Account</button>
    </form>
</div>
@endsection
