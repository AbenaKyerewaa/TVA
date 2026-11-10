<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get or create the active cart for the customer or guest.
     */
    public function getCart(): Cart
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            // Migrate session cart if present
            $cartId = Session::get('cart_id');
            $sessionCart = $cartId ? Cart::where('id', $cartId)->whereNull('user_id')->first() : null;
            if (! $sessionCart) {
                $sessionId = Session::getId();
                $sessionCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
            }

            if ($sessionCart && $sessionCart->id !== $cart->id) {
                foreach ($sessionCart->items as $sessionItem) {
                    $existingItem = $cart->items()
                        ->where('product_id', $sessionItem->product_id)
                        ->where('product_variant_id', $sessionItem->product_variant_id)
                        ->first();

                    if ($existingItem) {
                        $existingItem->quantity += $sessionItem->quantity;
                        $existingItem->total_price = $existingItem->quantity * $existingItem->unit_price;
                        $existingItem->save();
                    } else {
                        $sessionItem->cart_id = $cart->id;
                        $sessionItem->save();
                    }
                }
                $sessionCart->delete();
                Session::forget('cart_id');
            }

            return $cart->load(['items.product.primaryImage', 'items.variant']);
        }

        $cartId = Session::get('cart_id');
        if ($cartId) {
            $cart = Cart::where('id', $cartId)->whereNull('user_id')->first();
            if ($cart) {
                return $cart->load(['items.product.primaryImage', 'items.variant']);
            }
        }

        $sessionId = Session::getId();
        $cart = Cart::firstOrCreate(
            ['session_id' => $sessionId, 'user_id' => null],
            ['expires_at' => now()->addDays(14)]
        );
        Session::put('cart_id', $cart->id);

        return $cart->load(['items.product.primaryImage', 'items.variant']);
    }

    /**
     * Add a product and optional variant to the cart.
     *
     * @throws \Exception
     */
    public function addItem(Product $product, ?ProductVariant $variant = null, int $quantity = 1): CartItem
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('Quantity must be at least 1.');
        }

        // Validate stock availability
        $availableStock = $variant ? $variant->stock_quantity : $product->stock_quantity;
        if ($availableStock < $quantity) {
            throw new \RuntimeException("Only {$availableStock} pieces currently available in the vault.");
        }

        $cart = $this->getCart();
        $unitPrice = $variant ? (float) $variant->effective_price : (float) $product->price;

        $existingItem = $cart->items()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variant?->id)
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $quantity;
            if ($newQuantity > $availableStock) {
                throw new \RuntimeException("Cannot add more pieces. Vault limit is {$availableStock}.");
            }

            $existingItem->quantity = $newQuantity;
            $existingItem->total_price = $newQuantity * $unitPrice;
            $existingItem->save();

            return $existingItem;
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $quantity * $unitPrice,
        ]);
    }

    /**
     * Update quantity of an item in the cart.
     *
     * @throws \Exception
     */
    public function updateQuantity(int $cartItemId, int $quantity): CartItem
    {
        $cart = $this->getCart();
        $item = $cart->items()->where('id', $cartItemId)->firstOrFail();

        if ($quantity <= 0) {
            $item->delete();

            return $item;
        }

        $availableStock = $item->variant ? $item->variant->stock_quantity : $item->product->stock_quantity;
        if ($quantity > $availableStock) {
            throw new \RuntimeException("Vault limit exceeded. Only {$availableStock} pieces available.");
        }

        $item->quantity = $quantity;
        $item->total_price = $quantity * (float) $item->unit_price;
        $item->save();

        return $item;
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem(int $cartItemId): void
    {
        $cart = $this->getCart();
        $cart->items()->where('id', $cartItemId)->delete();
    }

    /**
     * Empty the entire cart.
     */
    public function clear(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        Session::forget('applied_coupon_code');
        Session::forget('selected_shipping_method_id');
    }

    /**
     * Apply a coupon code to the session.
     *
     * @throws \Exception
     */
    public function applyCoupon(string $code): Coupon
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if (! $coupon) {
            throw new \RuntimeException('The promotional invitation code entered is invalid.');
        }

        $subtotal = $this->getSubtotal();
        if (! $coupon->isValidForAmount($subtotal)) {
            throw new \RuntimeException('This coupon code is expired, fully claimed, or does not meet minimum order requirements.');
        }

        Session::put('applied_coupon_code', $coupon->code);

        return $coupon;
    }

    /**
     * Remove the active coupon.
     */
    public function removeCoupon(): void
    {
        Session::forget('applied_coupon_code');
    }

    /**
     * Get the active applied coupon model if valid.
     */
    public function getAppliedCoupon(): ?Coupon
    {
        $code = Session::get('applied_coupon_code');
        if (! $code) {
            return null;
        }

        $coupon = Coupon::where('code', $code)->first();
        if ($coupon && $coupon->isValidForAmount($this->getSubtotal())) {
            return $coupon;
        }

        Session::forget('applied_coupon_code');

        return null;
    }

    /**
     * Calculate current subtotal.
     */
    public function getSubtotal(): float
    {
        $cart = $this->getCart();

        return (float) $cart->items->sum('total_price');
    }

    /**
     * Calculate current discount amount.
     */
    public function getDiscountAmount(): float
    {
        $coupon = $this->getAppliedCoupon();
        if (! $coupon) {
            return 0.00;
        }

        return $coupon->calculateDiscount($this->getSubtotal());
    }

    /**
     * Get shipping fee based on selected method or default complimentary.
     */
    public function getShippingFee(?int $methodId = null): float
    {
        $methodId = $methodId ?? Session::get('selected_shipping_method_id');

        if ($methodId) {
            $method = ShippingMethod::find($methodId);
            if ($method) {
                return (float) $method->fee;
            }
        }

        $defaultMethod = ShippingMethod::where('is_active', true)->orderBy('fee')->first();

        return $defaultMethod ? (float) $defaultMethod->fee : 0.00;
    }

    /**
     * Calculate estimated tax (e.g. 0% for tax-free luxury export or standard rate).
     */
    public function getTaxAmount(): float
    {
        return 0.00; // Customizable by destination
    }

    /**
     * Calculate final order total.
     */
    public function getTotal(?int $shippingMethodId = null): float
    {
        $total = ($this->getSubtotal() - $this->getDiscountAmount()) + $this->getShippingFee($shippingMethodId) + $this->getTaxAmount();

        return max(0.00, round($total, 2));
    }

    /**
     * Count total items in the cart.
     */
    public function getItemCount(): int
    {
        $cart = $this->getCart();

        return (int) $cart->items->sum('quantity');
    }
}
