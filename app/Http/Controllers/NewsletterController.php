<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Subscribe a client to the TVA Collector's Circle.
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'source' => ['nullable', 'string', 'max:50'],
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => $validated['email']],
            [
                'is_active' => true,
                'source' => $validated['source'] ?? 'storefront',
                'subscribed_at' => now(),
            ]
        );

        return back()->with('newsletter_status', 'Welcome to the TVA Collector\'s Circle. Your invitation has been registered.');
    }
}
