<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $metrics = [
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_revenue' => Order::whereDate('created_at', $today)->where('status', '!=', Order::STATUS_CANCELLED)->sum('total'),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'confirmed_orders' => Order::where('status', Order::STATUS_CONFIRMED)->count(),
            'preparing_orders' => Order::where('status', Order::STATUS_PREPARING)->count(),
            'ready_orders' => Order::whereIn('status', [Order::STATUS_READY_PICKUP, Order::STATUS_OUT_FOR_DELIVERY])->count(),
            'completed_orders' => Order::where('status', Order::STATUS_DELIVERED)->count(),
            'cancelled_orders' => Order::where('status', Order::STATUS_CANCELLED)->count(),
            'total_products' => Product::count(),
        ];

        $recentOrders = Order::with('items')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $activeKitchenOrders = Order::with('items')
            ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_CONFIRMED, Order::STATUS_PREPARING, Order::STATUS_READY_PICKUP, Order::STATUS_OUT_FOR_DELIVERY])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.dashboard', compact('metrics', 'recentOrders', 'activeKitchenOrders'));
    }
}
