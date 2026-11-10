<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store patron evaluation and commentary for an heirloom piece.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['required', 'string', 'max:150'],
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $user = Auth::user();

        // Check if patron already reviewed this product
        $existing = Review::where('user_id', $user->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existing) {
            return back()->withErrors(['review' => 'You have already submitted an appraisal for this creation.']);
        }

        // Verify if client purchased this piece
        $isVerified = $user->orders()
            ->whereIn('payment_status', ['paid'])
            ->whereHas('items', function ($q) use ($validated) {
                $q->where('product_id', $validated['product_id']);
            })->exists();

        Review::create([
            'product_id' => $validated['product_id'],
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'is_verified_purchase' => $isVerified,
            'is_approved' => true, // Auto-approved for luxury verified patrons
        ]);

        return back()->with('status', 'Your patron testimonial and rating have been recorded in the atelier registry.');
    }
}
