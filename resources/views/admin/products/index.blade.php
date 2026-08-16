@extends('layouts.admin')
@section('title', 'Products')

@section('admin-content')
<div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1.2rem; flex-wrap:wrap;">
    <form class="admin-search" method="GET" style="flex:1; max-width:360px; margin-bottom:0;">
        <input type="text" name="q" value="{{ $q }}" placeholder="Search products…">
        <button type="submit" class="admin-btn admin-btn-outline">Search</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn-dark">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Product
    </a>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead><tr><th></th><th>Product</th><th>SKU</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td><img class="thumb" src="{{ $product->images->first()->url ?? '/images/placeholder.jpg' }}" alt=""></td>
                    <td class="admin-cell-primary">{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->category->name ?? '—' }}</td>
                    <td class="admin-cell-primary">&#8377;{{ number_format($product->sale_price ?? $product->price, 0) }}</td>
                    <td>{{ $product->inventory->stock_quantity ?? 0 }}</td>
                    <td><span class="status-pill status-{{ $product->status === 'active' ? 'delivered' : 'pending' }}">{{ ucfirst($product->status) }}</span></td>
                    <td class="admin-table-actions">
                        <a href="{{ route('admin.products.edit', $product) }}">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">
                    <div class="admin-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/></svg>
                        <p>No products found.</p>
                    </div>
                </td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="admin-pagination">{{ $products->links() }}</div>
@endsection
