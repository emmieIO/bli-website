<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\UpcomingEventReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
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
        $events24h = Event::with(['attendees' => function ($query) {
                $query->where('status', 'registered');
            }])
            ->where('start_date', '>', $now->copy()->addHours(23)->addMinutes(55))
            ->where('start_date', '<=', $now->copy()->addHours(24)->addMinutes(5))
            ->get();

        foreach ($events24h as $event) {
            $cacheKey = "event_reminder_24h_{$event->id}";

            // Check if we've already sent reminders for this event in the last 23 hours
            if (Cache::has($cacheKey)) {
                continue;
            }

            if ($event->attendees->isNotEmpty()) {
                // Queue notification for all registered attendees
                Notification::send($event->attendees, new UpcomingEventReminder($event));
                $reminderCount += $event->attendees->count();

                // Cache for 23 hours to prevent duplicate reminders
                Cache::put($cacheKey, true, now()->addHours(23));

                Log::info('24-hour event reminders queued', [
                    'event_id' => $event->id,
                    'event_title' => $event->title,
                    'attendee_count' => $event->attendees->count(),
                ]);
            }
        }

        // Send reminders for events starting in approximately 2 hours (1h 55m to 2h 5m window)
        // This 10-minute window accommodates two 5-minute cron runs
        $events2h = Event::with(['attendees' => function ($query) {
                $query->where('status', 'registered');
            }])
            ->where('start_date', '>', $now->copy()->addHours(1)->addMinutes(55))
            ->where('start_date', '<=', $now->copy()->addHours(2)->addMinutes(5))
            ->get();

        foreach ($events2h as $event) {
            $cacheKey = "event_reminder_2h_{$event->id}";

            // Check if we've already sent reminders for this event in the last 2 hours
            if (Cache::has($cacheKey)) {
                continue;
            }

            if ($event->attendees->isNotEmpty()) {
                // Queue notification for all registered attendees
                Notification::send($event->attendees, new UpcomingEventReminder($event));
                $reminderCount += $event->attendees->count();

                // Cache for 2 hours to prevent duplicate reminders
                Cache::put($cacheKey, true, now()->addHours(2));

                Log::info('2-hour event reminders queued', [
                    'event_id' => $event->id,
                    'event_title' => $event->title,
                    'attendee_count' => $event->attendees->count(),
                ]);
            }
        }

        $this->info("Queued {$reminderCount} event reminder notifications.");
        Log::info("Event reminders queued: {$reminderCount} notifications");

        return Command::SUCCESS;
    }
}
