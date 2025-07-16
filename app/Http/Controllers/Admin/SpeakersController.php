<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSpeakerRequest;
use App\Http\Requests\UpdateSpeakerRequest;
use App\Models\Event;
use App\Models\Speaker;
use App\Services\Event\SpeakerService;
use Illuminate\Http\Request;

class SpeakersController extends Controller
{
    public function __construct(protected SpeakerService $speakerService){}
    public function index(){
        $speakers = $this->speakerService->fetchSpeakers();
        return view("admin.speakers.index", compact('speakers'));
    }

    public function create(){
        return view("admin.speakers.create-speaker");
    }

    public function store(CreateSpeakerRequest $request){
        $speaker = $this->speakerService->createSpeaker($request);
        if($speaker){
          return back()->with([
            "type"=>"success",
            "message" => "Speaker created successfully"
          ]);
        }
         return back()->with([
            "type"=>"error",
            "message" => "An error occured, Try again later"
          ]);
    }

    public function show(Speaker $speaker){
        return view("admin.speakers.view-speaker", compact("speaker"));
    }

    public function edit(Speaker $speaker){
        return view("admin.speakers.edit-speaker", compact("speaker"));
    }

    public function update(UpdateSpeakerRequest $request, Speaker $speaker)
    {
        $speaker = $this->speakerService->updateSpeaker($request, $speaker);
        if($speaker){
            return back()->with([
            "type" => "success",
            "message" => "Speaker updated successfully"
            ]);
        }
        return back()->with([
            "type" => "error",
            "message" => "An error occurred, Try again later"
        ]);
    }

    public function destroySpeaker(Speaker $speaker){
        if($this->speakerService->deleteSpeaker($speaker)){
            return back()->with([
                "type" => "success",
                "message" => "Speaker deleted successfully"
            ]);
        }
        return back()->with([
            "type" => "error",
            "message" => "An error occurred, Try again later"
        ]);
    }

    public function showAssignSpeakersPage(Event $event)
    {
        $speakers = $this->speakerService->fetchSpeakers();
        $assignedSpeakerIds = $event->speakers()->pluck('speakers.id')->toArray();
        return view("admin.speakers.assign-speaker", compact("event", 'speakers', "assignedSpeakerIds"));
    }

}
