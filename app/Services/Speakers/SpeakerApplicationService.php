<?php

namespace App\Services\Speakers;

use App\Enums\ApplicationStatus;
use App\Events\SpeakerApplicationApprovedEvent;
use App\Http\Requests\SpeakerApplicationRequest;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\SpeakerApplication;
use App\Models\User;
use App\Services\Event\EventService;
use App\Traits\HasFileUpload;
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

    public function apply(SpeakerApplicationRequest $request, Event $event)
    {
        try {
            DB::transaction(function () use ($request, $event) {
                $validated = $request->validated();
                $speakerDp = null;
                /**
                 * @var User $user
                 */
                $user = auth()->user();
                $existingApplication = $this->getExistingApplication($event);

                // update user incase the need to reformat their name
                if ( $existingApplication && $existingApplication->speaker?->photo) {
                    $speaker = $existingApplication->speaker;
                    $speakerDp = $speaker?->photo;
                }

                // handle photo upload
                if ($request->hasFile('photo')) {
                    $file_path = $this->uploadfile($request, 'photo', 'speakers_dp');
                    if($file_path){
                        $validated['speakerInfo']['photo'] = $file_path;
                        if($speakerDp){
                            $this->deleteFile($speakerDp);
                        }
                    }
                } else {
                    $validated['speakerInfo']['photo'] = $speakerDp;
                }


                // add speaker to database with status of inactive
                $speaker = Speaker::updateOrCreate(["email" => $user->email], $validated['speakerInfo']);
                // create a new application
                $application = SpeakerApplication::updateOrCreate([
                    'user_id'=>$user->id,
                    'event_id' => $event->id
                ],array_merge(
                    $validated['applicationInfo'],
                    [
                        'user_id' => $user->id,
                        'event_id' => $event->id,
                        'speaker_id' => $speaker->id
                    ]
                ));
            });
            // send mail to speaker if ACID transaction is successful
            // return true for check in controller
            return true;

        } catch (\Exception $th) {
            // log exception message
            \Log::error('Speaker application failed: ' . $th->getMessage(), ['exception' => $th]);
            //return false for controller check
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

    public function fetchPendingSpeakerApplications(){
        return SpeakerApplication::with(['speaker'])->where('status', ApplicationStatus::PENDING->value)->lazy();
    }

    public function fetchApprovedSpeakerApplications(){
        return SpeakerApplication::with(['speaker'])->where('status', ApplicationStatus::APPROVED->value)->lazy();
    }

    public function approveSpeakerApplication(SpeakerApplication $application){
        try {
            // transaction block
            DB::beginTransaction();
            // update application status to approved
            $application->status = ApplicationStatus::APPROVED->value;
            $application->save();
            // change speaker status to active
            $application->speaker->update([
                "status" => 'active'
            ]);
            // add speaker to event speaker list
            $application->event->speakers()->syncWithoutDetaching([$application->speaker->id]);
            DB::commit();
            // then send mail to speaker on approval success event
            SpeakerApplicationApprovedEvent::dispatch($application);
            return $application;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Speaker application approval failed: " . $th->getMessage(), ['exception' => $th]);
            return null;
        }
    }

    public function rejectSpeakerApplication(SpeakerApplication $application, string $feedback)
    {
        try {
            DB::beginTransaction();
            if($application->status == 'approved'){
                $speaker = $application->speaker;
                $event = $application->event;

                $application->update([
                    "status" => ApplicationStatus::REJECTED->value,
                    "feedback" => $feedback
                ]);

                $event->speakers()->detach($speaker->id);
            }
            // code to reject application

            DB::commit();
            return $application;
        } catch (\Exception $th) {
            DB::rollBack();
            return null;
        }
    }
}
