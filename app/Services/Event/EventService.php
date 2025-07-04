<?php

namespace App\Services\Event;

use App\Models\Programme;

class EventService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAllUpcomingEvents(){

    }

    public function registerForEvent(int $eventId): bool
    {
        $userId = auth()->id();
        $event = Programme::findOrFail($eventId);

        return (bool) $event->attendees()->syncWithoutDetaching([$userId]);
    }

    public function getEventsImAttending(){
    $user = auth()->user();
    if ($user) {
        return $user->load('events')->events;
    }
    return collect([]);
    }

    public function revokeRsvp(string $slug): bool
    {
        $event = Programme::findBySlug($slug)->firstOrFail();
        $userId = auth()->id();

        return (bool) $event->attendees()->detach($userId);
    }
}
