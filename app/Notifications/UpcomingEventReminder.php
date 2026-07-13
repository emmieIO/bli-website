<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\EventGuestAttendee;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingEventReminder extends Notification implements ShouldQueue
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
        if ($notifiable instanceof EventGuestAttendee) {
            return ['mail'];
        }

        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $startDate = Carbon::parse($this->event->start_date);
        $endDate = Carbon::parse($this->event->end_date);
        $timeUntil = $this->timeUntilStart($startDate);

        // Format dates
        $dateRange = $startDate->isSameDay($endDate)
            ? $startDate->format('l, F j, Y')
            : $startDate->format('F j').' - '.$endDate->format('F j, Y');

        $timeRange = $startDate->format('g:i A').' - '.$endDate->format('g:i A');
        $recipientName = $notifiable->name ?: 'there';

        $mail = (new MailMessage)
            ->subject("⏰ Reminder: {$this->event->title} is {$timeUntil}!")
            ->greeting("Hello {$recipientName}!")
            ->line("**Your confirmed event is starting {$timeUntil}!**")
            ->line("Don't forget about **{$this->event->title}** - we're excited to see you there!")
            ->line('---')
            ->line('### Event Details')
            ->line('**Event:** '.$this->event->title);

        // Add theme if available
        if ($this->event->theme) {
            $mail->line('**Theme:** '.$this->event->theme);
        }

        $mail->line('**Date:** '.$dateRange)
            ->line('**Time:** '.$timeRange)
            ->line('**Mode:** '.ucfirst($this->event->mode ?? 'Hybrid'))
            ->line('**Location:** '.$this->formatLocation());

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
            ->salutation('Best regards,
The '.config('app.name').' Team');

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
        $startDate = Carbon::parse($this->event->start_date);
        $timeUntil = $this->timeUntilStart($startDate);

        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'event_slug' => $this->event->slug,
            'start_date' => $this->event->start_date,
            'time_until' => $timeUntil,
            'mode' => $this->event->mode,
            'location' => $this->formatLocation(),
            'message' => "Reminder: {$this->event->title} is starting {$timeUntil}!",
            'action_url' => route('events.show', $this->event->slug),
            'type' => 'event_reminder',
        ];
    }

    public function formatLocation(): string
    {
        return match ($this->event->mode) {
            'online' => 'Online Event (Access link on event page)',
            'offline' => $this->event->physical_address ?? 'Venue TBA',
            'hybrid' => 'Hybrid Event - Online & '.($this->event->physical_address ?? 'Physical venue TBA'),
            default => $this->event->location ?? 'Location TBA',
        };
    }

    private function timeUntilStart(Carbon $startDate): string
    {
        $now = Carbon::now();
        $minutesUntil = max(0, (int) ceil($now->diffInMinutes($startDate)));

        if ($minutesUntil < 120) {
            return 'in less than 2 hours';
        }

        if ($minutesUntil < 1440) {
            $hoursUntil = (int) round($minutesUntil / 60);

            return 'in '.$hoursUntil.' hour'.($hoursUntil === 1 ? '' : 's');
        }

        $daysUntil = (int) round($minutesUntil / 1440);

        return $daysUntil === 1 ? 'tomorrow' : 'in '.$daysUntil.' days';
    }
}
