<?php

namespace App\Notifications;

use App\Models\InstructorPayout;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstructorPayoutRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public InstructorPayout $payout
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New Payout Request: {$this->payout->payout_reference}")
            ->greeting("Hello Admin,")
            ->line("An instructor has requested a payout.")
            ->line("**Instructor:** {$this->payout->instructor->name}")
            ->line("**Amount:** " . number_format($this->payout->amount, 2) . " {$this->payout->currency}")
            ->line("**Method:** " . ucfirst(str_replace('_', ' ', $this->payout->payout_method)))
            ->action('View Payout Request', route('admin.payouts.show', $this->payout->id))
            ->line('Please review and process this request.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payout_id' => $this->payout->id,
            'payout_reference' => $this->payout->payout_reference,
            'amount' => $this->payout->amount,
            'currency' => $this->payout->currency,
            'instructor_name' => $this->payout->instructor->name,
            'instructor_id' => $this->payout->instructor_id,
            'message' => "New payout request of {$this->payout->currency} " . number_format($this->payout->amount, 2) . " from {$this->payout->instructor->name}",
            'action_url' => route('admin.payouts.show', $this->payout->id),
            'type' => 'payout_request',
        ];
    }
}
