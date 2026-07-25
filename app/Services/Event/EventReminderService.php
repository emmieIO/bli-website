<?php

namespace App\Services\Event;

use App\Enums\EventRegistrationStatus;
use App\Models\Event;
use App\Models\EventDay;
use App\Notifications\EventLiveNotification;
use App\Notifications\UpcomingEventReminder;
use Illuminate\Support\Facades\Notification;

class EventReminderService
{
    public function __construct(
        protected EventRegistrationService $registrationService
    ) {}

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

    /**
     * Queue a live alert. Guests receive a fresh code while account attendees
     * can use the meeting link directly.
     *
     * @return array{accounts: int, guests: int, total: int}
     */
    public function sendLiveAlertToRegisteredAttendees(
        Event $event,
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

        Notification::send($accounts, new EventLiveNotification($event));
        $guests->each(function ($guest) use ($event) {
            $accessCode = $this->registrationService->createGuestAccessCode($guest);
            $guest->notify(new EventLiveNotification($event, $accessCode));
        });

        return [
            'accounts' => $accounts->count(),
            'guests' => $guests->count(),
            'total' => $accounts->count() + $guests->count(),
        ];
    }
}
