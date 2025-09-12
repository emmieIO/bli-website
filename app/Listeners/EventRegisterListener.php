<?php

namespace App\Listeners;

use App\Events\EventRegisterEvent;
use App\Notifications\EventRegisteredNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Services\Events\EventCalendarService;
class EventRegisterListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EventRegisterEvent $event): void
    {
        // Assuming $event->user is the notifiable entity and $event->notification is the notification instance
        $calendar = app( EventCalendarService::class);
        Notification::send($event->user, new EventRegisteredNotification($event->event, $calendar));
        logger()->info('EventRegisterEvent received', ['event' => $event]);
    }
}
