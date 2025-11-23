<?php

namespace App\Http\Controllers;

use App\Notifications\ContactFormSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    /**
     * Handle contact form submission
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            // Send notification to admin
            Notification::route('mail', config('mail.from.address'))
                ->notify(new ContactFormSubmitted(
                    $validated['name'],
                    $validated['email'],
                    $validated['message']
                ));

            // Log the contact submission
            Log::info('Contact form submitted', [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            return back()->with([
                'type' => 'success',
                'message' => 'Thank you for contacting us! We will get back to you shortly.',
            ]);
        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'data' => $validated,
            ]);

            return back()->with([
                'type' => 'error',
                'message' => 'Sorry, there was an error sending your message. Please try again later.',
            ])->withInput();
        }
    }
}
