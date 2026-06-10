<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\Events\EventCalendarService;
use Illuminate\Http\Request;

class EventCalenderController extends Controller
{
    public function __construct(public EventCalendarService $service){}
    public function download(Event $event)
    {
        $ics = $this->service->downloadEventCalendar($event);
        $filename = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $event->title) . '.ics';

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
