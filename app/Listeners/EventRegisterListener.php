<?php

namespace App\Listeners;

use App\Events\EventRegisterEvent;
use App\Notifications\EventRegisteredNotification;
use Illuminate\Support\Facades\Notification;

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
        Notification::send(
            $event->user,
            new EventRegisteredNotification($event->event, $event->registrationContext)
        );

        logger()->info('EventRegisterEvent received', ['event' => $event]);
    }
}
