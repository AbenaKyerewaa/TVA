<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display directory of distinguished patrons and clientele.
     */
    public function index(Request $request): View
    {
        $query = User::with(['customerProfile', 'orders'])
            ->where('role', 'customer')
            ->withCount('orders')
            ->withSum(['orders' => function ($q) {
                $q->whereIn('payment_status', ['paid']);
            }], 'total_amount')
            ->latest();

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tier')) {
            $tier = $request->query('tier');
            $query->whereHas('customerProfile', function ($q) use ($tier) {
                $q->where('vip_tier', $tier);
            });
        }

        $customers = $query->paginate(15)->withQueryString();

        $metrics = [
            'total_customers' => User::where('role', 'customer')->count(),
            'vip_clients' => User::whereHas('customerProfile', function ($q) {
                $q->whereIn('vip_tier', ['gold', 'platinum', 'diamond']);
            })->count(),
            'total_patron_spend' => (float) Order::where('payment_status', 'paid')->sum('total_amount'),
        ];

        return view('admin.customers.index', compact('customers', 'metrics'));
    }

    /**
     * Display confidential client profile dossier and purchase history.
     */
    public function show(User $customer): View
    {
        $customer->load(['customerProfile', 'addresses', 'orders.items.product', 'wishlist.items.product']);

        $lifetimeSpend = $customer->orders()->where('payment_status', 'paid')->sum('total_amount');

        return view('admin.customers.show', compact('customer', 'lifetimeSpend'));
    }
}
