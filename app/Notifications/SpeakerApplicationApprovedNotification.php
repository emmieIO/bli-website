<?php

namespace App\Notifications;


use App\Models\SpeakerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SpeakerApplicationApprovedNotification extends Notification implements ShouldQueue
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
        return ['database', 'mail'];
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
            'application_id' => $this->application->id,
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'event_slug' => $this->event->slug,
            'message' => "Your application to speak at '{$this->event->title}' has been approved!",
            'action_url' => route('events.show', $this->event->slug),
            'type' => 'speaker_application_approved',
        ];
    }
}
