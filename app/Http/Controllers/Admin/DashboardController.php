<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the luxury atelier administrative executive dashboard.
     */
    public function index(): View
    {
        $metrics = [
            'total_sales' => (float) Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->sum('total_amount'),
            'month_sales' => (float) Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('total_amount'),
            'total_orders' => Order::count(),
            'pending_orders' => Order::whereIn('status', ['pending', 'awaiting_payment'])->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'shipped_orders' => Order::where('status', 'shipped')->count(),
            'total_products' => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'low_stock_count' => Product::where('stock_quantity', '<=', 3)->count(),
        ];

        $recentOrders = Order::with(['user', 'items.product', 'shipment'])->latest()->take(6)->get();
        $lowStockProducts = Product::with(['category', 'primaryImage'])
            ->where('stock_quantity', '<=', 3)
            ->orderBy('stock_quantity', 'asc')
            ->take(5)
            ->get();
        $recentShipments = Shipment::with(['order.user'])->latest()->take(5)->get();
        $categories = Category::withCount('products')->get();

        return view('admin.dashboard', compact(
            'metrics',
            'recentOrders',
            'lowStockProducts',
            'recentShipments',
            'categories'
        ));
    }
}
