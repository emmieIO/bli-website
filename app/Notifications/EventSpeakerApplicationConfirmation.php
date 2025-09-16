<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventSpeakerApplicationConfirmation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Event $event)
    {
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
            ->subject("Your Application to Speak at {$this->event->title} – Confirmation")
            ->greeting("Dear {$notifiable->name},")
            ->line("Thank you for submitting your application to speak at {$this->event->title}.")
            ->line("We appreciate your interest in sharing your expertise and insights with our audience. Our team will carefully review your submission and notify you regarding the next steps as soon as possible.")
            ->line("If you have any questions or need further information, please feel free to contact us at " . config('app.support_mail') . ".")
            ->salutation('Best regards, Beacon Leadership Institute Team');
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
