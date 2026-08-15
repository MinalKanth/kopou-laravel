@extends('layouts.admin')
@section('title', $product->exists ? 'Edit Product' : 'New Product')

@section('admin-content')
<div class="admin-form-card">
    <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST">
        @csrf
        @if ($product->exists) @method('PUT') @endif

        <div class="checkout-form-grid">
            <div class="form-field full">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
            </div>
            <div class="form-field">
                <label>Slug (optional)</label>
                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" placeholder="auto-generated if blank">
            </div>
            <div class="form-field">
                <label>SKU</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required>
            </div>
            <div class="form-field">
                <label>Category</label>
                <select name="category_id" required>
                    <option value="">Select…</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-field">
                <label>Status</label>
                <select name="status">
                    @foreach (['draft', 'active', 'inactive'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $product->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-field">
                <label>Brand</label>
                <input type="text" name="brand" value="{{ old('brand', $product->brand) }}">
            </div>
            <div class="form-field">
                <label>Origin</label>
                <input type="text" name="origin" value="{{ old('origin', $product->origin) }}">
            </div>
            <div class="form-field">
                <label>Price (&#8377;)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required>
            </div>
            <div class="form-field">
                <label>Sale Price (&#8377;, optional)</label>
                <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
            </div>
            <div class="form-field">
                <label>Stock Quantity</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->inventory->stock_quantity ?? 0) }}" required>
            </div>
            <div class="form-field">
                <label>Low Stock Threshold</label>
                <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->inventory->low_stock_threshold ?? 5) }}">
            </div>
            <div class="form-field full">
                <label>Image URL</label>
                <input type="text" name="image_url" value="{{ old('image_url', $product->images->first()->url ?? '') }}" placeholder="https://…">
            </div>
            <div class="form-field full">
                <label>Short Description</label>
                <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}">
            </div>
            <div class="form-field full">
                <label>Description</label>
                <textarea name="description">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <div style="margin-top:1.5rem; display:flex; gap:0.8rem;">
            <button type="submit" class="btn btn-dark">{{ $product->exists ? 'Save Changes' : 'Create Product' }}</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
