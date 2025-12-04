<?php

namespace App\Services\Course;

use App\Models\Course;
use App\Models\CourseModule;
use App\Services\VimeoService;
use App\Traits\HasFileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseModuleService
{
    use HasFileUpload;
    /**
     * Create a new class instance.
     */
    public function __construct(public VimeoService $vimeoService)
    {
        //
    }

    public function createModule(Course $course, array $data)
    {
        try {
            $order = $course->modules()->max('order') + 1;
            return DB::transaction(function () use ($course, $data, $order) {
                $module = CourseModule::create([
                    'course_id' => $course->id,
                    'title' => $data['title'],
                    'description' => $data['description'] ?? null,
                    'order' => $order,
                ]);
                return $module;
            });
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            throw $th;
        }
    }

    public function updateModule(CourseModule $module, array $data)
    {
        try {
            return DB::transaction(function () use ($module, $data) {
                $module->update([
                    'title' => $data['title'] ?? $module->title,
                    'description' => $data['description'] ?? $module->description,
                ]);
                return $module;
            });
        } catch (\Throwable $th) {
            Log::error('Error updating module', [
                'module_id' => $module->id,
                'error' => $th->getMessage(),
            ]);
            throw $th;
        }
    }

    public function deleteModule(CourseModule $module)
    {
        try {
            return DB::transaction(function () use ($module) {
                // Delete all lessons in the module
                foreach ($module->lessons as $lesson) {
                    // Delete associated files
                    if ($lesson->type === 'pdf' && $lesson->content_path) {
                        $this->deleteFile($lesson->content_path);
                    }

                    // Delete Vimeo video
                    if ($lesson->type === 'video' && $lesson->vimeo_id) {
                        $this->vimeoService->deleteVideo($lesson->vimeo_id);
                    }

                    $lesson->delete();
                }

                // Delete the module
                return $module->delete();
            });
        } catch (\Throwable $th) {
            Log::error('Error deleting module', [
                'module_id' => $module->id,
                'error' => $th->getMessage(),
            ]);
            throw $th;
        }
    }

    public function createModuleLesson(CourseModule $module, array $data, ?UploadedFile $file = null, ?UploadedFile $videoFile = null)
    {
        try {
            // For video uploads, don't use transaction as upload happens outside
            if ($data['type'] === 'video') {
                return $this->saveVideo($module, $data, $videoFile);
            }

            // For other types (pdf, link), use transaction
            $result = DB::transaction(function () use ($module, $data, $file) {
                return match ($data['type']) {
                    'pdf' => $this->savePdf($module, $data, $file),
                    'link' => $this->saveLink($module, $data),
                    default => throw new \Exception('Invalid lesson type'),
                };
            });

            return $result;
        } catch (\Throwable $th) {
            Log::error("Error creating module lesson", [
                'module_id' => $module->id,
                'type' => $data['type'] ?? 'unknown',
                'error' => $th->getMessage(),
            ]);
            throw $th;
        }
    }

    public function savePdf(CourseModule $module, array $data, UploadedFile $file)
    {
        
        $module->lessons()->create([
            'title' => $data['title'],
            'type' => 'pdf',
            'description' => $data['description'] ?? null,
            'content_path' => $this->uploadFile($file, 'course_lessons/pdfs'),
            'order' => $module->lessons()->max('order') + 1,
        ]);
    }

    public function saveLink(CourseModule $module, array $data)
    {
        $module->lessons()->create([
            'title' => $data['title'],
            'type' => 'link',
            'description' => $data['description'] ?? null,
            'content_path' => $data['link_url'],
            'order' => $module->lessons()->max('order') + 1,
        ]);
    }

    public function saveVideo(CourseModule $module, array $data, UploadedFile $file)
    {
        // Store video temporarily
        $tempPath = $file->store('temp-videos', 'local');

        // Create lesson with pending status first
        $lesson = $module->lessons()->create([
            'title' => $data['title'],
            'type' => 'video',
            'description' => $data['description'] ?? null,
            'order' => $module->lessons()->max('order') + 1,
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

        Log::info('Video upload job dispatched from CourseModuleService', [
            'lesson_id' => $lesson->id,
            'temp_path' => $tempPath,
        ]);

        return $lesson;
    }

}
