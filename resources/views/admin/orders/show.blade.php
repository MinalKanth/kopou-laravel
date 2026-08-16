@extends('layouts.admin')
@section('title', 'Order '.$order->order_number)

@section('admin-content')
<div class="admin-grid-2">
    <div>
        <div class="admin-panel">
            <div class="admin-panel-head"><h3>Items</h3></div>
            <table class="admin-table">
                <thead><tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr></thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="admin-cell-primary">{{ $item->product_name }}{{ $item->variant_label ? ' — '.$item->variant_label : '' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>&#8377;{{ number_format($item->unit_price, 0) }}</td>
                            <td class="admin-cell-primary">&#8377;{{ number_format($item->line_total, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="display:flex; justify-content:flex-end; gap:2rem; padding:1rem 1.1rem 0; font-size:0.9rem;">
                <span style="opacity:0.6;">Total</span>
                <strong style="font-family:var(--font-display); font-size:1.15rem;">&#8377;{{ number_format($order->total, 0) }}</strong>
            </div>
        </div>

        <div class="admin-panel">
            <div class="admin-panel-head"><h3>Shipping Address</h3></div>
            <p style="font-size:0.88rem; opacity:0.8; line-height:1.6;">
                {{ $order->shipping_name }}<br>
                {{ $order->shipping_line1 }}{{ $order->shipping_line2 ? ', '.$order->shipping_line2 : '' }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_state }} — {{ $order->shipping_pincode }}<br>
                Phone: {{ $order->shipping_phone }}
            </p>
        </div>
    </div>

    <div>
        <div class="admin-panel">
            <div class="admin-panel-head"><h3>Order Info</h3></div>
            <p style="font-size:0.85rem; line-height:2;">
                <strong>Customer</strong><br>{{ $order->user->name ?? '—' }}<br>{{ $order->user->email ?? '—' }}
            </p>
            <p style="font-size:0.85rem; line-height:2; margin-top:0.8rem;">
                <strong>Payment</strong><br>
                <span class="status-pill status-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
                via {{ $order->payment_method }}
            </p>
            <p style="font-size:0.85rem; margin-top:0.8rem; opacity:0.7;">
                Placed {{ $order->created_at->format('d M Y, g:ia') }}
            </p>
        </div>

        <div class="admin-panel">
            <div class="admin-panel-head"><h3>Update Status</h3></div>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-field">
                    <select name="status">
                        @foreach (['pending_payment', 'processing', 'shipped', 'delivered', 'cancelled'] as $s)
                            <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="admin-btn admin-btn-dark" style="width:100%; justify-content:center;">Update Status</button>
            </form>
        </div>
    </div>
</div>

<a href="{{ route('admin.orders.index') }}" class="admin-btn admin-btn-outline">← Back to Orders</a>
@endsection
