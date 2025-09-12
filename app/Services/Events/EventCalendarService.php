<?php

namespace App\Services\Events;

use App\Models\Event as EventModel;
use Illuminate\Support\Carbon;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event as IcsEvent;
use Spatie\IcalendarGenerator\Components\Alarm;


class EventCalendarService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function downloadEventCalendar(EventModel $event){
       $start = Carbon::parse($event->start_date);
        $end   = Carbon::parse($event->end_date);

        $location    = $event->location ?? $event->physical_address ?? '';
        $description = strip_tags($event->description ?? '');
        $title       = $event->title ?? 'Event';

        // Build the event and add alerts (VALARM)
        $icsEvent = IcsEvent::create($title)
            ->uniqueIdentifier($event->slug)
            ->organizer(env('MAIL_FROM_ADDRESS', "Beacon Leadership Institute"))
            ->description($description)
            ->address($location)
            ->startsAt($start)
            ->endsAt($end)
            ->alertMinutesBefore(4320, "Reminder (3 days): {$title}")  // 3 days
            ->alertMinutesBefore(1440, "Reminder (1 day): {$title}")   // 1 day
            ->alertMinutesBefore(60,   "Reminder (1 hour): {$title}")  // 1 hour
            ->alertMinutesBefore(1,   "Event has started: {$title}"); // 1 hour

        $calendar = Calendar::create()
            ->name('Beacon Leadership Institute Events')
            ->productIdentifier('-//My App//EN')
            ->event($icsEvent);

        // Generate ICS string with proper CRLF endings
        $ics = $calendar->get();

        return $ics;

        // return response($ics, 200, [
        //     'Content-Type' => 'text/calendar; charset=utf-8',
        //     'Content-Disposition' => 'attachment; filename="' . ($event->title ?? 'event') . '.ics"',
        // ]);
    }
}
