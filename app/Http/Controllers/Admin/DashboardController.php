<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
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
            'recentOrders' => Order::with('user')->orderByDesc('created_at')->take(8)->get(),
            'lowStock' => Product::with('inventory')
                ->whereHas('inventory', fn ($q) => $q->whereRaw('(stock_quantity - reserved_quantity) <= low_stock_threshold'))
                ->take(8)->get(),
        ]);
    }
}
