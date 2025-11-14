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
                    'order' => $order,
                ]);
                return $module;
            });
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            throw $th;
        }
    }

    public function createModuleLesson(CourseModule $module, array $data, ?UploadedFile $file = null, ?UploadedFile $videoFile = null)
    {
        try {
            $order = $module->lessons()->max('order') + 1;
            DB::transaction(function () use ($module, $data, $order, $file, $videoFile) {
                return match ($data['type']) {
                    'pdf' => $this->savePdf($module, $data, $file),
                    'video' => $this->saveVideo($module, $data, $videoFile),
                    'link' => $this->saveLink($module, $data),
                    default => throw new \Exception('Invalid lesson type'),
                };
            });
            return true;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
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
        $vimeo = $this->vimeoService->getClient();
        // Upload the video to Vimeo
        $uri = $vimeo->upload($file->getRealPath(), [
            'name' => $data['title'],
            'description' => $data['description'] ?? '',

        ]);

        $videoId = last(explode('/', $uri));

        $module->lessons()->create([
            'title' => $data['title'],
            'type' => 'video',
            'description' => $data['description'] ?? null,
            'vimeo_id' => $videoId,
            'content_path' => $uri,
            'order' => $module->lessons()->max('order') + 1,
        ]);
    }

}
