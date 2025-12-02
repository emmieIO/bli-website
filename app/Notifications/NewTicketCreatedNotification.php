<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTicketCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Ticket $ticket
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
            ->subject("New Support Ticket #{$this->ticket->reference_code}")
            ->greeting("Hello Admin,")
            ->line("A new support ticket has been created.")
            ->line("**Ticket:** #{$this->ticket->reference_code}")
            ->line("**From:** {$this->ticket->user->name}")
            ->line("**Subject:** {$this->ticket->subject}")
            ->line("**Priority:** " . ucfirst($this->ticket->priority))
            ->action('View Ticket', route('admin.tickets.show', $this->ticket->id))
            ->line('Please respond as soon as possible.');
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
            'user_name' => $this->ticket->user->name,
            'user_id' => $this->ticket->user_id,
            'priority' => $this->ticket->priority,
            'message' => "New support ticket #{$this->ticket->reference_code} from {$this->ticket->user->name}: {$this->ticket->subject}",
            'action_url' => route('admin.tickets.show', $this->ticket->id),
            'type' => 'new_ticket',
        ];
    }
}
