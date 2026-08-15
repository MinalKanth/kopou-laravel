@extends('layouts.account')
@section('title', 'My Orders')

@section('account-content')
<h1>My Orders</h1>

@if ($orders->isEmpty())
    <div class="empty-state">You haven't placed any orders yet. <a href="{{ route('products.index') }}">Start shopping →</a></div>
@else
    @foreach ($orders as $order)
        <div class="order-row">
            <div class="order-row-main">
                <strong>{{ $order->order_number }}</strong>
                <div>{{ $order->created_at->format('d M Y') }} &middot; {{ $order->items->count() }} item(s) &middot; &#8377;{{ number_format($order->total, 0) }}</div>
            </div>
            <span class="status-pill status-{{ $order->status }}">{{ $order->status_label }}</span>
            <a href="{{ route('account.orders.show', $order) }}" class="btn btn-outline" style="padding:0.5rem 1.1rem; font-size:0.8rem;">View</a>
        </div>
    @endforeach

    <div class="admin-pagination">{{ $orders->links() }}</div>
@endif
@endsection
