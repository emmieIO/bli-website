<?php

namespace App\Mail\Instructors;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class InstructorsApplication extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $applicationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->applicationUrl = URL::temporarySignedRoute(
            "instructors.application-form",
            Carbon::now()->addDays(3),
            ['user'=>$user->id]
        );
    
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Continue Your Instructors Application',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.instructors.application-link',
            with: [
                "url" => $this->applicationUrl,
                'name' => 'there'
            ]
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
