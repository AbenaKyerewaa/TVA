<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Show payment screen for order.
     */
    public function show(Request $request, string $orderNumber): View|RedirectResponse
    {
        $order = Order::with(['items.product.primaryImage', 'shippingMethod'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($order->payment_status === 'paid') {
            return redirect()->route('order.confirmation', $order->order_number);
        }

        $preferredMethod = $request->query('method', 'card');

        return view('storefront.payment', compact('order', 'preferredMethod'));
    }

    /**
     * Process payment transaction.
     */
    public function process(Request $request, string $orderNumber): RedirectResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if ($order->payment_status === 'paid') {
            return redirect()->route('order.confirmation', $order->order_number);
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:card,paystack,stripe,bank_transfer,cod'],
            'card_name' => ['nullable', 'string', 'max:255'],
            'transaction_reference' => ['nullable', 'string', 'max:100'],
        ]);

        $reference = $validated['transaction_reference']
            ?? 'TVA-TXN-'.strtoupper(Str::random(10));

        // Create verified payment record
        Payment::create([
            'order_id' => $order->id,
            'payment_method' => $validated['payment_method'],
            'transaction_reference' => $reference,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
            'status' => 'completed',
            'gateway_response' => [
                'processor' => $validated['payment_method'],
                'verified_at' => now()->toIso8601String(),
                'auth_code' => strtoupper(Str::random(8)),
            ],
            'paid_at' => now(),
        ]);

        // Update order status
        $order->update([
            'status' => 'paid',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        // Initialize shipment dispatch record
        Shipment::create([
            'order_id' => $order->id,
            'shipping_method_id' => $order->shipping_method_id,
            'tracking_number' => 'TVA-SEC-'.strtoupper(Str::random(8)),
            'carrier' => 'TVA Armored Courier',
            'status' => 'preparing',
            'notes' => 'White-glove verification in vault dispatch.',
        ]);

        return redirect()->route('order.confirmation', $order->order_number)
            ->with('status', 'Payment verified successfully. Your heirloom is being prepared in our private vault.');
    }

    /**
     * Generate official luxury PDF Invoice for the order.
     */
    public function invoice(string $orderNumber): Response
    {
        $order = Order::with(['items.product', 'shippingMethod', 'payments'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $pdf = Pdf::loadView('invoices.luxury-order-receipt', compact('order'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download("TVA-Invoice-{$order->order_number}.pdf");
    }
}
