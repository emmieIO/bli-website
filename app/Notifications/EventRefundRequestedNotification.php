<?php

namespace App\Notifications;

use App\Models\EventRefundRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventRefundRequestedNotification extends Notification
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
            'type' => 'event_refund_requested',
            'message' => "{$this->refundRequest->user->name} requested a refund for {$this->refundRequest->event->title}.",
            'action_url' => route('admin.events.show', $this->refundRequest->event),
            'event_id' => $this->refundRequest->event_id,
            'event_slug' => $this->refundRequest->event->slug,
            'refund_request_id' => $this->refundRequest->id,
            'status' => $this->refundRequest->status,
        ];
    }
}
