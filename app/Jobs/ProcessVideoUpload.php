<?php

namespace App\Jobs;

use App\Models\Lesson;
use App\Services\VimeoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessVideoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hour timeout for large videos
    public $tries = 3; // Retry up to 3 times on failure
    public $backoff = [60, 300, 900]; // Retry after 1min, 5min, 15min

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $lessonId,
        public string $tempVideoPath,
        public string $videoTitle,
        public ?string $videoDescription = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(VimeoService $vimeoService): void
    {
        $lesson = Lesson::find($this->lessonId);

        if (!$lesson) {
            Log::error('Lesson not found for video upload', ['lesson_id' => $this->lessonId]);
            $this->cleanupTempFile();
            return;
        }

        try {
            Log::info('Starting background Vimeo upload', [
                'lesson_id' => $this->lessonId,
                'temp_path' => $this->tempVideoPath,
                'attempt' => $this->attempts(),
            ]);

            // Update status to uploading
            $lesson->update([
                'video_status' => 'uploading',
            ]);

            // Get the full path to the temp file
            $fullPath = Storage::disk('local')->path($this->tempVideoPath);

            if (!file_exists($fullPath)) {
                throw new \Exception("Temporary video file not found: {$fullPath}");
            }

            // Upload to Vimeo
            $result = $vimeoService->uploadVideo(
                $fullPath,
                [
                    'name' => $this->videoTitle,
                    'description' => $this->videoDescription ?? '',
                ]
            );

            if ($result['success']) {
                // Update lesson with Vimeo details
                $lesson->update([
                    'vimeo_id' => $result['video_id'],
                    'content_path' => $result['uri'],
                    'video_status' => 'processing',
                    'video_uploaded_at' => now(),
                ]);

                // Check if video is ready
                $statusCheck = $vimeoService->getVideoStatus($result['video_id']);
                if ($statusCheck['is_ready']) {
                    $lesson->update(['video_status' => 'ready']);
                }

                Log::info('Vimeo upload completed successfully', [
                    'lesson_id' => $this->lessonId,
                    'vimeo_id' => $result['video_id'],
                    'status' => $lesson->video_status,
                ]);

                // Notify instructor when video is ready
                if ($lesson->video_status === 'ready') {
                    $lesson->load('courseModule.course.instructor');
                    $instructor = $lesson->courseModule?->course?->instructor;
                    if ($instructor) {
                        $instructor->notify(new \App\Notifications\VideoUploadStatusNotification($lesson, 'ready'));
                    }
                }
            } else {
                throw new \Exception($result['error'] ?? 'Unknown upload error');
            }

            // Cleanup temp file
            $this->cleanupTempFile();

        } catch (\Exception $e) {
            Log::error('Failed to upload video to Vimeo', [
                'lesson_id' => $this->lessonId,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

            // Update lesson status to failed
            $lesson->update([
                'video_status' => 'failed',
            ]);

            // Cleanup temp file on final failure
            if ($this->attempts() >= $this->tries) {
                $this->cleanupTempFile();
            }

            // Re-throw to trigger job retry
            throw $e;
        }
    }

    /**
     * Clean up temporary video file
     */
    protected function cleanupTempFile(): void
    {
        try {
            if (Storage::disk('local')->exists($this->tempVideoPath)) {
                Storage::disk('local')->delete($this->tempVideoPath);
                Log::info('Temporary video file deleted', ['path' => $this->tempVideoPath]);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to delete temporary video file', [
                'path' => $this->tempVideoPath,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Video upload job failed permanently', [
            'lesson_id' => $this->lessonId,
            'temp_path' => $this->tempVideoPath,
            'error' => $exception->getMessage(),
        ]);

        // Update lesson to failed status
        $lesson = Lesson::find($this->lessonId);
        if ($lesson) {
            $lesson->update([
                'video_status' => 'failed',
                'video_error' => $exception->getMessage(),
            ]);

            // Notify instructor about the failure
            $lesson->load('courseModule.course.instructor');
            $instructor = $lesson->courseModule?->course?->instructor;
            if ($instructor) {
                $instructor->notify(new \App\Notifications\VideoUploadStatusNotification($lesson, 'failed'));
            }
        }

        // Cleanup temp file
        $this->cleanupTempFile();
    }
}
