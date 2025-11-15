<?php

namespace App\Http\Controllers;

use App\Models\SpeakerInvite;
use App\Services\Event\SpeakerService;
use Illuminate\Http\Request;

class SpeakerInvitationController extends Controller
{
    public function __construct(public SpeakerService $speakerService){}

    public function index(){
        $invitations = $this->speakerService->getSpeakerInvites();
        return \Inertia\Inertia::render('MyInvitations/Index', compact('invitations'));
    }

    public function show(SpeakerInvite $invite){
        $invite->load('event');
        $event = $invite->event;
        return \Inertia\Inertia::render('InviteResponse/Index', compact('invite', 'event'));
    }

    public function acceptInvitation(){
        
    }


}
