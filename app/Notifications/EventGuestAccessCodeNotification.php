<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventGuestAccessCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Event $event,
        public string $accessCode
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Your new access code - '.$this->event->title)
            ->greeting('Hello '.($notifiable->name ?: 'there').',')
            ->line('A new guest access code was requested for '.$this->event->title.'.')
            ->line('Your new access code is: **'.$this->accessCode.'**')
            ->line('This code expires in 15 minutes. Your previous access code will no longer work.')
            ->action('Open event details', route('events.open', $this->event))
            ->line('Enter this code with the email address used for your registration.');

        if ($this->event->contact_email) {
            $mail->replyTo($this->event->contact_email);
        }

        return $mail;
    }
}
