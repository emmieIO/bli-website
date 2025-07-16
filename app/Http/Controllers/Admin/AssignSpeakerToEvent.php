<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\Event\SpeakerService;

use Illuminate\Http\Request;

class AssignSpeakerToEvent extends Controller
{
    public function __construct(protected SpeakerService $speakerService){}
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Event $event)
    {
        $event->speakers()->sync($request->speaker_ids);
        return redirect()
            ->route("admin.events.show", $event)
            ->with([
            'type' => 'success',
            'message' => 'Speakers assigned successfully.'
            ]);
        }
}
