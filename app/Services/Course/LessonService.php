<?php

namespace App\Services\Course;

use App\Models\CourseModule;
use App\Models\Lesson;
use App\Services\VimeoService;
use App\Traits\HasFileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LessonService
{
    use HasFileUpload;

    public function __construct(
        protected VimeoService $vimeoService
    ) {}

    /**
     * Create a new lesson for a module
     *
     * @param CourseModule $module
     * @param array $data
     * @param UploadedFile|null $contentFile
     * @param UploadedFile|null $videoFile
     * @return Lesson
     * @throws \Exception
     */
    public function createLesson(
        CourseModule $module,
        array $data,
        ?UploadedFile $contentFile = null,
        ?UploadedFile $videoFile = null
    ): Lesson {
        try {
            // Route to appropriate handler based on lesson type
            return match ($data['type']) {
                'video' => $this->createVideoLesson($module, $data, $videoFile),
                'pdf' => $this->createPdfLesson($module, $data, $contentFile),
                'link' => $this->createLinkLesson($module, $data),
                default => throw new \Exception('Invalid lesson type: ' . ($data['type'] ?? 'unknown')),
            };
        } catch (\Throwable $e) {
            Log::error('Error creating lesson', [
                'module_id' => $module->id,
                'type' => $data['type'] ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Create a video lesson (uploads to Vimeo)
     *
     * @param CourseModule $module
     * @param array $data
     * @param UploadedFile|null $videoFile
     * @return Lesson
     * @throws \Exception
     */
    protected function createVideoLesson(
        CourseModule $module,
        array $data,
        ?UploadedFile $videoFile
    ): Lesson {
        if (!$videoFile) {
            throw new \Exception('Video file is required for video lessons');
        }

        // Create lesson with uploading status first
        $lesson = $module->lessons()->create([
            'title' => $data['title'],
            'type' => 'video',
            'description' => $data['description'] ?? null,
            'order' => $this->getNextOrder($module),
            'video_status' => 'uploading',
        ]);

        try {
            // Upload video to Vimeo (outside of transaction)
            $uploadResult = $this->vimeoService->uploadVideo(
                $videoFile->getRealPath(),
                [
                    'name' => $data['title'],
                    'description' => $data['description'] ?? '',
                ]
            );

            if (!$uploadResult['success']) {
                // Upload failed
                $lesson->update([
                    'video_status' => 'failed',
                    'video_error' => $uploadResult['error'],
                ]);

                throw new \Exception($uploadResult['error']);
            }

            // Upload successful
            $lesson->update([
                'vimeo_id' => $uploadResult['video_id'],
                'content_path' => $uploadResult['uri'],
                'video_status' => 'processing',
                'video_uploaded_at' => now(),
            ]);

            Log::info('Video lesson uploaded successfully', [
                'lesson_id' => $lesson->id,
                'vimeo_id' => $uploadResult['video_id'],
            ]);

            return $lesson->fresh();

        } catch (\Exception $e) {
            // Mark lesson as failed
            $lesson->update([
                'video_status' => 'failed',
                'video_error' => $e->getMessage(),
            ]);

            Log::error('Failed to create video lesson', [
                'lesson_id' => $lesson->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Create a PDF lesson
     *
     * @param CourseModule $module
     * @param array $data
     * @param UploadedFile|null $pdfFile
     * @return Lesson
     * @throws \Exception
     */
    protected function createPdfLesson(
        CourseModule $module,
        array $data,
        ?UploadedFile $pdfFile
    ): Lesson {
        if (!$pdfFile) {
            throw new \Exception('PDF file is required for PDF lessons');
        }

        return DB::transaction(function () use ($module, $data, $pdfFile) {
            $filePath = $this->uploadFile($pdfFile, 'course_lessons/pdfs');

            return $module->lessons()->create([
                'title' => $data['title'],
                'type' => 'pdf',
                'description' => $data['description'] ?? null,
                'content_path' => $filePath,
                'order' => $this->getNextOrder($module),
            ]);
        });
    }

    /**
     * Create a link lesson
     *
     * @param CourseModule $module
     * @param array $data
     * @return Lesson
     * @throws \Exception
     */
    protected function createLinkLesson(CourseModule $module, array $data): Lesson
    {
        if (empty($data['link_url'])) {
            throw new \Exception('Link URL is required for link lessons');
        }

        return DB::transaction(function () use ($module, $data) {
            return $module->lessons()->create([
                'title' => $data['title'],
                'type' => 'link',
                'description' => $data['description'] ?? null,
                'content_path' => $data['link_url'],
                'order' => $this->getNextOrder($module),
            ]);
        });
    }

    /**
     * Update an existing lesson
     *
     * @param Lesson $lesson
     * @param array $data
     * @param UploadedFile|null $contentFile
     * @param UploadedFile|null $videoFile
     * @return Lesson
     * @throws \Exception
     */
    public function updateLesson(
        Lesson $lesson,
        array $data,
        ?UploadedFile $contentFile = null,
        ?UploadedFile $videoFile = null
    ): Lesson {
        try {
            return DB::transaction(function () use ($lesson, $data, $contentFile, $videoFile) {
                // Update basic fields
                $lesson->update([
                    'title' => $data['title'] ?? $lesson->title,
                    'description' => $data['description'] ?? $lesson->description,
                ]);

                // Handle file updates based on type
                if ($lesson->type === 'pdf' && $contentFile) {
                    // Delete old file
                    if ($lesson->content_path) {
                        $this->deleteFile($lesson->content_path);
                    }

                    // Upload new file
                    $lesson->content_path = $this->uploadFile($contentFile, 'course_lessons/pdfs');
                    $lesson->save();
                }

                if ($lesson->type === 'video' && $videoFile) {
                    // Delete old video from Vimeo
                    if ($lesson->vimeo_id) {
                        $this->vimeoService->deleteVideo($lesson->vimeo_id);
                    }

                    // Upload new video (this should be done async in production)
                    $lesson->video_status = 'uploading';
                    $lesson->save();

                    // Upload to Vimeo
                    $uploadResult = $this->vimeoService->uploadVideo(
                        $videoFile->getRealPath(),
                        [
                            'name' => $lesson->title,
                            'description' => $lesson->description ?? '',
                        ]
                    );

                    if ($uploadResult['success']) {
                        $lesson->update([
                            'vimeo_id' => $uploadResult['video_id'],
                            'content_path' => $uploadResult['uri'],
                            'video_status' => 'processing',
                            'video_uploaded_at' => now(),
                        ]);
                    } else {
                        $lesson->update([
                            'video_status' => 'failed',
                            'video_error' => $uploadResult['error'],
                        ]);
                    }
                }

                if ($lesson->type === 'link' && isset($data['link_url'])) {
                    $lesson->content_path = $data['link_url'];
                    $lesson->save();
                }

                return $lesson->fresh();
            });
        } catch (\Throwable $e) {
            Log::error('Error updating lesson', [
                'lesson_id' => $lesson->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Delete a lesson
     *
     * @param Lesson $lesson
     * @return bool
     * @throws \Exception
     */
    public function deleteLesson(Lesson $lesson): bool
    {
        try {
            return DB::transaction(function () use ($lesson) {
                // Delete associated files
                if ($lesson->type === 'pdf' && $lesson->content_path) {
                    $this->deleteFile($lesson->content_path);
                }

                // Delete Vimeo video
                if ($lesson->type === 'video' && $lesson->vimeo_id) {
                    $this->vimeoService->deleteVideo($lesson->vimeo_id);
                }

                return $lesson->delete();
            });
        } catch (\Throwable $e) {
            Log::error('Error deleting lesson', [
                'lesson_id' => $lesson->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Reorder lessons within a module
     *
     * @param CourseModule $module
     * @param array $lessonOrder Array of lesson IDs in desired order
     * @return bool
     */
    public function reorderLessons(CourseModule $module, array $lessonOrder): bool
    {
        try {
            return DB::transaction(function () use ($module, $lessonOrder) {
                foreach ($lessonOrder as $index => $lessonId) {
                    $module->lessons()
                        ->where('id', $lessonId)
                        ->update(['order' => $index + 1]);
                }

                return true;
            });
        } catch (\Throwable $e) {
            Log::error('Error reordering lessons', [
                'module_id' => $module->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get the next order number for a lesson in a module
     *
     * @param CourseModule $module
     * @return int
     */
    protected function getNextOrder(CourseModule $module): int
    {
        return ($module->lessons()->max('order') ?? 0) + 1;
    }
}
