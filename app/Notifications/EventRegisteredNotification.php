<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Event;
use Illuminate\Support\Carbon;

class EventRegisteredNotification extends Notification
{
    use Queueable;

    public Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('You’re Registered for ' . $this->event->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have successfully registered for the event: **' . $this->event->title . '**.')
             ->line('📅 Date: ' . Carbon::parse($this->event->start_date)->format('F j, Y \a\t g:i A'))
            ->line('📍 Location: ' . $this->event->location)
            ->action('View Event', url('/events/' . $this->event->slug))
            ->line('Thank you for your interest!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'event_slug' => $this->event->slug,
        ];
    }
}
