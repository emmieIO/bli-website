<?php

namespace App\Services\Speakers;

use App\Http\Requests\SpeakerApplicationRequest;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\SpeakerApplication;
use App\Models\User;
use App\Traits\HasFileUpload;
use Illuminate\Support\Facades\DB;

class SpeakerApplicationService
{
    use HasFileUpload;
    /**
     * Create a new class instance.
     */
    public function __construct()
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
                $application = SpeakerApplication::updateOrCreate(['user_id'=>$user->id],array_merge(
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

    public function reApply(SpeakerApplicationRequest $request, Event $event)
    {
        try {
            $existingApplication = $this->getExistingApplication($event);
            DB::transaction(function () use ($existingApplication, $request, $event) {
                $user = auth()->user()->id;
                $validated = $request->validated();
                $speakerDp = $existingApplication->speaker->photo;
                if ($request->hasFile('photo')) {
                    $photoLink = $this->uploadfile($request, 'photo', 'speakers_dp');
                    $validated['speakerInfo']['photo'] = $photoLink;

                    if (!empty($speakerDp) && $this->deletefile($speakerDp)) {
                        \Log::info('Speaker photo deleted successfully for re-application.', ['speaker_id' => $existingApplication->speaker->id]);
                    }
                }
                $speaker = Speaker::updateOrCreate(
                    ['email' => $user->email],
                    $validated['speakerInfo']
                );
                $existingApplication->update(array_merge(
                    $validated['applicationInfo'],
                    [
                        'user_id' => $user->id,
                        'event_id' => $event->id,
                        'speaker_id' => $speaker->id
                    ]
                ));
            });
            return true;
        } catch (\Exception $th) {
            \Log::error('Speaker re-application failed: ' . $th->getMessage(), ['exception' => $th]);
            return false;
        }
    }
}
