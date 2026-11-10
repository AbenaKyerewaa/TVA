<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display all orders with filtering and search.
     */
    public function index(Request $request): View
    {
        $query = Order::with(['items.product', 'user', 'shippingMethod', 'payments'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->query('payment_status'));
        }

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('shipping_address', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Display single order details with white-glove courier status.
     */
    public function show(Order $order): View
    {
        $order->load(['items.product.primaryImage', 'user.customerProfile', 'shippingMethod', 'payments', 'shipment']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order lifecycle status with automatic inventory reconciliation.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,awaiting_payment,paid,processing,shipped,delivered,cancelled,refunded'],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        $previousStatus = $order->status;
        $newStatus = $validated['status'];

        if ($previousStatus === $newStatus) {
            return back()->with('status', 'Order status remains unchanged.');
        }

        DB::transaction(function () use ($order, $previousStatus, $newStatus, $validated) {
            $updates = ['status' => $newStatus];

            if ($newStatus === 'paid' && ! $order->paid_at) {
                $updates['paid_at'] = now();
                $updates['payment_status'] = 'paid';
            }

            if ($newStatus === 'shipped') {
                $updates['shipped_at'] = now();
                $updates['shipping_status'] = 'shipped';

                if ($order->shipment) {
                    $order->shipment->update([
                        'status' => 'in_transit',
                        'dispatched_at' => now(),
                    ]);
                } else {
                    Shipment::create([
                        'order_id' => $order->id,
                        'shipping_method_id' => $order->shipping_method_id,
                        'tracking_number' => 'TVA-SEC-'.strtoupper(Str::random(8)),
                        'carrier' => 'TVA Armored Courier',
                        'status' => 'in_transit',
                        'dispatched_at' => now(),
                    ]);
                }
            }

            if ($newStatus === 'delivered') {
                $updates['delivered_at'] = now();
                $updates['shipping_status'] = 'delivered';

                if ($order->shipment) {
                    $order->shipment->update([
                        'status' => 'delivered',
                        'delivered_at' => now(),
                    ]);
                }
            }

            // If order cancelled or refunded from a previously active state, restore inventory
            if (in_array($newStatus, ['cancelled', 'refunded']) && ! in_array($previousStatus, ['cancelled', 'refunded'])) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $prevStock = $item->product->stock_quantity;
                        $item->product->increment('stock_quantity', $item->quantity);
                        $currentStock = $item->product->fresh()->stock_quantity;

                        InventoryLog::create([
                            'product_id' => $item->product_id,
                            'product_variant_id' => $item->product_variant_id,
                            'change_type' => 'return',
                            'quantity' => $item->quantity,
                            'previous_stock' => $prevStock,
                            'current_stock' => $currentStock,
                            'reference_type' => 'Order',
                            'reference_id' => $order->id,
                            'notes' => "Restocked upon order {$order->order_number} {$newStatus}",
                        ]);
                    }
                }
            }

            if (! empty($validated['admin_note'])) {
                $existingNote = $order->admin_notes ? $order->admin_notes."\n" : '';
                $updates['admin_notes'] = $existingNote.'['.now()->format('Y-m-d H:i').'] Status changed from '.$previousStatus.' to '.$newStatus.': '.$validated['admin_note'];
            }

            $order->update($updates);
        });

        return back()->with('status', "Order {$order->order_number} status updated to ".ucfirst($newStatus).'.');
    }

    /**
     * Append an internal atelier administrative note.
     */
    public function addNote(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'max:1000'],
        ]);

        $existing = $order->admin_notes ? $order->admin_notes."\n" : '';
        $order->update([
            'admin_notes' => $existing.'['.now()->format('Y-m-d H:i').'] '.$validated['note'],
        ]);

        return back()->with('status', 'Internal atelier note recorded.');
    }
}
