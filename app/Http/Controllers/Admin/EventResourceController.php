<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventResourceRequest;
use App\Models\Event;
use App\Models\EventResource;
use App\Services\Event\EventResourceService;
use Illuminate\Http\Request;

class EventResourceController extends Controller
{
    public function __construct(protected EventResourceService $eventResourceService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        return \Inertia\Inertia::render('Admin/Events/AddResource', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEventResourceRequest $request, Event $event)
    {
        $file = $request->file("file_path");
    
        $validated = (object)$request->validated();
        $resource = $this->eventResourceService->createEventResourse($validated, $event, $file);
        if($resource){
            return redirect()->route("admin.events.show", $event)->with([
                'type' => "success",
                'message' => "Resource added successfully."
            ]);
        } else {
            return back()->with([
                'type' => "error",
                'message' => "Failed to add resource."
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
    public function destroy(Event $event, EventResource $resource)
    {
        $eventResource = $this->eventResourceService->deleteEventResource($event, $resource);
        if ($eventResource) {
            return redirect()->route("admin.events.show", $event)->with([
                'type' => "success",
                'message' => "Resource deleted successfully."
            ]);
        } else {
            return back()->with([
                'type' => "error",
                'message' => "Failed to delete resource."
            ]);
        }
    }
}
