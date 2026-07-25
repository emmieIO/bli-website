<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSpeakerRequest;
use App\Services\Event\SpeakerService;
use Inertia\Inertia;

class SpeakerUserController extends Controller
{
    public function __construct(protected SpeakerService $speakerService) {}

    public function index()
    {
        return Inertia::render('Speakers/BecomeASpeaker');
    }

    public function store(CreateSpeakerRequest $request)
    {
        $validated = $request->validated();
        $photo = $validated['userInfo']['photo'];
        $speaker = $this->speakerService->createSpeaker($validated, $photo);

        if ($speaker) {
            return to_route('login')->with([
                'type' => 'success',
                'message' => 'Your speaker application has been submitted for review.',
            ]);
        }

        return back()->withInput($request->except(['password', 'password_confirmation', 'photo']))->with([
            'type' => 'error',
            'message' => 'We could not submit your speaker application. Please check the photo and try again.',
        ]);
    }
}
