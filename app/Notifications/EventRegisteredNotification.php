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

    public function __construct(
        public Event $event
    )
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

        $subject = 'Registration Confirmed - ' . $this->event->title;
        $nextSteps = [
            'Your spot is secured',
            'A calendar invite is attached to this email',
            'You will receive a reminder before the event',
        ];

        $mail = (new MailMessage)
            ->subject($subject)
            ->view([
                'html' => 'emails.events.registered',
                'text' => 'emails.events.registered_plain',
            ], [
                'recipientName' => ucfirst($notifiable->name),
                'event' => $this->event,
                'subjectLine' => $subject,
                'dateRange' => $dateRange,
                'timeRange' => $timeRange,
                'locationDisplay' => $locationDisplay,
                'entryFeeDisplay' => $this->event->entry_fee > 0
                    ? 'N' . number_format($this->event->entry_fee, 2)
                    : 'Free',
                'workspaceUrl' => route('events.open', $this->event),
                'nextSteps' => $nextSteps,
                'modeTips' => $this->buildModeTips(),
                'contactEmail' => $this->event->contact_email,
                'appName' => config('app.name'),
            ])
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

    protected function buildModeTips(): array
    {
        $tips = [];

        if ($this->event->mode === 'online' || $this->event->mode === 'hybrid') {
            $tips[] = 'Meeting access will be available from your event workspace before the session starts.';
        }

        if ($this->event->mode === 'offline' || $this->event->mode === 'hybrid') {
            $tips[] = 'Please plan to arrive at least 15 minutes early for check-in.';
        }

        return $tips;
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
            'action_url' => route('events.open', $this->event),
            'type' => 'event_registration',
        ];
    }
}
