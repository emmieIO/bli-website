<?php

namespace App\Services\Event;

use App\Enums\SpeakerStatus;
use App\Enums\UserRoles;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\SpeakerApplication;
use App\Models\SpeakerInvite;
use App\Models\User;
use App\Notifications\SpeakerAccountApprovedNotification;
use App\Notifications\SpeakerAccountCreatedNotification;
use App\Services\MiscService;
use App\Traits\HasFileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpeakerService
{
    use HasFileUpload;

    /**
     * Create a new class instance.
     */
    public function __construct(protected MiscService $miscService)
    {
        //
    }

    public function fetchSpeakers(string $status = 'active')
    {
        $speakers = Speaker::latest()
            ->where('status', $status)
            ->paginate(10);

        return $speakers;
    }

    public function findOneSpeaker($id)
    {
        return Speaker::findOrFail($id);
    }

    public function createSpeaker(array $validated, UploadedFile $photo): ?Speaker
    {
        $photoPath = $this->uploadFile($photo, 'speakers_dp');

        if (! $photoPath) {
            return null;
        }

        try {
            return DB::transaction(function () use ($validated, $photoPath) {
                // Identity belongs to User; speaking credentials belong to Speaker.
                $user = User::create(array_merge($validated['userInfo'], [
                    'photo' => $photoPath,
                ]));

                $createdByAdmin = $this->miscService->isAdmin();

                if ($createdByAdmin) {
                    $user->forceFill(['email_verified_at' => now()])->save();
                }

                $speaker = Speaker::create(array_merge($validated['speakerInfo'], [
                    'user_id' => $user->id,
                    'created_by' => auth()->id() ?? $user->id,
                    'photo' => $photoPath,
                    'status' => $createdByAdmin
                        ? SpeakerStatus::ACTIVE->value
                        : SpeakerStatus::PENDING->value,
                ]));

                // Public applicants receive speaker access only after an admin approves them.
                if ($createdByAdmin && ! $user->hasRole(UserRoles::SPEAKER->value)) {
                    $user->assignRole(UserRoles::SPEAKER->value);
                }

                DB::afterCommit(function () use ($user, $createdByAdmin) {
                    $user->notify(new SpeakerAccountCreatedNotification($createdByAdmin));
                });

                return $speaker;
            });
        } catch (\Throwable $e) {
            $this->deleteFile($photoPath);
            Log::error('Speaker creation failed', [
                'email' => data_get($validated, 'userInfo.email'),
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function updateSpeaker(array $validated, Speaker $speaker, ?UploadedFile $photo)
    {
        try {
            $existing_photo = $speaker->user->photo;
            $validated['userInfo']['photo'] = $existing_photo;
            DB::transaction(function () use ($speaker, $validated, $photo, $existing_photo) {
                $speaker->user->update($validated['userInfo']);
                $speaker->update($validated['speakerProfile']);

                if ($photo) {
                    $new_photo = $this->uploadfile($photo, 'speakers_dp');
                    $speaker->user->photo = $new_photo;
                    $updatedPhoto = $speaker->user->save();
                    if ($updatedPhoto) {
                        if ($existing_photo) {
                            $this->deleteFile($existing_photo);
                        }
                    }
                }
            });

            return $speaker->fresh();
        } catch (\Exception $e) {
            Log::error('Speaker update failed: '.$e->getMessage());

            return null;
        }
    }

    public function activateSpeaker(Speaker $speaker)
    {
        try {
            $user = $speaker->user;

            return DB::transaction(function () use ($speaker, $user) {
                $user->forceFill(['email_verified_at' => now()])->save();
                if (! $user->hasRole(UserRoles::SPEAKER->value)) {
                    $user->assignRole(UserRoles::SPEAKER->value);
                }
                $speaker->Fill(['status' => SpeakerStatus::ACTIVE->value])->save();
                DB::afterCommit(function () use ($user) {
                    $user->notifyNow(new SpeakerAccountApprovedNotification);
                });

                return true;
            });
        } catch (\Exception $e) {
            Log::error('Speaker Activation Failed', ['error' => $e->getMessage()]);

            return false;
        }
    }

    public function deleteSpeaker(Speaker $speaker)
    {
        $photo_path = $speaker->photo;
        $user = $speaker->user;

        if ($speaker->delete()) {
            if ($user && ! $user->speaker()->exists() && $user->hasRole(UserRoles::SPEAKER->value)) {
                $user->removeRole(UserRoles::SPEAKER->value);
            }
            $this->deleteFile($photo_path);

            return true;
        }

        return false;
    }

    public function getSpeakerInvites(int $perPage = 10)
    {
        $speaker_id = auth()->user()->speaker?->id;
        if ($speaker_id) {
            $invites = SpeakerInvite::where('speaker_id', $speaker_id)
                ->paginate($perPage);

            return $invites;
        }

        return collect([]);
    }

    public function speakerAlreadyInvited(Event $event, Speaker $speaker)
    {
        $invite = SpeakerInvite::where('speaker_id', $speaker->id)
            ->where('event_id', $event->id)->exists();

        return $invite;
    }

    public function speakerHasAplication(Event $event, Speaker $speaker)
    {
        $application = SpeakerApplication::where('speaker_id', $speaker->id)
            ->where('event_id', $event->id)->exists();

        return $application;
    }

    public function findExistingSpeakerApplication(Event $event, Speaker $speaker)
    {
        $application = SpeakerApplication::where('speaker_id', $speaker->id)
            ->where('event_id', $event->id);

        return $application->firstOrFail();
    }
}
