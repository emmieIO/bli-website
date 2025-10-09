<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSpeakerRequest;
use App\Services\Event\SpeakerService;
use Illuminate\Http\Request;

class SpeakerUserController extends Controller
{
    public function __construct(protected SpeakerService $speakerService){}
    public function index()
    {
        return view('speakers.become-a-speaker');
    }

    public function store(CreateSpeakerRequest $request)
    {
        $validated = $request->validated();
        $photo = $validated['speakerInfo']['photo'];
        $speaker = $this->speakerService->createSpeaker($validated, $photo);
        if ($speaker) {
            return back()->with([
                "type" => "success",
                "message" => "Thank you for registering as a speaker! Your application has been submitted successfully."
            ]);
        }
        return back()->with([
            "type" => "error",
            "message" => "An error occured, Try again later"
        ]);
    }
}
