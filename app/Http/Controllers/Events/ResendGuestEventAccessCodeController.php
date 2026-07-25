<?php

namespace App\Http\Controllers\Events;

use App\Enums\EventRegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\Event\EventRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ResendGuestEventAccessCodeController extends Controller
{
    public function __invoke(
        Request $request,
        string $slug,
        EventRegistrationService $registrationService
    ): RedirectResponse {
        $event = Event::query()->findBySlug($slug)->firstOrFail();
        $validated = $request->validate([
            'guest_access_email' => ['required', 'email', 'max:255'],
        ]);
        $email = mb_strtolower(trim($validated['guest_access_email']));
        $guest = $event->guestAttendees()
            ->where('email', $email)
            ->where('status', EventRegistrationStatus::REGISTERED->value)
            ->first();

        if ($guest) {
            $registrationService->resendGuestAccessCode($event, $guest);
        }

        // Keep the response identical so this endpoint cannot reveal who registered.
        return back()->with([
            'type' => 'success',
            'message' => 'If this email is on the attendee list, a new access code has been queued.',
        ]);
    }
}
