<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\Event\SpeakerService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class AssignSpeakerToEvent extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected SpeakerService $speakerService){}
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Event $event)
    {
        $this->authorize('manageSpeakers', $event);

        $validated = $request->validate([
            'speaker_ids' => ['array'],
            'speaker_ids.*' => ['integer', 'exists:speakers,id'],
        ]);

        $event->speakers()->sync($validated['speaker_ids'] ?? []);

        return back()
            ->with([
            'type' => 'success',
            'message' => 'Speakers assigned successfully.'
            ]);
        }
}
