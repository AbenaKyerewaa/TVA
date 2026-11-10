<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    /**
     * Display live tracking interface for patrons and guests.
     */
    public function show(Request $request): View
    {
        $trackingNumber = $request->query('tracking');
        $orderNumber = $request->query('order');
        $email = $request->query('email');

        $order = null;
        $shipment = null;

        if ($trackingNumber) {
            $shipment = Shipment::with(['order.items.product.primaryImage', 'order.shippingMethod'])
                ->where('tracking_number', trim($trackingNumber))
                ->first();

            if ($shipment) {
                $order = $shipment->order;
            }
        } elseif ($orderNumber && $email) {
            $order = Order::with(['items.product.primaryImage', 'shippingMethod', 'shipment'])
                ->where('order_number', trim($orderNumber))
                ->where('customer_email', trim($email))
                ->first();

            $shipment = $order?->shipment;
        }

        return view('storefront.order-tracking', compact('order', 'shipment', 'trackingNumber', 'orderNumber', 'email'));
    }
}
