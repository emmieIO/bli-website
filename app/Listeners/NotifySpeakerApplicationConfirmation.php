<?php

namespace App\Listeners;


use App\Events\SpeakerAppliedToEvent;
use App\Notifications\EventSpeakerApplicationConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;

class NotifySpeakerApplicationConfirmation
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
    public function handle(SpeakerAppliedToEvent $event): void
    {
        // EventSpeakerApplicationConfirmation
        $event->user->notify(new EventSpeakerApplicationConfirmation($event->event));
        logger()->info("Event speaker application event triggered", ['event' => $event->user]);

    }
}
