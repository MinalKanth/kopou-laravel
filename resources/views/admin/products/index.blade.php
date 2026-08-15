@extends('layouts.admin')
@section('title', 'Products')

@section('admin-content')
<div class="admin-header" style="margin-bottom:1rem;">
    <form class="admin-search" method="GET" style="flex:1; max-width:360px;">
        <input type="text" name="q" value="{{ $q }}" placeholder="Search products…">
        <button type="submit" class="btn btn-outline">Search</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn btn-dark">+ New Product</a>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead><tr><th></th><th>Name</th><th>SKU</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td><img class="thumb" src="{{ $product->images->first()->url ?? '/images/placeholder.jpg' }}" alt=""></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->category->name ?? '—' }}</td>
                    <td>&#8377;{{ number_format($product->sale_price ?? $product->price, 0) }}</td>
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
                <tr><td colspan="8" class="empty-state">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="admin-pagination">{{ $products->links() }}</div>
@endsection
