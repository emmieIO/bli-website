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

        // Store video temporarily
        $tempPath = $videoFile->store('temp-videos', 'local');

        // Create lesson with pending status first
        $lesson = $module->lessons()->create([
            'title' => $data['title'],
            'type' => 'video',
            'description' => $data['description'] ?? null,
            'order' => $this->getNextOrder($module),
            'video_status' => 'pending',
            'content_path' => null,
            'vimeo_id' => null,
            'video_uploaded_at' => null,
        ]);

        // Dispatch background job for Vimeo upload
        \App\Jobs\ProcessVideoUpload::dispatch(
            $lesson->id,
            $tempPath,
            $data['title'],
            $data['description'] ?? null
        );

        Log::info('Video upload job dispatched from LessonService', [
            'lesson_id' => $lesson->id,
            'temp_path' => $tempPath,
        ]);

        return $lesson->fresh();
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

                    // Store video temporarily
                    $tempPath = $videoFile->store('temp-videos', 'local');

                    // Update lesson to pending status
                    $lesson->update([
                        'video_status' => 'pending',
                        'vimeo_id' => null,
                        'content_path' => null,
                        'video_uploaded_at' => null,
                    ]);

                    // Dispatch background job for Vimeo upload
                    \App\Jobs\ProcessVideoUpload::dispatch(
                        $lesson->id,
                        $tempPath,
                        $lesson->title,
                        $lesson->description ?? null
                    );

                    Log::info('Video upload job dispatched from LessonService (update)', [
                        'lesson_id' => $lesson->id,
                        'temp_path' => $tempPath,
                    ]);
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
