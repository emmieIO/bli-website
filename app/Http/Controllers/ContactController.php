<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
            // Send email to admin
            Mail::send('emails.contact', $validated, function ($message) use ($validated) {
                $message->to(config('mail.from.address'))
                    ->subject('New Contact Form Submission from ' . $validated['name'])
                    ->replyTo($validated['email'], $validated['name']);
            });

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
