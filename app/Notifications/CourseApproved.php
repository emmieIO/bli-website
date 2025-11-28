<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseApproved extends Notification
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
        return (new MailMessage)
            ->subject('Course Approved - ' . $this->course->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Congratulations! Your course has been approved.')
            ->line("**Course Title:** {$this->course->title}")
            ->line('Your course is now live and available for students to enroll.')
            ->action('View My Courses', route('instructor.courses.index'))
            ->line('Thank you for contributing quality content to our platform!');
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
            'message' => "Congratulations! Your course '{$this->course->title}' has been approved and is now live.",
            'action_url' => route('instructor.courses.index'),
            'type' => 'course_approved',
        ];
    }
}
