<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventGuestAttendee;
use App\Services\Event\EventRegistrationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class ResendGuestEventAccessCodeController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(
        Event $event,
        EventGuestAttendee $guest,
        EventRegistrationService $registrationService
    ): RedirectResponse {
        $this->authorize('manageAttendees', $event);
        abort_unless($guest->event_id === $event->id, 404);

        if (! $registrationService->resendGuestAccessCode($event, $guest)) {
            return back()->with([
                'type' => 'error',
                'message' => 'An access code can only be sent to a confirmed guest registration.',
            ]);
        }

        return back()->with([
            'type' => 'success',
            'message' => "A new access code was queued for {$guest->email}.",
        ]);
    }
}
