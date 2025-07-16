<?php

namespace App\Listeners;

use App\Events\EventRegisterEvent;
use App\Notifications\EventRegisteredNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
        // Assuming $event->user is the notifiable entity and $event->notification is the notification instance
        Notification::send($event->user, new EventRegisteredNotification($event->event));
        logger()->info('EventRegisterEvent received', ['event' => $event]);
    }
}
