@extends('layouts.admin')
@section('title', 'Order '.$order->order_number)

@section('admin-content')
<div class="admin-form-card" style="margin-bottom:1.5rem;">
    <p><strong>Customer:</strong> {{ $order->user->name ?? '—' }} ({{ $order->user->email ?? '—' }})</p>
    <p><strong>Payment:</strong> {{ ucfirst($order->payment_status) }} via {{ $order->payment_method }}</p>
    <p><strong>Placed:</strong> {{ $order->created_at->format('d M Y, g:ia') }}</p>

    <form action="{{ route('admin.orders.update', $order) }}" method="POST" style="margin-top:1.2rem; display:flex; gap:0.8rem; align-items:end;">
        @csrf @method('PUT')
        <div class="form-field" style="margin:0;">
            <label>Order Status</label>
            <select name="status">
                @foreach (['pending_payment', 'processing', 'shipped', 'delivered', 'cancelled'] as $s)
                    <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-dark">Update Status</button>
    </form>
</div>

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

<h3 style="margin-top:2rem; font-size:1rem;">Shipping Address</h3>
<p style="font-size:0.88rem; opacity:0.8; line-height:1.6;">
    {{ $order->shipping_name }}<br>
    {{ $order->shipping_line1 }}{{ $order->shipping_line2 ? ', '.$order->shipping_line2 : '' }}<br>
    {{ $order->shipping_city }}, {{ $order->shipping_state }} — {{ $order->shipping_pincode }}<br>
    Phone: {{ $order->shipping_phone }}
</p>

<a href="{{ route('admin.orders.index') }}" class="btn btn-outline" style="margin-top:1.5rem;">← Back to Orders</a>
@endsection
