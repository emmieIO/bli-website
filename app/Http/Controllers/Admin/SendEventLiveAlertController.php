<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EventStatus;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\Event\EventReminderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SendEventLiveAlertController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Request $request, Event $event, EventReminderService $reminderService): RedirectResponse
    {
        $this->authorize('sendUpdates', $event);

        if ($event->lifecycleStatus() !== EventStatus::LIVE) {
            return back()->with([
                'type' => 'warning',
                'message' => 'Set the event status to Live before sending a live alert.',
            ]);
        }

        if (! $event->meetingLinkFor($event->currentOrNextDay())) {
            return back()->with([
                'type' => 'warning',
                'message' => 'Add a meeting link before sending a live alert.',
            ]);
        }

        $validated = $request->validate([
            'account_ids' => ['sometimes', 'array'],
            'account_ids.*' => ['integer', 'distinct'],
            'guest_ids' => ['sometimes', 'array'],
            'guest_ids.*' => ['integer', 'distinct'],
        ]);
        $selectionProvided = $request->has('account_ids') || $request->has('guest_ids');
        $result = $reminderService->sendLiveAlertToRegisteredAttendees(
            $event,
            accountIds: $selectionProvided ? ($validated['account_ids'] ?? []) : null,
            guestIds: $selectionProvided ? ($validated['guest_ids'] ?? []) : null
        );

        if ($result['total'] === 0) {
            return back()->with([
                'type' => 'warning',
                'message' => 'No selected attendees have a confirmed registration.',
            ]);
        }

        return back()->with([
            'type' => 'success',
            'message' => "Live alert queued for {$result['total']} confirmed "
                .str('attendee')->plural($result['total']).'.',
        ]);
    }
}
