<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CoursePreviewVideoStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Course $course,
        public string $status
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
        $message = (new MailMessage)
            ->subject("Course Preview Video Status: {$this->course->title}");

        if ($this->status === 'ready') {
            $message->greeting("Hello {$notifiable->name},")
                ->line("Great news! Your course preview video has been uploaded successfully.")
                ->line("**Course:** {$this->course->title}")
                ->line("The preview video is now available on your course page.")
                ->action('View Course', route('instructor.courses.builder', $this->course->slug));
        } elseif ($this->status === 'failed') {
            $message->greeting("Hello {$notifiable->name},")
                ->line("Unfortunately, the course preview video upload failed.")
                ->line("**Course:** {$this->course->title}")
                ->line("Please try uploading the preview video again or contact support if the issue persists.")
                ->action('Edit Course', route('instructor.courses.edit', $this->course->slug));
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusMessages = [
            'ready' => 'has been uploaded successfully',
            'failed' => 'upload failed',
        ];

        return [
            'course_id' => $this->course->id,
            'course_title' => $this->course->title,
            'status' => $this->status,
            'message' => "Course preview video for '{$this->course->title}' " . ($statusMessages[$this->status] ?? $this->status),
            'action_url' => route('instructor.courses.builder', $this->course->slug),
            'type' => 'course_preview_video_status',
        ];
    }
}
