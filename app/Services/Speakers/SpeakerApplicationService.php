<?php

namespace App\Services\Speakers;

use App\Enums\ApplicationStatus;
use App\Enums\EntryPath;
use App\Events\SpeakerApplicationApprovedEvent;
use App\Events\SpeakerAppliedToEvent;
use App\Http\Requests\SpeakerApplicationRequest;
use App\Models\Event;
use App\Models\EventSpeaker;
use App\Models\Speaker;
use App\Models\SpeakerApplication;
use App\Models\User;
use App\Services\Event\EventService;
use App\Traits\HasFileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpeakerApplicationService
{
    use HasFileUpload;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
    }

    public function apply(array $data, Event $event, UploadedFile $file = null)
    {
        $existing = $this->getExistingApplication($event);

        // Prevent duplicate applications if already approved or pending
        if ($existing && ($existing->isApproved() || $existing->isPending())) {
            return true;
        }

        try {
            $speaker = DB::transaction(function () use ($data, $event, $file) {
                // Handle speaker info
                $speaker = $this->upsertSpeaker($data['speakerInfo']);

                // Handle application info
                $this->upsertApplication($speaker, $event, $data['applicationInfo']);

                // Handle file upload (inside transaction is safer)
                if ($file) {
                    $this->handleSpeakerPhoto($speaker, $file);
                }
                DB::afterCommit(function () use ($event) {
                    event(new SpeakerAppliedToEvent($event, auth()->user()));
                });
                return $speaker;
            });


            return $speaker;
        } catch (\Throwable $th) {
            Log::error('Speaker application failed: ' . $th->getMessage(), ['exception' => $th]);
            return false;
        }
    }

    private function upsertSpeaker(array $speakerInfo): Speaker
    {
        $user = auth()->user();
        $oldPhoto = $user->speaker?->photo;

        // Keep existing photo unless new one is uploaded later
        $speakerInfo['photo'] = $oldPhoto;

        return Speaker::updateOrCreate(
            ['user_id' => $user->id],
            $speakerInfo
        );
    }

    private function upsertApplication(Speaker $speaker, Event $event, array $applicationInfo): void
    {
        $user = auth()->user();

        SpeakerApplication::updateOrCreate(
            ['user_id' => $user->id, 'event_id' => $event->id],
            array_merge($applicationInfo, [
                'user_id' => $user->id,
                'event_id' => $event->id,
                'speaker_id' => $speaker->id,
                'status' => ApplicationStatus::PENDING->value,
            ])
        );
    }

    private function handleSpeakerPhoto(Speaker $speaker, UploadedFile $file): void
    {
        $oldPhoto = $speaker->photo;
        $filePath = $this->uploadfile($file, 'speakers_dp');

        if ($filePath) {
            $speaker->update(['photo' => $filePath]);

            if ($oldPhoto) {
                $this->deleteFile($oldPhoto);
            }
        }
    }


    public function getExistingApplication(Event $event)
    {
        $application = SpeakerApplication::with('speaker')->where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->first();
        return $application;
    }

    public function fetchPendingSpeakerApplications()
    {
        return SpeakerApplication::with(['speaker'])->where('status', ApplicationStatus::PENDING->value)->lazy();
    }

    public function fetchApprovedSpeakerApplications()
    {
        return SpeakerApplication::with(['speaker'])->where('status', ApplicationStatus::APPROVED->value)->lazy();
    }
    public function fetchRejectedSpeakerApplications()
    {
        return SpeakerApplication::with(['speaker'])->where('status', ApplicationStatus::REJECTED->value)->lazy();
    }

    public function approveSpeakerApplication(SpeakerApplication $application)
    {
        try {
            // transaction block
            DB::beginTransaction();
            // update application status to approved

            $application->update([
                'status' => ApplicationStatus::APPROVED->value,
                "approved_at" => now(),
                "reviewed_at" => now(),
            ]);

            // change speaker status to active
            $application->speaker->update([
                "status" => 'active',
            ]);
            // add speaker to event speaker list
            $application->event->speakers()->syncWithoutDetaching([
                $application->speaker->id,
            ]);
            DB::commit();
            // then send mail to speaker on approval success event
            event(new SpeakerApplicationApprovedEvent($application));

            return $application;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Speaker application approval failed: " . $th->getMessage(), ['exception' => $th]);
            return null;
        }
    }

    public function rejectSpeakerApplication(SpeakerApplication $application, string $feedback, string $status = null)
    {
        try {
            DB::beginTransaction();
            $speaker = $application->speaker;
            $event = $application->event;

            $application->update([
                "status" => ApplicationStatus::REJECTED->value,
                "feedback" => $feedback,
                'rejected_at' => now(),
                "reviewed_at" => now(),

            ]);

            $event->speakers()->detach($speaker->id);

            // code to reject application

            DB::commit();
            return $application;
        } catch (\Exception $th) {
            DB::rollBack();
            return null;
        }
    }

}
