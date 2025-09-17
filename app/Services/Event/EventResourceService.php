<?php

namespace App\Services\Event;

use App\Http\Requests\CreateEventResourceRequest;
use App\Models\Event;
use App\Models\EventResource;
use App\Traits\HasFileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventResourceService {
    use HasFileUpload;
    /**
    * Create a new class instance.
    */

    public function __construct() {
        //
    }

    public function createEventResourse( UploadedFile  $request, Event $event ) {
        try {
            DB::beginTransaction();
            $file_path = null;
            $validated = $request->validated();
            if ( $request->hasFile( 'file_path' ) ) {
                $file_path = $this->uploadfile( $request,  "event_resources");
                $validated[ 'file_path' ] = $file_path;
            }
            $validated[ 'uploaded_by' ] = auth()->id();
            $resource = $event->resources()->create( $validated );
            DB::commit();
            return $resource;
        } catch ( \Exception $e ) {
            DB::rollBack();
            if($file_path){
                $this->deleteFile( $file_path );
            }
            Log::error( 'Error creating event resource: ' . $e->getMessage(), [ 'exception' => $e ] );
            return null;
        }
    }

    public function updateEventResource() {
    }

    public function deleteEventResource(Event $event, EventResource $resource) {
        if($resource->file_path){
            $this->deleteFile($resource->file_path);
        }
        $resource->delete();

        return true;
    }
}
