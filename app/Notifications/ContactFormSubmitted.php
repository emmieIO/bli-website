<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $message
    ) {}

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
        return (new MailMessage)
            ->subject('New Contact Form Submission from ' . $this->name)
            ->replyTo($this->email, $this->name)
            ->greeting('New Contact Form Submission')
            ->line('You have received a new message from the contact form:')
            ->line('')
            ->line('**Name:** ' . $this->name)
            ->line('**Email:** ' . $this->email)
            ->line('')
            ->line('**Message:**')
            ->line($this->message)
            ->line('')
            ->line('This message was sent from the Beacon Leadership Institute contact form.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
            'notification_message' => "New contact form submission from {$this->name}",
            'type' => 'contact_form',
        ];
    }
}
