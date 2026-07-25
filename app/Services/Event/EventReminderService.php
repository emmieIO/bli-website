<?php

namespace App\Services\Event;

use App\Enums\EventRegistrationStatus;
use App\Models\Event;
use App\Models\EventDay;
use App\Notifications\UpcomingEventReminder;
use Illuminate\Support\Facades\Notification;

class EventReminderService
{
    /**
     * Queue one reminder for each registration that is still confirmed.
     *
     * @return array{accounts: int, guests: int, total: int}
     */
    public function sendToRegisteredAttendees(
        Event $event,
        ?EventDay $eventDay = null,
        ?array $accountIds = null,
        ?array $guestIds = null
    ): array {
        $eligibleStatuses = EventRegistrationStatus::reminderEligibleValues();

        $accounts = $event->attendees()
            ->wherePivotIn('status', $eligibleStatuses)
            ->when($accountIds !== null, fn ($query) => $query->whereKey($accountIds))
            ->get();
        $guests = $event->guestAttendees()
            ->whereIn('status', $eligibleStatuses)
            ->when($guestIds !== null, fn ($query) => $query->whereKey($guestIds))
            ->get();

        Notification::send($accounts, new UpcomingEventReminder($event, $eventDay));
        Notification::send($guests, new UpcomingEventReminder($event, $eventDay));

        return [
            'accounts' => $accounts->count(),
            'guests' => $guests->count(),
            'total' => $accounts->count() + $guests->count(),
        ];
    }
}
