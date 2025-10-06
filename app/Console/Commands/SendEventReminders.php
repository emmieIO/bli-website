<?php

namespace App\Console\Commands;

use App\Mail\EventReminder;
use App\Models\Event;
use App\Notifications\UpcomingEventReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Log;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders to users about upcoming events';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Event::with('attendees')
            ->where('start_date','>', now())
            ->chunkById(20, function ($events) {
                foreach ($events as $event) {
                    $attendees = $event->attendees;
                    foreach ($attendees as $attendee) {
                        Notification::send($attendee, new UpcomingEventReminder($event));
                    }
                }
            });
    }
}
