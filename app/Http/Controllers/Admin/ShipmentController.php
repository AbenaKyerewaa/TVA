<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    /**
     * Display all armored courier dispatches and shipments.
     */
    public function index(Request $request): View
    {
        $query = Shipment::with(['order.items.product', 'order.user', 'shippingMethod'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                    ->orWhere('carrier', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($oq) use ($search) {
                        $oq->where('order_number', 'like', "%{$search}%")
                            ->orWhere('customer_email', 'like', "%{$search}%");
                    });
            });
        }

        $shipments = $query->paginate(15)->withQueryString();

        $metrics = [
            'all' => Shipment::count(),
            'preparing' => Shipment::where('status', 'preparing')->count(),
            'in_transit' => Shipment::where('status', 'in_transit')->count(),
            'out_for_delivery' => Shipment::where('status', 'out_for_delivery')->count(),
            'delivered' => Shipment::where('status', 'delivered')->count(),
        ];

        return view('admin.shipping.shipments-index', compact('shipments', 'metrics'));
    }

    /**
     * Update dispatch credentials, carrier, tracking number, and status.
     */
    public function update(Request $request, Shipment $shipment): RedirectResponse
    {
        $validated = $request->validate([
            'carrier' => ['required', 'string', 'max:100'],
            'tracking_number' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:preparing,in_transit,out_for_delivery,delivered'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $updates = [
            'carrier' => $validated['carrier'],
            'tracking_number' => $validated['tracking_number'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ];

        if ($validated['status'] === 'in_transit' && ! $shipment->dispatched_at) {
            $updates['dispatched_at'] = now();
        }

        if ($validated['status'] === 'delivered' && ! $shipment->delivered_at) {
            $updates['delivered_at'] = now();
        }

        $shipment->update($updates);

        // Sync order shipping status
        if ($shipment->order) {
            $orderShippingStatus = match ($validated['status']) {
                'delivered' => 'delivered',
                'in_transit', 'out_for_delivery' => 'shipped',
                default => 'processing',
            };

            $orderUpdates = ['shipping_status' => $orderShippingStatus];

            if ($validated['status'] === 'delivered') {
                $orderUpdates['status'] = 'delivered';
                $orderUpdates['delivered_at'] = now();
            } elseif ($validated['status'] === 'in_transit' && in_array($shipment->order->status, ['paid', 'processing'])) {
                $orderUpdates['status'] = 'shipped';
                $orderUpdates['shipped_at'] = now();
            }
            $shipment->order->update($orderUpdates);
        }

        return back()->with('status', "Shipment {$shipment->tracking_number} updated successfully.");
    }
}
