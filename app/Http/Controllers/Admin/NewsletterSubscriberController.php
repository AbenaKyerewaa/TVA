<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterSubscriberController extends Controller
{
    /**
     * Display a listing of newsletter subscribers with search and status filtering.
     */
    public function index(Request $request): View
    {
        $query = NewsletterSubscriber::query()->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('email', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $status = $request->input('status') === 'active';
            $query->where('is_active', $status);
        }

        $subscribers = $query->paginate(20)->withQueryString();
        $totalSubscribers = NewsletterSubscriber::count();
        $activeSubscribers = NewsletterSubscriber::where('is_active', true)->count();

        return view('admin.subscribers.index', compact('subscribers', 'totalSubscribers', 'activeSubscribers'));
    }

    /**
     * Toggle the subscription status of a subscriber.
     */
    public function toggle(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->update([
            'is_active' => ! $subscriber->is_active,
        ]);

        $statusText = $subscriber->is_active ? 'reactivated' : 'unsubscribed';

        return back()->with('status', "Subscriber {$subscriber->email} has been {$statusText}.");
    }

    /**
     * Delete a subscriber from the circle.
     */
    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $email = $subscriber->email;
        $subscriber->delete();

        return back()->with('status', "Subscriber {$email} has been permanently removed.");
    }

    /**
     * Export all subscribers to CSV.
     */
    public function export(): StreamedResponse
    {
        $filename = 'tva_collector_circle_subscribers_'.date('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Email Address', 'Source Channel', 'Status', 'Subscribed At']);

            NewsletterSubscriber::orderBy('id', 'desc')->chunk(200, function ($subscribers) use ($handle) {
                foreach ($subscribers as $subscriber) {
                    fputcsv($handle, [
                        $subscriber->id,
                        $subscriber->email,
                        $subscriber->source ?? 'storefront',
                        $subscriber->is_active ? 'Active' : 'Unsubscribed',
                        $subscriber->subscribed_at?->toIso8601String() ?? $subscriber->created_at->toIso8601String(),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
