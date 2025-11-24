<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        try {
            // Check if already subscribed
            $existing = Newsletter::where('email', $validated['email'])->first();

            if ($existing) {
                if ($existing->is_active) {
                    return back()->with([
                        'type' => 'info',
                        'message' => 'You are already subscribed to our newsletter!',
                    ]);
                } else {
                    // Reactivate subscription
                    $existing->update([
                        'is_active' => true,
                        'subscribed_at' => now(),
                        'unsubscribed_at' => null,
                    ]);

                    return back()->with([
                        'type' => 'success',
                        'message' => 'Welcome back! Your newsletter subscription has been reactivated.',
                    ]);
                }
            }

            // Create new subscription
            Newsletter::create([
                'email' => $validated['email'],
                'is_active' => true,
                'subscribed_at' => now(),
            ]);

            Log::info('Newsletter subscription', [
                'email' => $validated['email'],
            ]);

            return back()->with([
                'type' => 'success',
                'message' => 'Thank you for subscribing! You will now receive our latest updates.',
            ]);
        } catch (\Exception $e) {
            Log::error('Newsletter subscription failed', [
                'error' => $e->getMessage(),
                'email' => $validated['email'],
            ]);

            return back()->with([
                'type' => 'error',
                'message' => 'Sorry, there was an error processing your subscription. Please try again later.',
            ])->withInput();
        }
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $subscription = Newsletter::where('email', $validated['email'])
            ->where('is_active', true)
            ->first();

        if (!$subscription) {
            return back()->with([
                'type' => 'error',
                'message' => 'Email address not found in our newsletter list.',
            ]);
        }

        $subscription->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);

        return back()->with([
            'type' => 'success',
            'message' => 'You have been successfully unsubscribed from our newsletter.',
        ]);
    }
}
