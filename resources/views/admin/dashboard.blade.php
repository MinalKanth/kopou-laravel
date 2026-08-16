@extends('layouts.admin')
@section('title', 'Dashboard')

@section('admin-content')
<div class="admin-stat-grid">
    <div class="admin-stat-card" style="--stat-tint: rgba(177,88,58,0.08); --stat-icon-bg: rgba(177,88,58,0.12); --stat-icon-color: var(--terracotta-deep);">
        <div class="admin-stat-top">
            <div class="admin-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
        </div>
        <div class="admin-stat-label">Total Revenue</div>
        <div class="admin-stat-value">&#8377;{{ number_format($stats['revenue'], 0) }}</div>
    </div>

    <div class="admin-stat-card" style="--stat-tint: rgba(179,146,78,0.1); --stat-icon-bg: rgba(179,146,78,0.16); --stat-icon-color: #8a6d1f;">
        <div class="admin-stat-top">
            <div class="admin-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
        </div>
        <div class="admin-stat-label">Total Orders</div>
        <div class="admin-stat-value">{{ $stats['orders'] }}</div>
    </div>

    <div class="admin-stat-card" style="--stat-tint: rgba(58,85,68,0.08); --stat-icon-bg: rgba(58,85,68,0.12); --stat-icon-color: var(--forest);">
        <div class="admin-stat-top">
            <div class="admin-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
            </div>
        </div>
        <div class="admin-stat-label">Products</div>
        <div class="admin-stat-value">{{ $stats['products'] }}</div>
    </div>

    <div class="admin-stat-card" style="--stat-tint: rgba(47,77,128,0.08); --stat-icon-bg: rgba(47,77,128,0.12); --stat-icon-color: #2f4d80;">
        <div class="admin-stat-top">
            <div class="admin-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
        </div>
        <div class="admin-stat-label">Customers</div>
        <div class="admin-stat-value">{{ $stats['users'] }}</div>
    </div>
</div>

<div class="admin-grid-2">
    <div>
        <div class="admin-panel">
            <div class="admin-panel-head">
                <h3>Orders — Last 7 Days</h3>
            </div>
            @if (collect($weeklyOrders)->sum('value') > 0)
                @php $maxVal = max(1, collect($weeklyOrders)->max('value')); @endphp
                <div class="admin-chart-bars">
                    @foreach ($weeklyOrders as $day)
                        <div class="admin-chart-col">
                            <span class="chart-value">{{ $day['value'] }}</span>
                            <div class="admin-chart-bar" style="height: {{ max(4, ($day['value'] / $maxVal) * 100) }}%;" title="{{ $day['value'] }} orders"></div>
                            <span class="chart-label">{{ $day['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="admin-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    <p>No orders in the last 7 days yet.</p>
                </div>
            @endif
        </div>

        <div class="admin-panel">
            <div class="admin-panel-head">
                <h3>Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="view-all">View all →</a>
            </div>
            @if ($recentOrders->isEmpty())
                <div class="admin-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/></svg>
                    <p>No orders yet.</p>
                </div>
            @else
                <table class="admin-table">
                    <thead><tr><th>Order</th><th>Customer</th><th>Status</th><th>Total</th></tr></thead>
                    <tbody>
                        @foreach ($recentOrders as $order)
                            <tr onclick="window.location='{{ route('admin.orders.show', $order) }}'" style="cursor:pointer;">
                                <td class="admin-cell-primary">{{ $order->order_number }}</td>
                                <td>{{ $order->user->name ?? '—' }}</td>
                                <td><span class="status-pill status-{{ $order->status }}">{{ $order->status_label }}</span></td>
                                <td class="admin-cell-primary">&#8377;{{ number_format($order->total, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div>
        <div class="admin-panel">
            <div class="admin-panel-head">
                <h3>Order Status</h3>
            </div>
            @if (empty($statusBreakdown))
                <div class="admin-empty" style="padding: 1.5rem;">
                    <p>No data yet.</p>
                </div>
            @else
                @php
                    $total = collect($statusBreakdown)->sum('value');
                    $gradientParts = [];
                    $cursor = 0;
                    foreach ($statusBreakdown as $row) {
                        $pct = $total > 0 ? ($row['value'] / $total) * 100 : 0;
                        $gradientParts[] = $row['color'].' '.$cursor.'% '.($cursor + $pct).'%';
                        $cursor += $pct;
                    }
                @endphp
                <div class="admin-donut-wrap">
                    <div class="admin-donut" style="background: conic-gradient({{ implode(', ', $gradientParts) }});"></div>
                    <ul class="admin-donut-legend">
                        @foreach ($statusBreakdown as $row)
                            <li>
                                <span class="swatch" style="background: {{ $row['color'] }};"></span>
                                {{ $row['label'] }}
                                <span class="count">{{ $row['value'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="admin-panel">
            <div class="admin-panel-head">
                <h3>Low Stock Alerts</h3>
                <a href="{{ route('admin.products.index') }}" class="view-all">Manage →</a>
            </div>
            @if ($lowStock->isEmpty())
                <div class="admin-empty" style="padding: 1.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <p>All stocked up. 🎉</p>
                </div>
            @else
                <div class="admin-activity">
                    @foreach ($lowStock as $product)
                        <div class="admin-activity-item">
                            <div class="admin-activity-dot">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            </div>
                            <div class="admin-activity-body">
                                <a href="{{ route('admin.products.edit', $product) }}"><strong>{{ $product->name }}</strong></a>
                                <div class="admin-activity-time">Only {{ $product->inventory->stock_quantity ?? 0 }} left in stock</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
