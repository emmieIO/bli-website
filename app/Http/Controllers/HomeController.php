<?php

namespace App\Http\Controllers;

use App\Services\Event\EventService;
use App\Services\MiscService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(

        public EventService $eventService,
        public MiscService $miscService
    ){}
    public function index(){
        $events = $this->eventService->fetchFeaturedEvents();
        $categories = $this->miscService->fetchFiveCategories();
        return view("index", compact("events", "categories"));
    }
}
