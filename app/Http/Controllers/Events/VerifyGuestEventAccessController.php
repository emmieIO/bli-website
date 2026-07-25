<?php

namespace App\Http\Controllers\Events;

use App\Enums\EventRegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class VerifyGuestEventAccessController extends Controller
{
    public function __invoke(Request $request, string $slug): RedirectResponse
    {
        $event = Event::query()->findBySlug($slug)->firstOrFail();
        $validated = $request->validate([
            'guest_access_email' => ['required', 'email', 'max:255'],
            'guest_access_code' => ['required', 'digits:6'],
        ]);

        $guest = $event->guestAttendees()
            ->where('email', mb_strtolower(trim($validated['guest_access_email'])))
            ->where('status', EventRegistrationStatus::REGISTERED->value)
            ->first();

        if (! $guest
            || ! $guest->access_code_hash
            || ! $guest->access_code_expires_at
            || $guest->access_code_expires_at->isPast()
            || ! Hash::check($validated['guest_access_code'], $guest->access_code_hash)) {
            throw ValidationException::withMessages([
                'guest_access_code' => 'The access code is invalid or has expired. Request a new code and try again.',
            ]);
        }

        $request->session()->put("event_guest_access.{$event->id}", true);
        $guest->forceFill([
            'access_code_hash' => null,
            'access_code_expires_at' => null,
        ])->save();

        return back()->with([
            'type' => 'success',
            'message' => 'Guest access verified. Private event joining details are now available.',
        ]);
    }
}
