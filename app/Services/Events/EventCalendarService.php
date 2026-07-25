<?php

namespace App\Services\Events;

use App\Models\Event as EventModel;
use Illuminate\Support\Carbon;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event as IcsEvent;

class EventCalendarService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function downloadEventCalendar(EventModel $event)
    {
        $start = Carbon::parse($event->start_date);
        $end = $event->end_date ? Carbon::parse($event->end_date) : null;

        $location = $event->location ?? $event->physical_address ?? '';
        $description = strip_tags($event->description ?? '');
        $title = $event->title ?? 'Event';

        // Build the event and add alerts (VALARM)
        $icsEvent = IcsEvent::create($title)
            ->uniqueIdentifier($event->slug)
            ->organizer(env('MAIL_FROM_ADDRESS', 'Beacon Leadership Institute'))
            ->description($description)
            ->address($location)
            ->startsAt($start)
            ->alertMinutesBefore(4320, "Reminder (3 days): {$title}")
            ->alertMinutesBefore(1440, "Reminder (1 day): {$title}")
            ->alertMinutesBefore(60, "Reminder (1 hour): {$title}")
            ->alertMinutesBefore(1, "Event has started: {$title}");

        // An iCalendar event may omit DTEND when the organizer has not set one.
        if ($end) {
            $icsEvent->endsAt($end);
        }

        $calendar = Calendar::create()
            ->name('Beacon Leadership Institute Events')
            ->productIdentifier('-//My App//EN')
            ->event($icsEvent);

        return $calendar->get();
    }
}
