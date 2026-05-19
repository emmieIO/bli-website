<?php

namespace App\Notifications;

use App\Models\EventRefundRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventRefundRequestReviewedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public EventRefundRequest $refundRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'event_refund_reviewed',
            'message' => $this->refundRequest->status === 'approved'
                ? "Your refund request for {$this->refundRequest->event->title} has been approved."
                : "Your refund request for {$this->refundRequest->event->title} was declined.",
            'action_url' => route('events.open', $this->refundRequest->event),
            'event_id' => $this->refundRequest->event_id,
            'event_slug' => $this->refundRequest->event->slug,
            'refund_request_id' => $this->refundRequest->id,
            'status' => $this->refundRequest->status,
            'admin_note' => $this->refundRequest->admin_note,
        ];
    }
}
