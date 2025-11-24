<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\UpcomingEventReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
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
        $now = Carbon::now();
        $reminderCount = 0;

        // Send reminders for events starting in approximately 24 hours (23h 55m to 24h 5m window)
        // This 10-minute window accommodates two 5-minute cron runs
        $events24h = Event::with('attendees')
            ->where('start_date', '>', $now->copy()->addHours(23)->addMinutes(55))
            ->where('start_date', '<=', $now->copy()->addHours(24)->addMinutes(5))
            ->get();

        foreach ($events24h as $event) {
            foreach ($event->attendees()->where('status', 'registered')->get() as $attendee) {
                Notification::send($attendee, new UpcomingEventReminder($event));
                $reminderCount++;
            }
        }

        // Send reminders for events starting in approximately 2 hours (1h 55m to 2h 5m window)
        // This 10-minute window accommodates two 5-minute cron runs
        $events2h = Event::with('attendees')
            ->where('start_date', '>', $now->copy()->addHours(1)->addMinutes(55))
            ->where('start_date', '<=', $now->copy()->addHours(2)->addMinutes(5))
            ->get();

        foreach ($events2h as $event) {
            foreach ($event->attendees()->where('status', 'registered')->get() as $attendee) {
                Notification::send($attendee, new UpcomingEventReminder($event));
                $reminderCount++;
            }
        }

        $this->info("Sent {$reminderCount} event reminders.");
        Log::info("Event reminders sent: {$reminderCount} notifications");

        return Command::SUCCESS;
    }
}
