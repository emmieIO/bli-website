<?php

namespace App\Services\Event;


use App\Enums\SpeakerStatus;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

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

    public function fetchSpeakers(string $status = "active")
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

    public function createSpeaker(array $validated, UploadedFile $photo)
    {
        $photoPath = null;
        try {
            $speaker = DB::transaction(function () use ($validated, $photo) {
                // we  save the speaker info
                $photoPath = $this->uploadFile($photo, "speakers_dp");
                if (!$photoPath) {
                    throw new \Exception('Photo upload failed.');
                }

                // we register the user :which definitely would need to be verified
                $user = User::updateOrCreate(array_merge($validated['userInfo'], [
                    'photo' => $photoPath,
                ]));

                if ($this->miscService->isAdmin()) {
                    $user->forceFill(['email_verified_at' => now()])->save();
                }

                $speakerData = array_merge($validated['speakerInfo'], [

                    'user_id' => $user->id,
                    'status' => $this->miscService->isAdmin() ? 'active' : 'pending',
                ]);
                $speaker = Speaker::create($speakerData);


                DB::afterCommit(function () use ($user) {
                    if ($this->miscService->isAdmin()) {
                        // $user->sendEmailVerificationNotification();
                        $user->notify(new SpeakerAccountCreatedNotification());
                    }
                    else {
                        $user->notify(new SpeakerAccountCreatedNotification(false));
                    }
                });
                return $speaker;
            });

            return $speaker;
        } catch (\Exception $e) {
            if ($photoPath) {
                $this->deleteFile($photoPath);
            }
            Log::error('Speaker creation failed: ' . $e->getMessage());
            throw $e;
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
            Log::error('Speaker update failed: ' . $e->getMessage());
            return null;
        }
    }

    public function activateSpeaker(Speaker $speaker)
    {
        try {
            $user = $speaker->user;

            return DB::transaction(function () use ($speaker, $user) {
                $user->forceFill(['email_verified_at' => now()])->save();
                $speaker->Fill(['status' => SpeakerStatus::ACTIVE->value])->save();
                DB::afterCommit(function () use ($user) {
                    $user->notifyNow(new SpeakerAccountApprovedNotification());
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
        if ($speaker->delete()) {
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
