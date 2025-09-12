<?php

namespace App\Http\Controllers;

use App\Services\Event\SpeakerService;
use Illuminate\Http\Request;

class SpeakerInvitationController extends Controller
{
    public function __construct(public SpeakerService $speakerService){}

    public function index(){
        $invites = $this->speakerService->getSpeakerInvites();
        return view('user_dashboard.my-invitations', compact('invites'));
    }
}
