<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpeakerApplicationRequest;
use App\Models\Event;
use App\Services\Speakers\SpeakerApplicationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class SpeakerApplicationController extends Controller
{
    use AuthorizesRequests;
    public function __construct(public SpeakerApplicationService $service)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function apply(Event $event)
    {
        $this->authorize('applyToSpeak', $event);
        $application = $this->service->getExistingApplication($event);
        return view('speakers.apply', compact("event", "application"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SpeakerApplicationRequest $request, Event $event)
    {
        $this->authorize('applyToSpeak', $event);
        if ($this->service->apply($request, $event)) {
            return redirect()->back()->with(
                [
                    "type" => 'success',
                    "message" => 'Application submitted successfully.'
                ]
            );
        } else {
            return redirect()->back()->with([
                "type" => 'error',
                "message" => 'Failed to submit application.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
