<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\Event\EventReminderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SendEventReminderController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Request $request, Event $event, EventReminderService $reminderService): RedirectResponse
    {
        $this->authorize('sendUpdates', $event);

        $validated = $request->validate([
            'account_ids' => ['sometimes', 'array'],
            'account_ids.*' => ['integer', 'distinct'],
            'guest_ids' => ['sometimes', 'array'],
            'guest_ids.*' => ['integer', 'distinct'],
        ]);
        $selectionProvided = $request->has('account_ids') || $request->has('guest_ids');

        $result = $reminderService->sendToRegisteredAttendees(
            $event,
            accountIds: $selectionProvided ? ($validated['account_ids'] ?? []) : null,
            guestIds: $selectionProvided ? ($validated['guest_ids'] ?? []) : null
        );

        if ($result['total'] === 0) {
            return back()->with([
                'type' => 'warning',
                'message' => $selectionProvided
                    ? 'None of the selected recipients has a confirmed registration.'
                    : 'No confirmed registrations were found for this event.',
            ]);
        }

        return back()->with([
            'type' => 'success',
            'message' => "Reminder queued for {$result['total']} confirmed "
                .str('attendee')->plural($result['total']).'.',
        ]);
    }
}
