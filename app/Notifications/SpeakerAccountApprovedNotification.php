<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Password;

class SpeakerAccountApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

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
        $appName = config('app.name');

        // Generate password reset link
        $token = Password::createToken($notifiable);
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $notifiable->email,
        ], false));

        return (new MailMessage)
            ->subject("🎉 Your Speaker Account Has Been Approved — Welcome to {$appName}")
            ->greeting("Hello {$notifiable->name},")
            ->line("We’re excited to let you know that your speaker account on **{$appName}** has been **approved and activated**.")
            ->line("You can now access your speaker dashboard, manage your profile, and get ready for upcoming events.")
            ->line("To start, please set your password using the link below:")
            ->action('Set Your Password', $resetUrl)
            ->line("Once you’ve set your password, you can log in anytime to manage your sessions, view invitations, and stay connected with event organizers.")
            ->line("We’re thrilled to have you join our community of inspiring voices shaping the future of leadership.")
            ->salutation("Warm regards,\nThe {$appName} Team");
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'speaker_account_approved',
        ];
    }
}
