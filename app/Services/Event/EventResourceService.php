<?php

namespace App\Services\Event;

use App\Http\Requests\CreateEventResourceRequest;
use App\Models\Event;
use App\Models\EventResource;
use App\Traits\HasFileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventResourceService
{
    use HasFileUpload;
    /**
     * Create a new class instance.
     */

    public function __construct()
    {
        //
    }

    public function createEventResourse(object $data, Event $event, ?UploadedFile $file = null)
    {
        try {
            $file_path = null;
            $result = DB::transaction(function () use ($file, $event, $data) {
                if($file){
                    $file_path = $this->uploadFile($file, 'event_resources');
                    $data->file_path = $file_path;
                }
                $data->uploaded_by = auth()->id();
                $resource = $event->resources()->create((array) $data);
                return $resource;
            });
            return $result;
        } catch (\Exception $e) {
            Log::error('Error creating event resource: ' . $e->getMessage(), ['exception' => $e]);
            if($file_path){
                $this->deleteFile( $file_path );
            }
            return false;
        }
    }

    public function updateEventResource()
    {
    }

    public function deleteEventResource(Event $event, EventResource $resource)
    {
        if ($resource->file_path) {
            $this->deleteFile($resource->file_path);
        }
        $resource->delete();

        return true;
    }
}
