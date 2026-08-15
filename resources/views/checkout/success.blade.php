@extends('layouts.app')

@section('title', 'Order Confirmed — KOPOU')

@section('content')
<div class="container" style="padding-block: 3rem 5rem; max-width: 720px;">
    <div class="checkout-panel success-panel">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 6 9 17l-5-5"/></svg>
        <h1 style="font-size: var(--step-2);">Order Placed!</h1>
        <p style="opacity:0.7; margin-top:0.5rem;">Order <strong>{{ $order->order_number }}</strong> is confirmed and being prepared.</p>

        <table class="order-items-table" style="text-align:left; margin-top:2rem;">
            <thead><tr><th>Item</th><th>Qty</th><th>Price</th></tr></thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}{{ $item->variant_label ? ' — '.$item->variant_label : '' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>&#8377;{{ number_format($item->line_total, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="checkout-summary-total" style="text-align:left;">
            <span>Total Paid</span><span>&#8377;{{ number_format($order->total, 0) }}</span>
        </div>

        <div style="margin-top:2rem; display:flex; gap:1rem; justify-content:center;">
            <a href="{{ route('account.orders.show', $order) }}" class="btn btn-outline">View Order</a>
            <a href="{{ route('products.index') }}" class="btn btn-dark">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection
