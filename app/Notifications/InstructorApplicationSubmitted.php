<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class InstructorApplicationSubmitted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
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
            ->subject('Your Instructor Application Has Been Submitted')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Thank you for submitting your application to become an instructor.')
            ->line('Our team is currently reviewing your submission.')
            ->line('🕒 The review process typically takes 3–5 business days.')
            ->line('📬 You will receive a follow-up email once a decision has been made.')
            ->action('View Your Application', URL::signedRoute('instructors.view-application', ['user' => $notifiable->id]))
            ->line('Thank you for your interest in contributing as an instructor!');
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
