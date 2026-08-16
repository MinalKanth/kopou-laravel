<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::count(),
                'orders' => Order::count(),
                'pending_orders' => Order::where('status', 'processing')->count(),
                'users' => User::count(),
                'revenue' => (float) Order::where('payment_status', 'paid')->sum('total'),
            ],
            'recentOrders' => Order::with('user')->orderByDesc('created_at')->take(6)->get(),
            'lowStock' => Product::with('inventory')
                ->whereHas('inventory', fn ($q) => $q->whereRaw('(stock_quantity - reserved_quantity) <= low_stock_threshold'))
                ->take(5)->get(),
            'weeklyOrders' => $this->weeklyOrderCounts(),
            'statusBreakdown' => $this->statusBreakdown(),
        ]);
    }

    /** Order count per day for the last 7 days, oldest first — feeds the dashboard bar chart. */
    private function weeklyOrderCounts(): array
    {
        $days = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i));

        $counts = Order::selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::today()->subDays(6))
            ->groupBy('day')
            ->pluck('total', 'day');

        return $days->map(fn (Carbon $day) => [
            'label' => $day->format('D'),
            'value' => (int) ($counts[$day->toDateString()] ?? 0),
        ])->all();
    }

    /** Order counts grouped by status — feeds the dashboard donut chart. */
    private function statusBreakdown(): array
    {
        $statuses = ['processing', 'shipped', 'delivered', 'pending_payment', 'cancelled'];
        $colors = [
            'processing' => '#b3924e',
            'shipped' => '#2f4d80',
            'delivered' => '#3a5544',
            'pending_payment' => '#b1583a',
            'cancelled' => '#8a2f2f',
        ];

        $counts = Order::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        return collect($statuses)
            ->map(fn ($status) => [
                'label' => ucfirst(str_replace('_', ' ', $status)),
                'value' => (int) ($counts[$status] ?? 0),
                'color' => $colors[$status],
            ])
            ->filter(fn ($row) => $row['value'] > 0)
            ->values()
            ->all();
    }
}
