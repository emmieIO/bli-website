<?php

namespace App\Notifications;

use App\Models\InstructorPayout;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstructorPayoutStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public InstructorPayout $payout,
        public string $oldStatus,
        public string $newStatus,
        public ?string $additionalInfo = null
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
        $message = (new MailMessage)
            ->subject("Payout Status Updated: {$this->payout->payout_reference}");

        if ($this->newStatus === 'processing') {
            $message->greeting("Hello {$notifiable->name},")
                ->line("Your payout request is now being processed.")
                ->line("**Reference:** {$this->payout->payout_reference}")
                ->line("**Amount:** " . number_format((float) $this->payout->amount, 2) . " {$this->payout->currency}")
                ->line("We'll notify you once the payout is completed.");
        } elseif ($this->newStatus === 'completed') {
            $message->greeting("Hello {$notifiable->name},")
                ->line("Great news! Your payout has been completed.")
                ->line("**Reference:** {$this->payout->payout_reference}")
                ->line("**Amount:** " . number_format((float) $this->payout->amount, 2) . " {$this->payout->currency}")
                ->line("The funds should arrive in your account shortly.");

            if ($this->additionalInfo) {
                $message->line("**Transaction Reference:** {$this->additionalInfo}");
            }
        } elseif ($this->newStatus === 'failed') {
            $message->greeting("Hello {$notifiable->name},")
                ->line("Unfortunately, your payout request could not be processed.")
                ->line("**Reference:** {$this->payout->payout_reference}")
                ->line("**Amount:** " . number_format((float) $this->payout->amount, 2) . " {$this->payout->currency}");

            if ($this->additionalInfo) {
                $message->line("**Reason:** {$this->additionalInfo}");
            }

            $message->line("Your earnings have been returned to your available balance.")
                ->line("Please contact support if you need assistance.");
        } elseif ($this->newStatus === 'cancelled') {
            $message->greeting("Hello {$notifiable->name},")
                ->line("Your payout request has been cancelled.")
                ->line("**Reference:** {$this->payout->payout_reference}")
                ->line("**Amount:** " . number_format((float) $this->payout->amount, 2) . " {$this->payout->currency}")
                ->line("Your earnings have been returned to your available balance.");
        }

        $message->action('View Earnings Dashboard', route('instructor.earnings.index'));

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'processing' => 'being processed',
            'completed' => 'completed successfully',
            'failed' => 'failed',
            'cancelled' => 'cancelled',
        ];

        return [
            'payout_id' => $this->payout->id,
            'payout_reference' => $this->payout->payout_reference,
            'amount' => $this->payout->amount,
            'currency' => $this->payout->currency,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => "Your payout request of {$this->payout->currency} " . number_format((float) $this->payout->amount, 2) . " is now " . ($statusLabels[$this->newStatus] ?? $this->newStatus),
            'action_url' => route('instructor.earnings.index'),
            'type' => 'payout_status_changed',
            'additional_info' => $this->additionalInfo,
        ];
    }
}
