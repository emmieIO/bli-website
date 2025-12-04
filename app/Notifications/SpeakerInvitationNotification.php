<?php

namespace App\Notifications;

use App\Models\SpeakerInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class SpeakerInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private string $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(public SpeakerInvite $invitation)
    {
        $this->url = URL::temporarySignedRoute(
            "invitations.respond",
            Carbon::parse($this->invitation->expires_at),
            [
                'event' => $this->invitation->event,
                'invite' => $this->invitation->id
            ]
        );
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
        $mail = (new MailMessage)
            ->subject('Invitation to speak at ' . $this->invitation->event->title)
            ->greeting('Hi ' . ($this->invitation->speaker?->name ?? 'Speaker') . ',')
            ->line("We're thrilled to be inviting you to speak at **{$this->invitation->event->title}** on " . sweet_date($this->invitation->event->start_date) . ".")
            ->action('Respond to Invitation', $this->url)
            ->line('---')
            ->line('### Event at a glance')
            ->line('**Mode:** ' . ucfirst($this->invitation->event->mode))
            ->line('**Location / Link:** ' . ($this->invitation->event->location ?? 'To be announced'));

        if ($this->invitation->suggested_topic) {
            $mail->line('**Suggested Topic:** ' . $this->invitation->suggested_topic);
        }

        if ($this->invitation->suggested_duration) {
            $mail->line('**Suggested Duration:** ' . $this->invitation->suggested_duration . ' minutes');
        }

        if ($this->invitation->expected_format) {
            $mail->line('**Expected Format:** ' . $this->invitation->expected_format);
        }

        if ($this->invitation->audience_expectations) {
            $mail->line('')
                ->line('**Audience Expectations:**')
                ->line($this->invitation->audience_expectations);
        }

        if ($this->invitation->special_instructions) {
            $mail->line('')
                ->line('**Special Instructions:**')
                ->line($this->invitation->special_instructions);
        }

        if ($this->invitation->additional_notes) {
            $mail->line('')
                ->line('**Additional Notes:**')
                ->line($this->invitation->additional_notes);
        }

        $mail->line('')
            ->line('Please respond to this invitation at your earliest convenience.')
            ->salutation("Best regards,\nThe " . config('app.name') . " Team");

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
            'invitation_id' => $this->invitation->id,
            'event_id' => $this->invitation->event_id,
            'event_title' => $this->invitation->event->title,
            'event_slug' => $this->invitation->event->slug,
            'message' => "You've been invited to speak at '{$this->invitation->event->title}'",
            'action_url' => $this->url,
            'type' => 'speaker_invitation',
        ];
    }
}
