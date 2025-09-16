<?php

namespace App\Mail;

use App\Models\SpeakerInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Log;

class InvitationToSpeakerMail extends Mailable
{
    use Queueable, SerializesModels;
    private $url;
    /**
     * Create a new message instance.
     */
    public function __construct(public SpeakerInvite $invitation)
    {
        $this->url = URL::temporarySignedRoute(
            "invitations.respond",
            Carbon::parse($this->invitation->expires_at),
            [
                'event'=>$this->invitation->event,
                'invite' => $this->invitation->id
                ]
        );
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation to speak at ' . $this->invitation->event->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.invitations.speaker',
            with: [
                'invitation' => $this->invitation,
                'url' => $this->url,
            ],
            
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
