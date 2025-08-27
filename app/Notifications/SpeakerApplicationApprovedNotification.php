<?php

namespace App\Notifications;


use App\Models\SpeakerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SpeakerApplicationApprovedNotification extends Notification
{
    use Queueable;

    public SpeakerApplication $application;
    public $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(SpeakerApplication $application)
    {
        $this->application = $application;
        $this->event = $application->event;
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
        $event = $this->event;

        $dateRange = $event->start_date
            ? sweet_date($event->start_date) .
            ($event->end_date ? ' - ' . sweet_date($event->end_date) : '')
            : null;

        return (new MailMessage)
            ->subject("You're Approved for {$event->title}!")
            ->greeting("Hi {$notifiable->name},")
            ->line("🎉 Congratulations! Your speaker application for **{$event->title}** has been approved.")
            ->line("Here are the event details:")
            ->line("📅 **Date:** {$dateRange}")
            ->line("📍 **Location:** " . ($event->location ?? 'To be announced'))
            ->when($event->mode === 'online', fn($msg) =>
                $msg->line("🌐 This is an online event — you’ll receive login details soon."))
            ->action('View Event Details', route('events.show', $event->slug))
            ->line("We’re excited to have you as a speaker!");
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
