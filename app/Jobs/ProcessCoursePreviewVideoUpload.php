<?php

namespace App\Jobs;

use App\Models\Course;
use App\Services\VimeoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessCoursePreviewVideoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hour timeout for large videos
    public $tries = 3; // Retry up to 3 times on failure
    public $backoff = [60, 300, 900]; // Retry after 1min, 5min, 15min

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $courseId,
        public string $tempVideoPath,
        public array $videoMetadata,
        public array $privacySettings
    ) {}

    /**
     * Execute the job.
     */
    public function handle(VimeoService $vimeoService): void
    {
        $course = Course::find($this->courseId);

        if (!$course) {
            Log::error('Course not found for preview video upload', ['course_id' => $this->courseId]);
            $this->cleanupTempFile();
            return;
        }

        try {
            Log::info('Starting background course preview video upload', [
                'course_id' => $this->courseId,
                'temp_path' => $this->tempVideoPath,
                'attempt' => $this->attempts(),
            ]);

            // Get the full path to the temp file
            $fullPath = Storage::disk('local')->path($this->tempVideoPath);

            if (!file_exists($fullPath)) {
                throw new \Exception("Temporary video file not found: {$fullPath}");
            }

            // Upload to Vimeo
            $result = $vimeoService->uploadVideo(
                $fullPath,
                $this->videoMetadata,
                $this->privacySettings
            );

            if ($result['success']) {
                // Update course with Vimeo video ID
                $course->update([
                    'preview_video_id' => $result['video_id'],
                ]);

                Log::info('Course preview video upload completed successfully', [
                    'course_id' => $this->courseId,
                    'vimeo_id' => $result['video_id'],
                ]);

                // Notify instructor
                $instructor = $course->instructor;
                if ($instructor) {
                    $instructor->notify(new \App\Notifications\CoursePreviewVideoStatusNotification($course, 'ready'));
                }
            } else {
                throw new \Exception($result['error'] ?? 'Unknown upload error');
            }

            // Cleanup temp file
            $this->cleanupTempFile();

        } catch (\Exception $e) {
            Log::error('Failed to upload course preview video to Vimeo', [
                'course_id' => $this->courseId,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

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
                Log::info('Temporary preview video file deleted', ['path' => $this->tempVideoPath]);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to delete temporary preview video file', [
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
        Log::error('Course preview video upload job failed permanently', [
            'course_id' => $this->courseId,
            'temp_path' => $this->tempVideoPath,
            'error' => $exception->getMessage(),
        ]);

        // Notify instructor about the failure
        $course = Course::find($this->courseId);
        if ($course && $course->instructor) {
            $course->instructor->notify(new \App\Notifications\CoursePreviewVideoStatusNotification($course, 'failed'));
        }

        // Cleanup temp file
        $this->cleanupTempFile();
    }
}
