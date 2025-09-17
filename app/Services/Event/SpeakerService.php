<?php

namespace App\Services\Event;

use App\Http\Requests\CreateSpeakerRequest;
use App\Http\Requests\UpdateSpeakerRequest;
use App\Models\Speaker;
use App\Models\SpeakerInvite;
use App\Services\Misc;
use App\Traits\HasFileUpload;
use Illuminate\Http\UploadedFile;
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

    public function fetchSpeakers()
    {
        $speakers = Speaker::latest()
            ->where('status', 'active')
            ->paginate(10);

        return $speakers;
    }

    public function findOneSpeaker($id)
    {
        return Speaker::findOrFail($id);
    }

    public function createSpeaker(array $validated, UploadedFile $photo)
    {
        DB::beginTransaction();
        $file_path = null;
        try {
            $user = auth()->user();
            $file_path = $this->uploadfile($photo, "speakers_dp");
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

    public function updateSpeaker(array $validated, Speaker $speaker, ?UploadedFile $photo)
    {
        try {
            $existing_photo = $speaker->photo;
            $validated['speakerProfile']['photo'] = $existing_photo;
            DB::transaction(function () use ($speaker, $validated, $photo, $existing_photo) {
                $speaker->user->update($validated['userInfo']);
                $speaker->update($validated['speakerProfile']);

                if ($photo) {
                    $new_photo = $this->uploadfile($photo, 'speakers_dp');
                    $speaker->photo = $new_photo;
                    $updatedPhoto = $speaker->save();
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

}
