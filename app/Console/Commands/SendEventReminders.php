<?php

namespace App\Console\Commands;

use App\Mail\EventReminder;
use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
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

        $timeFrames = [
            '2 days' => $now->copy()->addDays(2),
            '1 hour' => $now->copy()->addHour(),
            '30 mins' => $now->copy()->addMinutes(30),
        ];
        Log::info('Preparing to send event reminders', [
            'timeFrames' => $timeFrames,
            'currentTime' => $now->toDateTimeString()
        ]);

        foreach ($timeFrames as $label => $time) {
            $events = Event::whereBetween('start_date', [
                $time->copy()->subMinutes(1),
                $time->copy()->addMinutes(1)
            ])->get();
            foreach ($events as $event) {
                foreach ($event->attendees as $attendee) {
                    if ($attendee->pivot->status == 'registered') {
                        Mail::to($attendee->email)->send(new EventReminder($event, $attendee, $label));
                    }
                }
            }
        }




        $this->info('Event Reminder Sent');
    }
}
