<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\EventGuestAttendee;
use App\Services\Events\EventCalendarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class EventRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Event $event,
        public ?string $guestAccessCode = null
    ) {}

    public function via(object $notifiable): array
    {
        return $notifiable instanceof EventGuestAttendee ? ['mail'] : ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $calendarService = app(EventCalendarService::class);
        $ics = $calendarService->downloadEventCalendar($this->event);

        $startDate = Carbon::parse($this->event->start_date);
        $endDate = $this->event->end_date ? Carbon::parse($this->event->end_date) : null;
        $isGuest = $notifiable instanceof EventGuestAttendee;
        $meetingLink = $isGuest ? null : $this->event->meetingLinkFor($this->event->currentOrNextDay());

        // Format dates
        $dateRange = ! $endDate || $startDate->isSameDay($endDate)
            ? $startDate->format('l, F j, Y')
            : $startDate->format('F j').' - '.$endDate->format('F j, Y');

        $timeRange = $endDate
            ? $startDate->format('g:i A').' - '.$endDate->format('g:i A')
            : $startDate->format('g:i A');
        $timeRange .= ' West Africa Time (WAT)';

        // Determine location display based on mode
        $locationDisplay = match ($this->event->mode) {
            'online' => $meetingLink ? 'Online event - access link confirmed below' : 'Online event - access link pending',
            'offline' => $this->event->physical_address ?? 'Venue TBA',
            'hybrid' => $meetingLink ? 'Hybrid event - online access confirmed below' : 'Hybrid event - online access pending',
            default => $this->event->physical_address ?? 'Location TBA'
        };

        $subject = 'Registration Confirmed - '.$this->event->title;
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
                'meetingLink' => $meetingLink,
                'guestAccessCode' => $this->guestAccessCode,
                'entryFeeDisplay' => $this->event->entry_fee > 0
                    ? 'N'.number_format($this->event->entry_fee, 2)
                    : 'Free',
                'workspaceUrl' => route('events.open', $this->event),
                'nextSteps' => $nextSteps,
                'modeTips' => $this->buildModeTips(),
                'contactEmail' => $this->event->contact_email,
                'appName' => config('app.name'),
            ])
            ->attachData(
                $ics,
                str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $this->event->title).'.ics',
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
            $tips[] = $this->event->meetingLinkFor($this->event->currentOrNextDay())
                ? 'Use the confirmed meeting link in this email when it is time to join.'
                : 'The organizer will send the meeting link in a later reminder.';
        }

        if ($this->event->mode === 'offline' || $this->event->mode === 'hybrid') {
            $tips[] = 'Please plan to arrive at least 15 minutes early for check-in.';
        }

        return $tips;
    }

    public function toArray(object $notifiable): array
    {
        $startDate = Carbon::parse($this->event->start_date);
        $endDate = $this->event->end_date ? Carbon::parse($this->event->end_date) : null;

        $dateRange = ! $endDate || $startDate->isSameDay($endDate)
            ? $startDate->format('l, F j, Y')
            : $startDate->format('F j').' - '.$endDate->format('F j, Y');

        $timeRange = $endDate
            ? $startDate->format('g:i A').' - '.$endDate->format('g:i A')
            : $startDate->format('g:i A');

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
