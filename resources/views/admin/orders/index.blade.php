@extends('layouts.admin')
@section('title', 'Orders')

@section('admin-content')
<form method="GET" class="admin-search" style="max-width:300px;">
    <select name="status" onchange="this.form.requestSubmit()">
        <option value="">All statuses</option>
        @foreach (['pending_payment', 'processing', 'shipped', 'delivered', 'cancelled'] as $s)
            <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
        @endforeach
    </select>
</form>

<div class="admin-table-wrap" style="margin-top:1rem;">
    <table class="admin-table">
        <thead><tr><th>Order</th><th>Customer</th><th>Payment</th><th>Status</th><th>Total</th><th>Date</th><th></th></tr></thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user->name ?? '—' }}</td>
                    <td><span class="status-pill status-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span></td>
                    <td><span class="status-pill status-{{ $order->status }}">{{ $order->status_label }}</span></td>
                    <td>&#8377;{{ number_format($order->total, 0) }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td class="admin-table-actions"><a href="{{ route('admin.orders.show', $order) }}">Manage</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty-state">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="admin-pagination">{{ $orders->links() }}</div>
@endsection
