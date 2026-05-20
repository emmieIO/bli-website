<?php

namespace App\Http\Controllers\UserDashBoard;

use App\Http\Controllers\Controller;
use App\Services\Event\EventRegistrationService;
use Illuminate\Http\Request;

class RequestEventRefundAction extends Controller
{
    public function __construct(
        protected EventRegistrationService $eventRegistrationService
    ) {}

    public function __invoke(Request $request, string $slug)
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $result = $this->eventRegistrationService->requestRefund($slug, auth()->id(), $validated['reason'] ?? null);

        if ($result === 'pending_created') {
            return back()->with([
                'type' => 'success',
                'message' => 'Your refund request has been submitted and is pending admin review.',
            ]);
        }

        if ($result === 'already_pending') {
            return back()->with([
                'type' => 'info',
                'message' => 'You already have a pending refund request for this event.',
            ]);
        }

        return back()->with([
            'type' => 'error',
            'message' => 'Refund request could not be created for this event.',
        ]);
    }
}
