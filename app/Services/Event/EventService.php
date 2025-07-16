<?php

namespace App\Services\Event;

use App\Events\EventRegisterEvent;
use App\Events\Events\EventCreated;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventService
{
    /**
     * Create a new class instance.
     */

    public function __construct()
    {
        //
    }

    public function getAllUpcomingEvents()
    {

    }

    public function getEventsCreatedByUser()
    {
        $user = Auth::user();
        if ($user) {
            return $user->eventsCreated()->get();
        }
        return collect([]);
    }

    public function registerForEvent(int $eventId): bool
    {
        $userId = auth()->id();
        $event = Event::findOrFail($eventId);
        $attached = $event->attendees()->syncWithoutDetaching([$userId]);

        if (!empty($attached['attached'])) {
            event(new EventRegisterEvent($event, auth()->user()));
        }

        return true;
    }

    public function getEventsImAttending()
    {
        $user = auth()->user();
        if ($user) {
            return $user->load('events')->events;
        }
        return collect([]);
    }

    public function revokeRsvp(string $slug): bool
    {
        $event = Event::findBySlug($slug)->firstOrFail();
        $userId = auth()->id();

        return (bool) $event->attendees()->detach($userId);
    }

    public function createEvent(CreateEventRequest $request)
    {
        try {
            DB::beginTransaction();
            $filepath = null;
            $user = Auth::user();
            $validated['is_active'] = $request->has('is_active');
            $validated['is_published'] = $request->has('is_published');
            $validated = $request->validated();

            $validated['slug'] = $this->generateUniqueSlug($validated['title']);

            if ($request->hasFile('program_cover')) {
                $file = $request->file('program_cover');
                $filepath = $file->store('program_covers/' . now()->format('Y/m'), 'public');
                $validated['program_cover'] = $filepath;
            }

            $event = $user->eventsCreated()->create($validated);
            DB::commit();
            event(new EventCreated($event));

            return $event;

        } catch (\Exception $e) {
            DB::rollback();
            if (!empty($filepath) && Storage::disk('public')->exists($filepath)) {
                Storage::disk('public')->delete($filepath);
            }
            Log::error('Event creation failed: ' . $e->getMessage());
            return null;
        }
    }

    protected function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = Event::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function updateEvent(UpdateEventRequest $request, Event $event)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if (
                $event->creator_id !== $user->id &&
                !$user->hasAnyRole(['admin', 'super-admin'])
            ) {
                abort(403, "You do not have permission to update this event.");
            }
            $file_path = $event->program_cover;
            $validated = $request->validated();

            if ($request->hasFile('program_cover')) {
                if (!empty($file_path) && Storage::disk('public')->exists($file_path)) {
                    Storage::disk('public')->delete($file_path);
                }
                $file = $request->file('program_cover');
                $file_path = $file->store('program_covers/' . now()->format('Y/m'), 'public');
                $validated['program_cover'] = $file_path;
            } else {
                $validated['program_cover'] = $file_path;
            }

            $event->update($validated);
            DB::commit();
            return $event->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Event update failed: ' . $e->getMessage());
            return null;
        }
    }

    protected function removeFile(string $file_path, string $type = 'public')
    {
        if (!empty($file_path) && Storage::disk($type)->exists($file_path)) {
            Storage::disk($type)->delete($file_path);
        }
    }

    public function deleteEvent(Event $event)
    {
        try {
            $file_path = $event->program_cover;
            $result = DB::transaction(function () use ($event) {
                return $event->delete();
            });
            $this->removeFile($file_path);
            return $result;

        } catch (\Exception $e) {
            Log::error('Event deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteMany(array $eventIds)
    {
        try {
            // Get all events with their program_cover paths before deletion
            $events = Event::whereIn('id', $eventIds)->get(['id', 'program_cover']);

            $filePaths = $events->pluck('program_cover')->filter()->toArray();

            $result = DB::transaction(function () use ($eventIds) {
                return Event::whereIn('id', $eventIds)->delete();
            });

            // Delete all associated files after successful database deletion
            foreach ($filePaths as $filePath) {
                $this->removeFile($filePath);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Mass event deletion failed: ' . $e->getMessage());
            return false;
        }
    }
}
