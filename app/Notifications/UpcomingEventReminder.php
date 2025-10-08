<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingEventReminder extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Event $event)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Upcoming Event Reminder: {$this->event->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("We wanted to remind you that **{$this->event->title}** is happening soon!")
            ->line('Here are the event details:')
            ->line('📅 **Starts:** ' . sweet_date($this->event->start_date))
            ->line('📅 **Ends:** ' . sweet_date($this->event->end_date))
            ->line('📍 **Location/Link:** ' . $this->formatLocation())
            ->action('View Event Details', route('events.show', $this->event->slug))
            ->line('Please mark your calendar so you don’t miss out.')
            ->salutation("Warm regards,\nThe " . config('app.name') . " Team");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function formatLocation(): string
    {
        return match ($this->event->mode) {
            'online' => $this->event->location,
            'offline' => $this->event->physical_address,
            'hybrid' => $this->event->location . ' (Online), ' . $this->event->physical_address . ' (In-person)',
            default => 'N/A',
        };
    }
}
