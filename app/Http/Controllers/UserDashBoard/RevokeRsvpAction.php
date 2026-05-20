<?php

namespace App\Http\Controllers\UserDashBoard;

use App\Http\Controllers\Controller;
use App\Services\Event\EventRegistrationService;
use Illuminate\Http\Request;

class RevokeRsvpAction extends Controller
{
    public function __construct(
        protected EventRegistrationService $eventRegistrationService
    ){}
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $slug)
    {
        if($this->eventRegistrationService->revokeRsvp($slug)){
            return back()->with([
                "type" => "success",
                "message" => "Your RSVP has been successfully revoked."
            ]);
        }
        return back()->with([
            "type" => "error",
            "message" => "Failed to revoke your RSVP. Please try again."
        ]);
    }
}
