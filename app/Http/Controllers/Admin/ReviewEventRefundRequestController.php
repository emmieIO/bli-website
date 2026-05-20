<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRefundRequest;
use App\Services\Event\EventRegistrationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ReviewEventRefundRequestController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected EventRegistrationService $eventRegistrationService
    ) {}

    public function approve(EventRefundRequest $refundRequest)
    {
        $this->authorize('manageAttendees', $refundRequest->event);

        if ($this->eventRegistrationService->approveRefundRequest($refundRequest, auth()->id())) {
            return back()->with([
                'type' => 'success',
                'message' => 'Refund request approved and registration updated.',
            ]);
        }

        return back()->with([
            'type' => 'error',
            'message' => 'Unable to approve refund request.',
        ]);
    }

    public function decline(Request $request, EventRefundRequest $refundRequest)
    {
        $this->authorize('manageAttendees', $refundRequest->event);

        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        if ($this->eventRegistrationService->declineRefundRequest($refundRequest, auth()->id(), $validated['admin_note'] ?? null)) {
            return back()->with([
                'type' => 'success',
                'message' => 'Refund request declined.',
            ]);
        }

        return back()->with([
            'type' => 'error',
            'message' => 'Unable to decline refund request.',
        ]);
    }
}
