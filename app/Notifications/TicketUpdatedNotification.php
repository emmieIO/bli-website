<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;
    public $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, $status)
    {
        $this->ticket = $ticket;
        $this->status = $status;
    }

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
        $statusText = match($this->status) {
            'open' => 'opened',
            'in_progress' => 'in progress',
            'closed' => 'closed',
            default => $this->status
        };

        return (new MailMessage)
            ->subject("Support Ticket #{$this->ticket->reference_code} Updated")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your support ticket has been updated.")
            ->line("**Ticket:** #{$this->ticket->reference_code}")
            ->line("**Subject:** {$this->ticket->subject}")
            ->line("**New Status:** " . ucfirst($statusText))
            ->action('View Ticket', route('user.tickets.show', $this->ticket->id))
            ->line('Thank you for your patience!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_reference' => $this->ticket->reference_code,
            'ticket_subject' => $this->ticket->subject,
            'message' => "Your support ticket #{$this->ticket->reference_code} has been updated.",
            'link' => route('user.tickets.show', $this->ticket->id),
            'status' => $this->status,
        ];
    }
}
