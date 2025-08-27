<?php

namespace App\Listeners;

use App\Events\SpeakerApplicationApprovedEvent;
use App\Notifications\SpeakerApplicationApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SpeakerApplicationApprovedListener
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
    public function handle(SpeakerApplicationApprovedEvent $event): void
    {
        $speaker = $event->application->speaker;
        $application = $event->application;
        Notification::send($speaker, new SpeakerApplicationApprovedNotification($application));
    }
}
