<?php

namespace App\Notifications;

use App\Models\InstructorProfile;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class InstructorApplicationRejection extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public ?string $reason=null, protected InstructorProfile $application)
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
            ->subject('Instructor Application Rejected')
            ->greeting("Hello {$notifiable->name},")
            ->line('Thank you for applying to become an instructor at ' . config('app.name') . '.')
            ->line('Unfortunately, your application has been rejected.')
            ->when($this->reason, fn($mail) => $mail->line("**Reason:** {$this->reason}"))
            ->line('You may review your application or make changes before applying again.')
            ->action('Review Application', URL::signedRoute('instructors.application.resume',$this->application->application_id ))
            ->line('We appreciate your interest and encourage you to try again in the future.')
            ->line('Best regards,')
            ->line(config('app.name'). " Team.")
            ;

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
