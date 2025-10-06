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
            ->subject('Reminder: ' . $this->event->title . ' starts ' . sweet_date($this->event->start_date))
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('This is a friendly reminder that **' . $this->event->title . '** is happening soon.')
            ->line('**From:** ' . sweet_date($this->event->start_date))
            ->line('**To:** ' . sweet_date($this->event->end_date))
            ->line('**Location/Link:** ' . (
                $this->event->mode === 'online'
                ? $this->event->location
                : ($this->event->mode === 'offline'
                    ? $this->event->physical_address
                    : ($this->event->mode === 'hybrid'
                        ? ($this->event->location . ' (Online), ' . $this->event->physical_address . ' (In-person)')
                        : 'N/A'
                    )
                )
            ))
            ->line('Please mark your calendar so you don’t miss it.')
            ->salutation('— The ' . config('app.name') . ' Team');
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
}
