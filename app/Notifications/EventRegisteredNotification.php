<?php

namespace App\Notifications;

use App\Services\Events\EventCalendarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Event;
use Illuminate\Support\Carbon;

class EventRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Event $event)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $calendarService = app(EventCalendarService::class);
        $ics = $calendarService->downloadEventCalendar($this->event);

        $startDate = Carbon::parse($this->event->start_date);
        $endDate = Carbon::parse($this->event->end_date);

        // Format dates
        $dateRange = $startDate->isSameDay($endDate)
            ? $startDate->format('l, F j, Y')
            : $startDate->format('F j') . ' - ' . $endDate->format('F j, Y');

        $timeRange = $startDate->format('g:i A') . ' - ' . $endDate->format('g:i A');

        // Determine location display based on mode
        $locationDisplay = match($this->event->mode) {
            'online' => 'Online Event (Link will be provided closer to the event)',
            'offline' => $this->event->physical_address ?? $this->event->location ?? 'Venue TBA',
            'hybrid' => 'Hybrid Event - Join online or in person',
            default => $this->event->location ?? 'Location TBA'
        };

        $mail = (new MailMessage)
            ->subject('🎉 Registration Confirmed - ' . $this->event->title)
            ->greeting('Hello ' . ucfirst($notifiable->name) . '!')
            ->line("**Congratulations!** Your registration for **{$this->event->title}** has been confirmed.")
            ->line('---')
            ->line('### Event Information')
            ->line('**Event:** ' . $this->event->title);

        // Add theme if available
        if ($this->event->theme) {
            $mail->line('**Theme:** ' . $this->event->theme);
        }

        $mail->line('**Date:** ' . $dateRange)
            ->line('**Time:** ' . $timeRange)
            ->line('**Mode:** ' . ucfirst($this->event->mode ?? 'Hybrid'))
            ->line('**Location:** ' . $locationDisplay);

        // Add entry fee information
        if ($this->event->entry_fee > 0) {
            $mail->line('**Entry Fee:** ₦' . number_format($this->event->entry_fee, 2));
        } else {
            $mail->line('**Entry Fee:** FREE');
        }

        $mail->line('---')
            ->action('View Full Event Details', route('events.show', $this->event->slug))
            ->line('### What\'s Next?')
            ->line('✅ Your spot is secured')
            ->line('✅ Calendar invite is attached to this email')
            ->line('✅ You\'ll receive a reminder before the event');

        // Add mode-specific instructions
        if ($this->event->mode === 'online' || $this->event->mode === 'hybrid') {
            $mail->line('✅ Meeting link will be available on the event page 1 hour before start time');
        }

        if ($this->event->mode === 'offline' || $this->event->mode === 'hybrid') {
            $mail->line('✅ Please arrive 15 minutes early for check-in');
        }

        $mail->line('---')
            ->line('**Need to make changes?** Visit your event dashboard to manage your registrations.')
            ->line('If you have any questions, feel free to reach out to us.')
            ->line('We look forward to seeing you at the event!')
            ->salutation('Best regards,
The ' . config('app.name') . ' Team')
            ->attachData(
                $ics,
                str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $this->event->title) . '.ics',
                ['mime' => 'text/calendar; method=REQUEST; charset=utf-8;']
            );

        // Set reply-to to event contact email if available
        if ($this->event->contact_email) {
            $mail->replyTo($this->event->contact_email);
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        $startDate = Carbon::parse($this->event->start_date);
        $endDate = Carbon::parse($this->event->end_date);

        $dateRange = $startDate->isSameDay($endDate)
            ? $startDate->format('l, F j, Y')
            : $startDate->format('F j') . ' - ' . $endDate->format('F j, Y');

        $timeRange = $startDate->format('g:i A') . ' - ' . $endDate->format('g:i A');

        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'event_slug' => $this->event->slug,
            'start_date' => $this->event->start_date,
            'end_date' => $this->event->end_date,
            'date_range' => $dateRange,
            'time_range' => $timeRange,
            'mode' => $this->event->mode ?? 'Hybrid',
            'message' => "Your registration for '{$this->event->title}' has been confirmed!",
            'action_url' => route('events.show', $this->event->slug),
            'type' => 'event_registration',
        ];
    }
}
