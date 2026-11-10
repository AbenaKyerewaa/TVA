<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingMethod;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    /**
     * Display the patron shopping bag.
     */
    public function index(): View
    {
        $cart = $this->cartService->getCart();
        $subtotal = $this->cartService->getSubtotal();
        $appliedCoupon = $this->cartService->getAppliedCoupon();
        $discountAmount = $this->cartService->getDiscountAmount();
        $shippingMethods = ShippingMethod::where('is_active', true)->orderBy('fee')->get();
        $shippingFee = $this->cartService->getShippingFee();
        $total = $this->cartService->getTotal();

        return view('storefront.cart', compact(
            'cart',
            'subtotal',
            'appliedCoupon',
            'discountAmount',
            'shippingMethods',
            'shippingFee',
            'total'
        ));
    }

    /**
     * Add a fine jewelry piece to the shopping bag.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::where('is_active', true)->findOrFail($validated['product_id']);
        $variant = ! empty($validated['variant_id'])
            ? ProductVariant::where('product_id', $product->id)->where('is_active', true)->find($validated['variant_id'])
            : null;

        $quantity = $validated['quantity'] ?? 1;

        try {
            $item = $this->cartService->addItem($product, $variant, $quantity);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Piece added to your shopping bag.',
                    'item_count' => $this->cartService->getItemCount(),
                    'subtotal' => '$'.number_format($this->cartService->getSubtotal(), 2),
                ]);
            }

            return redirect()->route('cart.index')->with('status', "{$product->name} added to your shopping bag.");
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->withErrors(['cart' => $e->getMessage()]);
        }
    }

    /**
     * Update quantity of an item in the bag.
     */
    public function update(Request $request, int $itemId): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        try {
            $this->cartService->updateQuantity($itemId, $validated['quantity']);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'item_count' => $this->cartService->getItemCount(),
                    'subtotal' => '$'.number_format($this->cartService->getSubtotal(), 2),
                    'total' => '$'.number_format($this->cartService->getTotal(), 2),
                ]);
            }

            return redirect()->route('cart.index')->with('status', 'Shopping bag updated.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->withErrors(['cart' => $e->getMessage()]);
        }
    }

    /**
     * Remove an item from the bag.
     */
    public function destroy(int $itemId): RedirectResponse
    {
        $this->cartService->removeItem($itemId);

        return redirect()->route('cart.index')->with('status', 'Piece removed from your shopping bag.');
    }

    /**
     * Apply promotional coupon code.
     */
    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ]);

        try {
            $coupon = $this->cartService->applyCoupon($validated['code']);

            return redirect()->back(fallback: route('cart.index'))->with('status', "Promotional invitation {$coupon->code} applied successfully.");
        } catch (\Exception $e) {
            return redirect()->back(fallback: route('cart.index'))->withErrors(['coupon' => $e->getMessage()]);
        }
    }

    /**
     * Remove applied coupon.
     */
    public function removeCoupon(): RedirectResponse
    {
        $this->cartService->removeCoupon();

        return redirect()->back(fallback: route('cart.index'))->with('status', 'Promotional code removed.');
    }

    /**
     * Returns JSON cart details for header badge & live drawer.
     */
    public function summary(): JsonResponse
    {
        return response()->json([
            'item_count' => $this->cartService->getItemCount(),
            'subtotal' => '$'.number_format($this->cartService->getSubtotal(), 2),
            'total' => '$'.number_format($this->cartService->getTotal(), 2),
        ]);
    }
}
