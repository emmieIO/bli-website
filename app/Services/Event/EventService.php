<?php

namespace App\Services\Event;

use App\Events\EventRegisterEvent;
use App\Events\Events\EventCreated;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\SpeakerApplication;
use App\Models\SpeakerInvite;
use App\Notifications\SpeakerInvitationNotification;
use App\Services\Speakers\SpeakerApplicationService;
use App\Traits\HasFileUpload;
use DB;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

use Throwable;

class EventService
{
    use HasFileUpload;
    /**
     * Create a new class instance.
     */

    public function __construct(
        protected SpeakerService $speakerService,
        protected SpeakerApplicationService $speakerApplicationService
        ){}

    public function getPublishedEvents(null|string $q=null)
    {
        $events = Event::where('is_published', true)
            ->whereAny([
                'title',
                'mode',
                'theme',
                'physical_address'
            ],'Like', "%$q%")
            ->orderBy('created_at', 'asc')
            ->paginate()
            ->withQueryString();

        return $events;
    }

    public function fetchFeaturedEvents(){
        return Event::where('is_featured', true)
        ->where('start_date', '>', Carbon::now())
            ->latest()
            ->take(3)
            ->get();
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

        // if (now()->greaterThan($event->start_date)) {
        //     Log::info("User {$userId} attempted to register for event {$eventId} after the event start date.");
        //     return false;
        // }

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

    public function createEvent(array $validated, UploadedFile|null $program_cover = null)
    {

        try {
            DB::beginTransaction();
            $filepath = null;
            $user = Auth::user();

            $validated['slug'] = (string) Str::uuid();

            if($program_cover){
                $filepath = $this->uploadFile($program_cover, 'program_covers');
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

    public function updateEvent(array $validated, Event $event, UploadedFile|null $program_cover = null)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $file_path = $event->program_cover;

            if (
                $event->creator_id !== $user->id &&
                !$user->hasAnyRole(['admin', 'super-admin'])
            ) {
                abort(403, "You do not have permission to update this event.");
            }

            if ($program_cover) {
                $new_file_path = $this->uploadFile($program_cover, 'program_covers');
                if($new_file_path){
                    $validated['program_cover'] = $new_file_path;
                    if(!empty($file_path)){
                        $this->removeFile($file_path);
                    }
                }
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
        if ($this->speakerService->speakerAlreadyInvited($event, $speaker)) {
            return "already_invited";
        }
        if($this->speakerService->speakerHasAplication($event, $speaker)) {
            $existingApplication = $this->speakerService->findExistingSpeakerApplication($event, $speaker);
            if ($existingApplication) {
                $this->speakerApplicationService->approveSpeakerApplication($existingApplication);
                return "speaker_approved";
            }
        }

        try {
            $data['expires_at'] = Carbon::now()->addDay();
            DB::transaction(function () use ($data) {
                $invitation = SpeakerInvite::create($data);

                $invitation->speaker->user->notify(new SpeakerInvitationNotification($invitation));
            });
            return true;
        } catch (Throwable $e) {
            Log::error('Speaker invitation failed: ', [
                'exception' => $e->getMessage(),
            ]);
            return false;
        }
    }


}
