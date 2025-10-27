<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SpeakerAccountCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public bool $isAdminCreated = true)
    {
        //
    }

    /**
     * Determine the channels for the notification.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name');

        $mail = (new MailMessage)
            ->subject("Welcome to {$appName} — Your Speaker Account Creation Successful")
            ->greeting("Dear {$notifiable->name},")
            ->line("We’re delighted to welcome you to the {$appName} community of speakers and thought leaders.");

        if ($this->isAdminCreated) {
            $mail->line("Your profile has been created by our administrative team. You’ve been invited to join as one of our distinguished speakers.")
                ->line("To activate your account and gain access to your speaker dashboard, please set your password using the separate email we’ve just sent you.")
                ->line("Once logged in, you can update your profile, manage your sessions, and explore upcoming events where your expertise can make an impact.");
        } else {
            $mail->line("Thank you for expressing interest in joining {$appName} as a speaker.")
                ->line("Our team is currently reviewing your application, and you’ll receive another notification once your account has been approved and activated.")
                ->line("In the meantime, please ensure your email address is verified so we can keep you informed about future opportunities.");
        }

        return $mail
            ->line("If you have any questions or need assistance, feel free to reach out to our support team at support@" . parse_url(config('app.url'), PHP_URL_HOST) . ".")
            ->salutation("Warm regards,\nThe {$appName} Team");
    }

    /**
     * Array form for database or broadcast notifications.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'speaker_account_created',
            'created_by_admin' => $this->isAdminCreated,
        ];
    }
}
