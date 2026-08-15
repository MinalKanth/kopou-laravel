@extends('layouts.account')
@section('title', 'Profile')

@section('account-content')
<h1>Profile</h1>
<p class="account-lead">Keep your contact details up to date.</p>

<div class="account-panel">
    <h3>Personal information</h3>
    <form method="POST" action="{{ route('account.profile.update') }}">
        @csrf
        @method('PUT')

        <div class="form-field">
            <label for="name">Full name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="{{ $errors->has('name') ? 'has-error' : '' }}">
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="{{ $errors->has('email') ? 'has-error' : '' }}">
            <p class="form-hint">Changing your email will require re-verification.</p>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-field">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
@endsection
