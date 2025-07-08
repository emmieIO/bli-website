<?php

namespace App\Services\Event;

use App\Events\Events\EventCreated;
use App\Http\Requests\CreateEventRequest;
use App\Models\Event;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventService {
    /**
    * Create a new class instance.
    */

    public function __construct() {
        //
    }

    public function getAllUpcomingEvents() {

    }

    public function getEventsCreatedByUser() {
        $user = Auth::user();
        if ( $user ) {
            return $user->eventsCreated()->get();
        }
        return collect( [] );
    }

    public function registerForEvent( int $eventId ): bool {
        $userId = auth()->id();
        $event = Event::findOrFail( $eventId );

        return ( bool ) $event->attendees()->syncWithoutDetaching( [ $userId ] );
    }

    public function getEventsImAttending() {
        $user = auth()->user();
        if ( $user ) {
            return $user->load( 'events' )->events;
        }
        return collect( [] );
    }

    public function revokeRsvp( string $slug ): bool {
        $event = Event::findBySlug( $slug )->firstOrFail();
        $userId = auth()->id();

        return ( bool ) $event->attendees()->detach( $userId );
    }

    public function createEvent( CreateEventRequest $request ) {
        try {
            DB::beginTransaction();
            $filepath = null;
            $user = Auth::user();
            $validated = $request->validated();
            $validated[ 'slug' ] = $this->generateUniqueSlug( $validated[ 'title' ] );

            if ( $request->hasFile( 'program_cover' ) ) {
                $file = $request->file( 'program_cover' );
                $filepath = $file->store( 'program_covers/' . now()->format( 'Y/m' ), 'public' );
                $validated[ 'program_cover' ] = $filepath;
            }

            $event = $user->eventsCreated()->create( $validated );
            DB::commit();
            event( new EventCreated( $event ) );

            return $event;

        } catch ( \Exception $e ) {
            DB::rollback();
            if ( !empty( $filepath ) && Storage::disk( 'public' )->exists( $filepath ) ) {
                Storage::disk( 'public' )->delete( $filepath );
            }
            Log::error( 'Event creation failed: ' . $e->getMessage() );
            return null;
        }
    }

    protected function generateUniqueSlug( $title ) {
        $slug = Str::slug( $title );
        $count = Event::where( 'slug', 'LIKE', "{$slug}%" )->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
