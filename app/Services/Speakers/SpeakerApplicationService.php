<?php

namespace App\Services\Speakers;

use App\Enums\ApplicationStatus;
use App\Events\SpeakerApplicationApprovedEvent;
use App\Events\SpeakerAppliedToEvent;
use App\Http\Requests\SpeakerApplicationRequest;
use App\Models\Event;
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
    public function __construct(public EventService $service)
    {
        //
    }

public function apply(array $data, Event $event, UploadedFile $file =null)
{
    $existing = $this->getExistingApplication($event);
    if($existing->isApproved() || $existing->isPending()) {
        return true;
    }
    try {
        $user = auth()->user();
        $old_photo = $user->speaker?->photo;
        $data['speakerInfo']['photo'] = $old_photo;

        $speaker = DB::transaction(function () use ($data, $event, $user) {
            $speaker = Speaker::updateOrCreate(
                ["user_id" => $user->id],
                $data['speakerInfo']
            );
            SpeakerApplication::updateOrCreate([
                'user_id' => $user->id,
                'event_id' => $event->id,
            ], array_merge(
                $data['applicationInfo'],
                [
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'speaker_id' => $speaker->id,
                    "status" => ApplicationStatus::PENDING->value

                ]
            ));
            return $speaker;
        });
        // dd($speaker)
        if ($file) {
            $oldPhoto = $speaker->photo;
            $file_path = $this->uploadfile($file, 'speakers_dp');
            if ($file_path) {
                $speaker->photo = $file_path;
                $speaker->save();
                if ($oldPhoto) {
                    $this->deleteFile($oldPhoto);
                }
            }
        }

        event(new SpeakerAppliedToEvent($event, $user));

        return $speaker;

    } catch (\Exception $th) {
        \Log::error('Speaker application failed: ' . $th->getMessage(), ['exception' => $th]);
        return false;
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

    public function approveSpeakerApplication(SpeakerApplication $application)
    {
        try {
            // transaction block
            DB::beginTransaction();
            // update application status to approved
            $application->status = ApplicationStatus::APPROVED->value;
            $application->save();
            // change speaker status to active
            $application->speaker->update([
                "status" => 'active',
                "approved_at" => now(),
                "reviewed_at" => now(),
            ]);
            // add speaker to event speaker list
            $application->event->speakers()->syncWithoutDetaching([$application->speaker->id]);
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
