@extends('layouts.account')
@section('title', 'Order '.$order->order_number)

@section('account-content')
<h1>Order {{ $order->order_number }}</h1>
<p style="opacity:0.7; margin-bottom:1.2rem;">
    Placed {{ $order->created_at->format('d M Y, g:ia') }} &middot;
    <span class="status-pill status-{{ $order->status }}">{{ $order->status_label }}</span>
</p>

<table class="order-items-table">
    <thead><tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr></thead>
    <tbody>
        @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}{{ $item->variant_label ? ' — '.$item->variant_label : '' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>&#8377;{{ number_format($item->unit_price, 0) }}</td>
                <td>&#8377;{{ number_format($item->line_total, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="checkout-summary-total"><span>Total</span><span>&#8377;{{ number_format($order->total, 0) }}</span></div>

<h3 style="margin-top:2rem; font-size:1rem;">Shipping Address</h3>
<p style="font-size:0.88rem; opacity:0.8; line-height:1.6;">
    {{ $order->shipping_name }}<br>
    {{ $order->shipping_line1 }}{{ $order->shipping_line2 ? ', '.$order->shipping_line2 : '' }}<br>
    {{ $order->shipping_city }}, {{ $order->shipping_state }} — {{ $order->shipping_pincode }}<br>
    Phone: {{ $order->shipping_phone }}
</p>

<a href="{{ route('account.orders.index') }}" class="btn btn-outline" style="margin-top:1.5rem;">← Back to Orders</a>
@endsection
