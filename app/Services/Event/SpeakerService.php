<?php

namespace App\Services\Event;

use App\Http\Requests\CreateSpeakerRequest;
use App\Http\Requests\UpdateSpeakerRequest;
use App\Models\Speaker;
use App\Models\SpeakerInvite;
use App\Services\Misc;
use App\Traits\HasFileUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpeakerService
{
    use HasFileUpload;
    /**
     * Create a new class instance.
     */
    public function __construct(protected Misc $miscService)
    {
        //
    }

    public function fetchSpeakers(){
        $speakers = Speaker::orderBy('name', 'asc')->paginate(10);

        return $speakers;
    }

    public function findOneSpeaker($id){
        return Speaker::findOrFail($id);
    }

    public function createSpeaker(CreateSpeakerRequest $request){
        DB::beginTransaction();
        $file_path = null;
        try {
            $user = auth()->user();
            $validated = $request->validated();
            $file_path = $this->uploadfile($request, 'photo', "speakers_dp");
            $validated['photo'] = $file_path;
            $speaker = $user->speakers()->create($validated);
            DB::commit();
            return $speaker;
        } catch (\Exception $e) {
            DB::rollBack();
            if ($file_path) {
                $this->deleteFile($file_path);
            }
            Log::error('Speaker creation failed: ' . $e->getMessage());
            return null;
        }
    }

    public function updateSpeaker(UpdateSpeakerRequest $request, Speaker $speaker)
    {
        try {
            DB::beginTransaction();
            $file_path = $speaker->photo;
            $validated = $request->validated();

            if($request->hasFile('photo')){
                $this->deleteFile($file_path);
                $file_path = $this->uploadfile($request, 'photo', 'speakers_dp');
                $validated['photo'] = $file_path;
            }else{
                $validated['photo'] = $file_path;
            }
            $speaker->update($validated);
            DB::commit();
            return $speaker->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->deleteFile($file_path);
            Log::error( 'Speaker update failed: ' . $e->getMessage() );
            return null;
        }
    }

    public function deleteSpeaker(Speaker $speaker)
    {
        $photo_path = $speaker->photo;
        if($speaker->delete()){
            $this->deleteFile($photo_path);
            return true;
        }
        return false;
    }

    public function getSpeakerInvites(int $perPage = 10)
    {
        // $user = auth()->user()->speaker->id;
        // return SpeakerInvite::where('speaker_id', '')->paginate($perPage);
        return true;
    }

}
