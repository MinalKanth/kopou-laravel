@extends('layouts.app')

@section('title', 'Your Wishlist — KOPOU')

@section('content')
<div class="container" style="padding-block: 1.6rem 0;">
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <span>Wishlist</span>
    </nav>
</div>

<section class="section" style="padding-top:1.2rem;">
    <div class="container">
        <div class="section-head">
            <h2>Your Wishlist</h2>
            <p>{{ count($products) }} saved {{ Str::plural('item', count($products)) }}.</p>
        </div>

        @if (empty($products))
            <div class="plp-empty">
                <h3>Your wishlist is empty</h3>
                <p>Tap the heart on any product to save it here.</p>
                <a href="{{ route('products.index') }}" class="btn btn-outline">Browse products</a>
            </div>
        @else
            <div class="product-grid">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
