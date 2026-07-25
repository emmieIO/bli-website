<?php

namespace App\Services\Event;

use App\Enums\EventRegistrationStatus;
use App\Models\Event;
use App\Notifications\UpcomingEventReminder;
use Illuminate\Support\Facades\Notification;

class EventReminderService
{
    /**
     * Queue one reminder for each registration that is still confirmed.
     *
     * @return array{accounts: int, guests: int, total: int}
     */
    public function sendToRegisteredAttendees(Event $event): array
    {
        $eligibleStatuses = EventRegistrationStatus::reminderEligibleValues();

        $accounts = $event->attendees()
            ->wherePivotIn('status', $eligibleStatuses)
            ->get();
        $guests = $event->guestAttendees()
            ->whereIn('status', $eligibleStatuses)
            ->get();

        Notification::send($accounts, new UpcomingEventReminder($event));
        Notification::send($guests, new UpcomingEventReminder($event));

        return [
            'accounts' => $accounts->count(),
            'guests' => $guests->count(),
            'total' => $accounts->count() + $guests->count(),
        ];
    }
}
