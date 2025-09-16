<?php

namespace App\Services\Event;

use App\Events\EventRegisterEvent;
use App\Events\Events\EventCreated;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Mail\InvitationToSpeakerMail;
use App\Models\Event;
use App\Models\SpeakerInvite;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Mail;

use Throwable;

class EventService
{
    /**
     * Create a new class instance.
     */

    public function __construct(protected SpeakerService $speakerService)
    {
        //
    }

    public function getPublishedEvents()
    {
        $events = Event::where('is_published', true)
            ->orderBy('created_at', 'asc')
            ->paginate()
            ->withQueryString();

        return $events;
    }



    public function getEventsCreatedByUser(string|null $filter = null)
    {
        $user = Auth::user();
        if ($user) {
            $query = $user->eventsCreated();

            if ($filter === 'past') {
                $query->where('start_date', '<', now());
            } elseif ($filter === 'ongoing') {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            } elseif ($filter === 'future') {
                $query->where('start_date', '>', now());
            } elseif ($filter === 'draft') {
                $query->where('is_published', '=', false);
            }

            return $query->orderBy('start_date', 'desc')->paginate()->withQueryString();
        }
        return collect([]);
    }

    public function registerForEvent(int $eventId): bool
    {
        $userId = auth()->id();
        $event = Event::findOrFail($eventId);
        $existing = $event->attendees()->where('user_id', $userId)->first();

        if (now()->greaterThan($event->start_date)) {
            Log::info("User {$userId} attempted to register for event {$eventId} after the event start date.");
            return false;
        }

        // this conditions runs if a user is not registered
        if ($existing && $event->isRegistered()) {
            Log::info("User {$userId} successfully registered for event {$eventId}.");
            return true;
        }

        // condition if event was previously canceled
        if ($existing && $event->isCanceled()) {
            if ($event->getRevokeCount() >= 4) {
                Log::warning("User {$userId} attempted to re-register for event {$eventId} but has reached the maximum revoke count ({$event->getRevokeCount()}). Registration denied.");
                return false;
            }

            $event->attendees()->updateExistingPivot($userId, [
                'status' => 'registered',
                'updated_at' => now(),
            ]);

            return true;
        }

        // conndition for fresh registration
        $event->attendees()->attach($userId, [
            'status' => 'registered',
            'revoke_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        event(new EventRegisterEvent($event, auth()->user()));

        return true;
    }

    public function getEventsImAttending()
    {
        $user = auth()->user();
        if ($user) {
            $events = $user->events()->wherePivot('status', '!=', 'cancelled')->get();
            return $events;
        }
        return collect([]);
    }

    public function revokeRsvp(string $slug): bool
    {

        $event = Event::findBySlug($slug)->firstOrFail();
        $userId = auth()->id();

        $registration = $event->attendees()->where("user_id", $userId);

        if (!$registration) {
            return false;
        }
        $event->attendees()->updateExistingPivot($userId, [
            'status' => 'cancelled',
            'revoke_count' => DB::raw('revoke_count + 1'),
            'updated_at' => now()
        ]);
        return true;
    }

    public function createEvent(CreateEventRequest $request)
    {
        try {
            DB::beginTransaction();
            $filepath = null;
            $user = Auth::user();
            $validated['is_active'] = $request->has('is_active');
            $validated['is_published'] = $request->has('is_published');
            $validated['is_allowing_application'] = $request->has('is_allowing_application');
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

    protected function removeFile(string|null $file_path, string $type = 'public')
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

    public function inviteSpeakerToEvent(Event $event, array $data): bool|string
    {
        $speaker = $this->speakerService->findOneSpeaker($data['speaker_id']);
        if (
            SpeakerInvite::where('event_id', $event->id)
                ->where('speaker_id', $speaker->id)
                ->exists()
        ) {
            return "already_invited";
        }

        try {
            $data['expires_at'] = Carbon::now()->addDay();
            DB::transaction(function () use ($data) {
                $invitation = SpeakerInvite::create($data);

                Mail::to($invitation->speaker->user->email)->send(new InvitationToSpeakerMail($invitation));
            });
            return true;
        } catch (Throwable $e) {

            Log::error('Speaker invitation failed: ', [
                'exception' => $e->getMessage(),
                // 'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
}
