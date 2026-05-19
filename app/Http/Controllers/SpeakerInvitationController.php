<?php

namespace App\Http\Controllers;

use App\Enums\SpeakerInviteStatus;
use App\Models\SpeakerInvite;
use App\Services\Event\SpeakerService;
use App\Services\Speakers\SpeakerInvitationService;
use Illuminate\Http\Request;

class SpeakerInvitationController extends Controller
{
    public function __construct(
        public SpeakerService $speakerService,
        public SpeakerInvitationService $speakerInvitationService
    ){}

    public function index(){
        $invitations = $this->speakerService->getSpeakerInvites();
        return \Inertia\Inertia::render('MyInvitations/Index', compact('invitations'));
    }

    public function show(SpeakerInvite $invite){
        $invite->load('event');
        $event = $invite->event;
        return \Inertia\Inertia::render('InviteResponse/Index', compact('invite', 'event'));
    }

    public function acceptInvitation(Request $request, \App\Models\Event $event, SpeakerInvite $invite)
    {
        $response = $request->isMethod('patch')
            ? SpeakerInviteStatus::REJECTED
            : SpeakerInviteStatus::ACCEPTED;

        $feedback = $request->input('feedback');
        $result = $this->speakerInvitationService->respondInvitation($invite, $response, $feedback);

        if ($result === 'INVITE_EXPIRED') {
            return redirect()->route('speaker.events.show', $event->slug)->with([
                'type' => 'error',
                'message' => 'This invitation has already expired.',
            ]);
        }

        return redirect()->route('speaker.events.show', $event->slug)->with([
            'type' => 'success',
            'message' => $response === SpeakerInviteStatus::ACCEPTED
                ? 'Invitation accepted. Continue from your speaker workspace.'
                : 'Invitation declined.',
        ]);
    }


}
