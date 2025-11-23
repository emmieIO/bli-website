<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingEventReminder extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Event $event)
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
        $startDate = \Carbon\Carbon::parse($this->event->start_date);
        $endDate = \Carbon\Carbon::parse($this->event->end_date);
        $now = \Carbon\Carbon::now();

        // Calculate time until event
        $hoursUntil = $now->diffInHours($startDate);
        $daysUntil = $now->diffInDays($startDate);

        $timeUntil = match(true) {
            $hoursUntil < 2 => 'in less than 2 hours',
            $hoursUntil < 24 => "in {$hoursUntil} hours",
            $daysUntil === 1 => 'tomorrow',
            default => "in {$daysUntil} days"
        };

        // Format dates
        $dateRange = $startDate->isSameDay($endDate)
            ? $startDate->format('l, F j, Y')
            : $startDate->format('F j') . ' - ' . $endDate->format('F j, Y');

        $timeRange = $startDate->format('g:i A') . ' - ' . $endDate->format('g:i A');

        $mail = (new MailMessage)
            ->subject("⏰ Reminder: {$this->event->title} is {$timeUntil}!")
            ->greeting("Hello {$notifiable->name}!")
            ->line("**Your registered event is starting {$timeUntil}!**")
            ->line("Don't forget about **{$this->event->title}** - we're excited to see you there!")
            ->line('---')
            ->line('### Event Details')
            ->line('**Event:** ' . $this->event->title);

        // Add theme if available
        if ($this->event->theme) {
            $mail->line('**Theme:** ' . $this->event->theme);
        }

        $mail->line('**Date:** ' . $dateRange)
            ->line('**Time:** ' . $timeRange)
            ->line('**Mode:** ' . ucfirst($this->event->mode ?? 'Hybrid'))
            ->line('**Location:** ' . $this->formatLocation());

        $mail->line('---')
            ->action('Join Event Now', route('events.show', $this->event->slug));

        // Add mode-specific reminders
        if ($this->event->mode === 'online' || $this->event->mode === 'hybrid') {
            $mail->line('### Online Access')
                ->line('🔗 Click the button above to access the meeting link')
                ->line('💻 Test your audio and video before joining')
                ->line('📱 Keep your device charged');
        }

        if ($this->event->mode === 'offline' || $this->event->mode === 'hybrid') {
            $mail->line('### In-Person Reminders')
                ->line('🚗 Plan your route and parking in advance')
                ->line('⏰ Arrive 15 minutes early for check-in')
                ->line('🎫 Have this confirmation email ready');
        }

        $mail->line('---')
            ->line('**Can\'t make it?** Please cancel your registration so others can attend.')
            ->line('We look forward to seeing you soon!')
            ->salutation("Best regards,
The " . config('app.name') . " Team");

        // Set reply-to to event contact email if available
        if ($this->event->contact_email) {
            $mail->replyTo($this->event->contact_email);
        }

        return $mail;
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

    public function formatLocation(): string
    {
        return match ($this->event->mode) {
            'online' => 'Online Event (Access link on event page)',
            'offline' => $this->event->physical_address ?? 'Venue TBA',
            'hybrid' => 'Hybrid Event - Online & ' . ($this->event->physical_address ?? 'Physical venue TBA'),
            default => $this->event->location ?? 'Location TBA',
        };
    }
}
