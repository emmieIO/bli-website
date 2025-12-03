<?php

namespace App\Notifications;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VideoUploadStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Lesson $lesson,
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
            ->subject("Video Upload Status: {$this->lesson->title}");

        if ($this->status === 'ready') {
            $message->greeting("Hello {$notifiable->name},")
                ->line("Great news! Your video has been uploaded and processed successfully.")
                ->line("**Lesson:** {$this->lesson->title}")
                ->line("**Course:** {$this->lesson->courseModule->course->title}")
                ->line("The video is now available to your students.")
                ->action('View Course', route('instructor.courses.builder', $this->lesson->courseModule->course->slug));
        } elseif ($this->status === 'failed') {
            $message->greeting("Hello {$notifiable->name},")
                ->line("Unfortunately, the video upload failed.")
                ->line("**Lesson:** {$this->lesson->title}")
                ->line("**Course:** {$this->lesson->courseModule->course->title}")
                ->line("Please try uploading the video again or contact support if the issue persists.")
                ->action('Edit Lesson', route('instructor.courses.lessons.edit', [
                    'course' => $this->lesson->courseModule->course->slug,
                    'module' => $this->lesson->courseModule->id,
                    'lesson' => $this->lesson->id
                ]));
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
            'ready' => 'has been uploaded and is ready',
            'failed' => 'upload failed',
        ];

        return [
            'lesson_id' => $this->lesson->id,
            'lesson_title' => $this->lesson->title,
            'course_id' => $this->lesson->courseModule->course->id,
            'course_title' => $this->lesson->courseModule->course->title,
            'status' => $this->status,
            'message' => "Your video lesson '{$this->lesson->title}' " . ($statusMessages[$this->status] ?? $this->status),
            'action_url' => route('instructor.courses.builder', $this->lesson->courseModule->course->slug),
            'type' => 'video_upload_status',
        ];
    }
}
