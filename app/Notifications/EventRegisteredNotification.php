<?php

namespace App\Notifications;

use App\Services\Events\EventCalendarService;
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

    public function __construct(Event $event, public EventCalendarService $calendarService)
    {
        $this->event = $event;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $ics = $this->calendarService->downloadEventCalendar($this->event);
        return (new MailMessage)
            ->subject('Registration Confirmed: ' . $this->event->title)
            ->greeting('Hello ' . ucfirst($notifiable->name) . ',')
            ->line("We're excited to confirm your registration for **{$this->event->title}**.")
            ->line('Here are the event details:')
            ->line('📅 **Date:** ' . Carbon::parse($this->event->start_date)->format('F j, Y \a\t g:i A'))
            ->line('📍 **Location:** ' . $this->event->location)
            ->action('View Event Details', route('events.show', $this->event->slug))
            ->line('An event calendar file (.ics) is attached for your convenience — you can add it directly to your calendar to stay reminded.')
            ->line('We look forward to seeing you there!')
            ->salutation('Warm regards,  
        The ' . config('app.name') . ' Team')
            ->attachData(
                $ics,
                "{$this->event->title}.ics",
                ['mime' => 'text/calendar; method=REQUEST; charset=utf-8;']
            );
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
