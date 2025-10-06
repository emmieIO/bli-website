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
            ->subject("Approval to Speak at {$event->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your application to speak at **{$event->title}** has been approved. We're delighted to have you on board as one of our speakers.")
            ->line("Here are the event details:")
            ->line("📅 **Date:** {$dateRange}")
            ->line("📍 **Location:** " . ($event->location ?? 'To be announced'))
            ->when($event->mode === 'online', fn($msg) =>
                $msg->line("🌐 **Mode:** Online"))
            ->action('View Event Details', route('events.show', $event->slug))
            ->line("Please review the event page for your session details and updates. We’re looking forward to your contribution!");

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
