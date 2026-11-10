<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Display patron client portal and acquisition overview.
     */
    public function dashboard(Request $request): View
    {
        $user = $request->user()->load(['customerProfile', 'addresses', 'orders.items', 'wishlist.items']);

        $recentOrders = $user->orders()->with(['items.product.primaryImage', 'shipment'])->latest()->take(5)->get();

        $metrics = [
            'total_acquisitions' => $user->orders()->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->count(),
            'lifetime_spend' => (float) $user->orders()->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->sum('total_amount'),
            'saved_vault_pieces' => $user->wishlist ? $user->wishlist->items()->count() : 0,
            'active_dispatches' => $user->orders()->whereIn('status', ['processing', 'shipped'])->count(),
        ];

        return view('dashboard', compact('user', 'recentOrders', 'metrics'));
    }

    /**
     * Display all historical orders commissioned by the patron.
     */
    public function orders(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->with(['items.product.primaryImage', 'shippingMethod', 'shipment'])
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Display individual acquisition dossier and certificates.
     */
    public function orderShow(Request $request, string $orderNumber): View
    {
        $order = $request->user()
            ->orders()
            ->with(['items.product.primaryImage', 'shippingMethod', 'payments', 'shipment'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('customer.orders.show', compact('order'));
    }
}
