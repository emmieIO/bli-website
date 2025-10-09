<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SpeakerRegistrationPendingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
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
            ->subject('Your Speaker Application is Pending Review')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Thank you for applying to become a speaker at Beacon Leadership Institute.')
            ->line('Your profile has been received and is currently under review by our team.')
            ->line('You’ll receive another email once your application has been approved.')
            ->line('In the meantime, please verify your email address using the separate verification link we’ve sent to you.')
            ->salutation('Warm regards,  Beacon Leadership Institute Team');
    }
}
