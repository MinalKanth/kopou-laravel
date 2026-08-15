@extends('layouts.admin')
@section('title', 'Dashboard')

@section('admin-content')
<div class="admin-stat-grid">
    <div class="admin-stat-card"><div class="label">Products</div><div class="value">{{ $stats['products'] }}</div></div>
    <div class="admin-stat-card"><div class="label">Orders</div><div class="value">{{ $stats['orders'] }}</div></div>
    <div class="admin-stat-card"><div class="label">Processing</div><div class="value">{{ $stats['pending_orders'] }}</div></div>
    <div class="admin-stat-card"><div class="label">Users</div><div class="value">{{ $stats['users'] }}</div></div>
    <div class="admin-stat-card"><div class="label">Revenue (Paid)</div><div class="value">&#8377;{{ number_format($stats['revenue'], 0) }}</div></div>
</div>

<h3 style="margin-bottom:0.8rem;">Recent Orders</h3>
<div class="admin-table-wrap" style="margin-bottom:2.4rem;">
    <table class="admin-table">
        <thead><tr><th>Order</th><th>Customer</th><th>Status</th><th>Total</th><th>Date</th></tr></thead>
        <tbody>
            @forelse ($recentOrders as $order)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                    <td>{{ $order->user->name ?? '—' }}</td>
                    <td><span class="status-pill status-{{ $order->status }}">{{ $order->status_label }}</span></td>
                    <td>&#8377;{{ number_format($order->total, 0) }}</td>
                    <td>{{ $order->created_at->format('d M') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty-state">No orders yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<h3 style="margin-bottom:0.8rem;">Low Stock</h3>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead><tr><th>Product</th><th>Stock</th><th>Reserved</th><th>Threshold</th></tr></thead>
        <tbody>
            @forelse ($lowStock as $product)
                <tr>
                    <td><a href="{{ route('admin.products.edit', $product) }}">{{ $product->name }}</a></td>
                    <td>{{ $product->inventory->stock_quantity ?? 0 }}</td>
                    <td>{{ $product->inventory->reserved_quantity ?? 0 }}</td>
                    <td>{{ $product->inventory->low_stock_threshold ?? 0 }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty-state">Nothing low on stock. 🎉</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
