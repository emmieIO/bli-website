<?php

namespace App\Services\Speakers;

use App\Enums\ApplicationStatus;
use App\Events\SpeakerAppliedToEvent;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\SpeakerApplication;
use App\Traits\HasFileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SpeakerApplicationService
{
    use HasFileUpload;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected SpeakerTransitionService $speakerTransitionService
    )
    {
    }

    public function createSpeakerAccount(array $data){
        try {
            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function apply(array $data, Event $event, UploadedFile|null $file = null)
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

        $application = SpeakerApplication::updateOrCreate(
            ['user_id' => $user->id, 'event_id' => $event->id],
            array_merge($applicationInfo, [
                'user_id' => $user->id,
                'event_id' => $event->id,
                'speaker_id' => $speaker->id,
            ])
        );

        $this->speakerTransitionService->submitApplication($application);
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
            return $this->speakerTransitionService->approveApplication($application);
        } catch (\Throwable $th) {
            Log::error("Speaker application approval failed: " . $th->getMessage(), ['exception' => $th]);
            return null;
        }
    }

    public function rejectSpeakerApplication(SpeakerApplication $application, string $feedback, string|null $status = null)
    {
        try {
            return $this->speakerTransitionService->rejectApplication($application, $feedback);
        } catch (\Exception $th) {
            Log::error("Speaker application rejection failed: " . $th->getMessage(), ['exception' => $th]);
            return null;
        }
    }

}
