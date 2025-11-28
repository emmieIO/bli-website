<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseRejected extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Course $course,
        public ?string $rejectionReason = null
    ) {
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
        $message = (new MailMessage)
            ->subject('Course Review Update - ' . $this->course->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Thank you for submitting your course for review.')
            ->line("**Course Title:** {$this->course->title}")
            ->line('Unfortunately, your course has not been approved at this time.');

        if ($this->rejectionReason) {
            $message->line("**Reason:** {$this->rejectionReason}");
        }

        $message->line('You can make the necessary changes and resubmit your course for review.')
            ->action('Edit Course', route('instructor.courses.edit', $this->course))
            ->line('If you have any questions, please contact our support team.');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'course_id' => $this->course->id,
            'course_title' => $this->course->title,
            'course_slug' => $this->course->slug,
            'rejection_reason' => $this->rejectionReason,
            'message' => "Your course '{$this->course->title}' was not approved",
            'action_url' => route('instructor.courses.edit', $this->course),
            'type' => 'course_rejected',
        ];
    }
}
