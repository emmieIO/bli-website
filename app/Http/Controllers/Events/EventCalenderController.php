<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\Events\EventCalendarService;
use Illuminate\Http\Request;

class EventCalenderController extends Controller
{
    public function __construct(public EventCalendarService $service){}
    public function download(Event $event){
        return $this->service->downloadEventCalendar($event);
    }
}
