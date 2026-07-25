<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\Event\EventReminderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class SendEventReminderController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Event $event, EventReminderService $reminderService): RedirectResponse
    {
        $this->authorize('sendUpdates', $event);

        $result = $reminderService->sendToRegisteredAttendees($event);

        if ($result['total'] === 0) {
            return back()->with([
                'type' => 'warning',
                'message' => 'No confirmed registrations were found for this event.',
            ]);
        }

        return back()->with([
            'type' => 'success',
            'message' => "Reminder queued for {$result['total']} confirmed "
                .str('attendee')->plural($result['total']).'.',
        ]);
    }
}
