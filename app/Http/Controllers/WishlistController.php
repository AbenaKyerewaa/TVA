<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    /**
     * Get or create the active wishlist for the current user or guest session.
     */
    protected function getOrCreateWishlist(Request $request): Wishlist
    {
        if (Auth::check()) {
            $wishlist = Wishlist::firstOrCreate(['user_id' => Auth::id()]);

            // Migrate guest session wishlist items to authenticated user wishlist
            $wishlistId = $request->session()->get('wishlist_id');
            $guestWishlist = $wishlistId ? Wishlist::where('id', $wishlistId)->whereNull('user_id')->first() : null;
            if (! $guestWishlist) {
                $sessionId = $request->session()->getId();
                $guestWishlist = Wishlist::where('session_id', $sessionId)->whereNull('user_id')->first();
            }

            if ($guestWishlist && $guestWishlist->id !== $wishlist->id) {
                foreach ($guestWishlist->items as $gItem) {
                    WishlistItem::firstOrCreate([
                        'wishlist_id' => $wishlist->id,
                        'product_id' => $gItem->product_id,
                    ]);
                }
                $guestWishlist->delete();
                $request->session()->forget('wishlist_id');
            }

            return $wishlist;
        }

        $wishlistId = $request->session()->get('wishlist_id');
        if ($wishlistId) {
            $wishlist = Wishlist::where('id', $wishlistId)->whereNull('user_id')->first();
            if ($wishlist) {
                return $wishlist;
            }
        }

        $sessionId = $request->session()->getId();
        $wishlist = Wishlist::where('session_id', $sessionId)->whereNull('user_id')->first();
        if (! $wishlist) {
            $wishlist = Wishlist::create([
                'session_id' => $sessionId,
                'user_id' => null,
            ]);
        }

        $request->session()->put('wishlist_id', $wishlist->id);

        return $wishlist;
    }

    /**
     * Display the patron's saved wishlist pieces.
     */
    public function index(Request $request): View
    {
        $wishlist = $this->getOrCreateWishlist($request);
        $items = $wishlist->items()->with(['product.category', 'product.primaryImage'])->latest()->get();

        return view('storefront.wishlist', compact('items'));
    }

    /**
     * Toggle product in or out of patron wishlist.
     */
    public function toggle(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $wishlist = $this->getOrCreateWishlist($request);

        $item = $wishlist->items()->where('product_id', $validated['product_id'])->first();

        if ($item) {
            $item->delete();
            $added = false;
            $message = 'Piece removed from your wishlist.';
        } else {
            $wishlist->items()->create(['product_id' => $validated['product_id']]);
            $added = true;
            $message = 'Piece preserved in your wishlist.';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'added' => $added,
                'message' => $message,
                'count' => $wishlist->items()->count(),
            ]);
        }

        return back()->with('status', $message);
    }

    /**
     * Move an item from the wishlist directly into the shopping bag.
     */
    public function moveToCart(Request $request, WishlistItem $item): RedirectResponse
    {
        $wishlist = $this->getOrCreateWishlist($request);

        if ($item->wishlist_id !== $wishlist->id) {
            abort(403);
        }

        try {
            $this->cartService->addItem($item->product, null, 1);
            $item->delete();

            return redirect()->route('cart.index')->with('status', "{$item->product->name} moved to your shopping bag.");
        } catch (\Exception $e) {
            return back()->withErrors(['wishlist' => $e->getMessage()]);
        }
    }

    /**
     * Remove an item from the wishlist.
     */
    public function destroy(Request $request, WishlistItem $item): RedirectResponse
    {
        $wishlist = $this->getOrCreateWishlist($request);

        if ($item->wishlist_id !== $wishlist->id) {
            abort(403);
        }

        $item->delete();

        return back()->with('status', 'Piece removed from your wishlist.');
    }
}
