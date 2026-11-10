<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show customer profile and preferences.
     */
    public function show(Request $request): View
    {
        $user = $request->user()->load(['customerProfile', 'addresses', 'orders']);

        return view('customer.profile', compact('user'));
    }

    /**
     * Update customer profile and preferences.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:20'],
            'anniversary_date' => ['nullable', 'date'],
            'preferred_metal' => ['nullable', 'string', 'max:50'],
            'ring_size' => ['nullable', 'string', 'max:10'],
        ]);

        $user = $request->user();
        if (isset($validated['phone'])) {
            $user->update(['phone' => $validated['phone']]);
        }

        $user->customerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'anniversary_date' => $validated['anniversary_date'] ?? null,
                'preferences' => [
                    'preferred_metal' => $validated['preferred_metal'] ?? null,
                    'ring_size' => $validated['ring_size'] ?? null,
                ],
            ]
        );

        return back()->with('status', 'Client preferences updated successfully.');
    }
}
