<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    /**
     * Display the luxury checkout stage.
     */
    public function index(): View|RedirectResponse
    {
        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Your shopping bag is currently empty.']);
        }

        // Validate stock for all items
        foreach ($cart->items as $item) {
            $available = $item->variant ? $item->variant->stock_quantity : $item->product->stock_quantity;
            if ($available < $item->quantity) {
                return redirect()->route('cart.index')->withErrors([
                    'cart' => "{$item->product->name} only has {$available} pieces remaining in the vault. Please adjust quantity.",
                ]);
            }
        }

        $user = Auth::user();
        $savedAddresses = $user ? $user->addresses()->latest()->get() : collect();
        $shippingMethods = ShippingMethod::where('is_active', true)->orderBy('fee')->get();
        $subtotal = $this->cartService->getSubtotal();
        $appliedCoupon = $this->cartService->getAppliedCoupon();
        $discountAmount = $this->cartService->getDiscountAmount();
        $shippingFee = $this->cartService->getShippingFee();
        $total = $this->cartService->getTotal();

        return view('storefront.checkout', compact(
            'cart',
            'user',
            'savedAddresses',
            'shippingMethods',
            'subtotal',
            'appliedCoupon',
            'discountAmount',
            'shippingFee',
            'total'
        ));
    }

    /**
     * Process checkout submission and initialize the order.
     */
    public function store(Request $request): RedirectResponse
    {
        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Your shopping bag is currently empty.']);
        }

        $validated = $request->validate([
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'full_name' => ['required', 'string', 'max:255'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'shipping_method_id' => ['required', 'exists:shipping_methods,id'],
            'payment_method' => ['required', 'in:card,paystack,stripe,bank_transfer,cod'],
            'customer_notes' => ['nullable', 'string', 'max:1000'],
            'save_address' => ['nullable', 'boolean'],
        ]);

        $shippingMethod = ShippingMethod::findOrFail($validated['shipping_method_id']);

        try {
            $order = DB::transaction(function () use ($cart, $validated, $shippingMethod) {
                // 1. Re-validate and reserve vault stock
                foreach ($cart->items as $item) {
                    $product = $item->product()->lockForUpdate()->first();
                    $variant = $item->variant ? $item->variant()->lockForUpdate()->first() : null;

                    $available = $variant ? $variant->stock_quantity : $product->stock_quantity;
                    if ($available < $item->quantity) {
                        throw new \RuntimeException("{$product->name} stock changed. Only {$available} pieces available.");
                    }
                }

                $subtotal = $this->cartService->getSubtotal();
                $coupon = $this->cartService->getAppliedCoupon();
                $discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0.00;
                $shippingFee = (float) $shippingMethod->fee;
                $tax = 0.00;
                $total = max(0.00, ($subtotal - $discount) + $shippingFee + $tax);

                $shippingAddress = [
                    'full_name' => $validated['full_name'],
                    'phone' => $validated['customer_phone'],
                    'address_line_1' => $validated['address_line_1'],
                    'address_line_2' => $validated['address_line_2'] ?? null,
                    'city' => $validated['city'],
                    'state' => $validated['state'] ?? null,
                    'postal_code' => $validated['postal_code'],
                    'country' => $validated['country'],
                ];

                // 2. Create Order
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => Auth::id(),
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'shipping_status' => 'pending',
                    'currency' => 'USD',
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount,
                    'coupon_code' => $coupon?->code,
                    'shipping_fee' => $shippingFee,
                    'tax_amount' => $tax,
                    'total_amount' => $total,
                    'shipping_method_id' => $shippingMethod->id,
                    'shipping_address' => $shippingAddress,
                    'billing_address' => $shippingAddress,
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'customer_notes' => $validated['customer_notes'] ?? null,
                ]);

                // 3. Create OrderItems and deduct inventory
                foreach ($cart->items as $item) {
                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'product_variant_id' => $item->product_variant_id,
                        'product_name' => $item->product->name,
                        'variant_details' => $item->variant?->name,
                        'sku' => $item->variant?->sku ?? $item->product->sku,
                        'unit_price' => $item->unit_price,
                        'quantity' => $item->quantity,
                        'total_price' => $item->total_price,
                    ]);

                    // Deduct stock
                    if ($item->variant) {
                        $prevStock = $item->variant->stock_quantity;
                        $item->variant->decrement('stock_quantity', $item->quantity);
                        $currentStock = $item->variant->fresh()->stock_quantity;
                    } else {
                        $prevStock = $item->product->stock_quantity;
                        $item->product->decrement('stock_quantity', $item->quantity);
                        $currentStock = $item->product->fresh()->stock_quantity;
                    }

                    // Log inventory change
                    InventoryLog::create([
                        'product_id' => $item->product_id,
                        'product_variant_id' => $item->product_variant_id,
                        'change_type' => 'sale',
                        'quantity' => -$item->quantity,
                        'previous_stock' => $prevStock,
                        'current_stock' => $currentStock,
                        'reference_type' => 'Order',
                        'reference_id' => $order->id,
                        'notes' => "Vault deduction for Order {$order->order_number}",
                    ]);
                }

                // 4. Update coupon usage
                if ($coupon) {
                    $coupon->increment('times_used');
                }

                // 5. Save address to address book if requested
                if (Auth::check() && ! empty($validated['save_address'])) {
                    Address::create([
                        'user_id' => Auth::id(),
                        'type' => 'shipping',
                        'full_name' => $validated['full_name'],
                        'phone' => $validated['customer_phone'],
                        'address_line_1' => $validated['address_line_1'],
                        'address_line_2' => $validated['address_line_2'] ?? null,
                        'city' => $validated['city'],
                        'state' => $validated['state'] ?? null,
                        'postal_code' => $validated['postal_code'],
                        'country' => $validated['country'],
                        'is_default' => false,
                    ]);
                }

                // 6. Clear shopping bag
                $this->cartService->clear();

                return $order;
            });

            return redirect()->route('checkout.payment', [
                'order_number' => $order->order_number,
                'method' => $validated['payment_method'],
            ]);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['checkout' => $e->getMessage()]);
        }
    }

    /**
     * Display order confirmation page.
     */
    public function confirmation(string $orderNumber): View
    {
        $order = Order::with(['items.product.primaryImage', 'shippingMethod', 'payments'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('storefront.order-confirmation', compact('order'));
    }
}
