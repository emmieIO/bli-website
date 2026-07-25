<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\EventGuestAttendee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class EventLiveNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Event $event,
        public ?string $guestAccessCode = null
    ) {}

    public function via(object $notifiable): array
    {
        return $notifiable instanceof EventGuestAttendee ? ['mail'] : ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isGuest = $notifiable instanceof EventGuestAttendee;
        $eventDay = $this->event->currentOrNextDay();
        $meetingLink = $this->event->meetingLinkFor($eventDay);
        $startAt = Carbon::parse($eventDay?->start_at ?? $this->event->start_date)
            ->timezone(config('app.timezone'));
        $actionUrl = $isGuest
            ? route('events.show', $this->event->slug)
            : ($meetingLink ?? route('events.show', $this->event->slug));

        $mail = (new MailMessage)
            ->subject("We're live now - {$this->event->title}")
            ->greeting('Hello '.($notifiable->name ?: 'there').',')
            ->line("**{$this->event->title} is live now.**")
            ->line('The session is open and ready for confirmed attendees.')
            ->line('**Start time:** '.$startAt->format('l, F j, Y g:i A').' West Africa Time (WAT)');

        if ($eventDay?->theme || $this->event->theme) {
            $mail->line('**Theme:** '.($eventDay?->theme ?: $this->event->theme));
        }

        if ($isGuest && $this->guestAccessCode) {
            $mail
                ->line('**Your guest access code:** '.$this->guestAccessCode)
                ->line('This code expires in 15 minutes. Enter it with your registration email on the event page.');
        } elseif ($meetingLink) {
            $mail->line('Use the button below to enter the live session.');
        }

        $mail
            ->action($isGuest ? 'Enter Access Code and Join' : 'Join Event Now', $actionUrl)
            ->line('We look forward to having you with us.');

        if ($this->event->contact_email) {
            $mail->replyTo($this->event->contact_email);
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'event_slug' => $this->event->slug,
            'message' => "{$this->event->title} is live now.",
            'action_url' => $this->event->meetingLinkFor($this->event->currentOrNextDay())
                ?? route('events.show', $this->event->slug),
            'timezone' => 'Africa/Lagos',
            'type' => 'event_live',
        ];
    }
}
