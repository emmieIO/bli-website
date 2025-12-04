<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseSubmittedForReview extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Course $course
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
        $instructorName = $this->course->instructor->name ?? 'Unknown Instructor';

        return (new MailMessage)
            ->subject('New Course Submitted for Review')
            ->greeting('Hello Admin,')
            ->line("A new course has been submitted for review by {$instructorName}.")
            ->line("**Course Title:** {$this->course->title}")
            ->line("**Instructor:** {$instructorName}")
            ->line("**Category:** " . ($this->course->category->name ?? 'Uncategorized'))
            ->action('Review Course', route('admin.courses.index'))
            ->line('Please review and approve or reject this course at your earliest convenience.');
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
            'instructor_name' => $this->course->instructor->name ?? 'Unknown Instructor',
            'instructor_id' => $this->course->instructor_id,
            'message' => "New course '{$this->course->title}' submitted for review",
            'action_url' => route('admin.courses.index'),
            'type' => 'course_submitted',
        ];
    }
}
