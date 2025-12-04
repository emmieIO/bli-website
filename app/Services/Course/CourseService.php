<?php

namespace App\Services\Course;

use App\Enums\ApplicationStatus;
use App\Models\Course;
use App\Models\CourseOutcome;
use App\Models\CourseRequirement;
use App\Services\VimeoService;
use App\Traits\HasFileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseService
{
    use HasFileUpload;

    protected VimeoService $vimeoService;

    /**
     * Create a new class instance.
     */
    public function __construct(VimeoService $vimeoService)
    {
        $this->vimeoService = $vimeoService;
    }

    public function createCourse(array $data, ?UploadedFile $thumbnail = null, ?UploadedFile $previewVideo = null)
    {
        try {
            return DB::transaction(function () use ($data, $thumbnail, $previewVideo) {
                $thumbnailPath = null;

                // Upload thumbnail
                if ($thumbnail) {
                    $thumbnailPath = $this->uploadFile($thumbnail, 'courses/thumbnails');
                }

                // Create course first
                $course = Course::create([
                    'title' => $data['title'],
                    'subtitle' => $data['subtitle'] ?? null,
                    'description' => $data['description'],
                    'language' => $data['language'],
                    'thumbnail_path' => $thumbnailPath,
                    'preview_video_id' => null, // Will be set by background job
                    'level' => $data['level'],
                    'category_id' => $data['category_id'],
                    'is_free' => (bool) $data['is_free'],
                    'price' => $data['is_free'] ? 0 : $data['price'],
                    'instructor_id' => auth()->id(),
                    'status' => ApplicationStatus::DRAFT->value,
                ]);

                // Upload preview video to Vimeo asynchronously if provided
                if ($previewVideo) {
                    $tempPath = $previewVideo->store('temp-videos', 'local');

                    $videoMetadata = [
                        'name' => $data['title'] . ' - Preview',
                        'description' => $data['subtitle'] ?? $data['description'] ?? 'Course preview video',
                    ];

                    $privacySettings = [
                        'view' => 'anybody',
                        'embed' => 'public',
                        'download' => false,
                        'add' => false,
                        'comments' => 'nobody',
                    ];

                    \App\Jobs\ProcessCoursePreviewVideoUpload::dispatch(
                        $course->id,
                        $tempPath,
                        $videoMetadata,
                        $privacySettings
                    );

                    Log::info('Course preview video upload job dispatched', [
                        'course_id' => $course->id,
                        'temp_path' => $tempPath,
                    ]);
                }

                return $course;
            });
        } catch (\Throwable $th) {
            Log::error("Error creating course", ['error' => $th->getMessage()]);
            throw $th;
        }
    }

    public function fetchCourses()
    {
        $userId = auth()->id();

        return Course::with(['category', 'instructor'])
            ->where(function ($query) use ($userId) {
                // Show courses created by the current user
                $query->where('instructor_id', $userId)
                    // OR courses submitted for approval (not draft)
                    ->orWhereIn('status', [
                        ApplicationStatus::PENDING->value,
                        ApplicationStatus::UNDER_REVIEW->value,
                        ApplicationStatus::APPROVED->value,
                        ApplicationStatus::REJECTED->value,
                    ]);
            })
            ->latest()
            ->get();
    }

    public function fetchInstructorCourses(int $instructorId)
    {
        return Course::where('instructor_id', $instructorId)->with(['category', 'students'])->latest()->paginate(10);
    }

    public function addRequirement(Course $course, string $requirement)
    {
        $order = $course->requirements()->max('order') + 1;
        return $course->requirements()->create([
            'requirement' => $requirement,
            'order' => $order
        ]);
    }

    public function removeRequirement(CourseRequirement $requirement, Course $course)
    {
        // check if the requirement belongs to the course
        if ($requirement->course_id !== $course->id) {
            return false;
        }

        return $course->requirements()->where('id', $requirement->id)->delete();
    }

    public function addOutcome(Course $course, string $outcome)
    {
        $order = $course->outcomes()->max('order') + 1;
        return $course->outcomes()->create([
            'outcome' => $outcome,
            'order' => $order
        ]);
    }

    public function removeOutcome(CourseOutcome $outcome, Course $course)
    {
        // check if the outcome belongs to the course
        if ($outcome->course_id !== $course->id) {
            return false;
        }

        return $course->outcomes()->where('id', $outcome->id)->delete();
    }

    public function updateCourse(Course $course, array $data, ?UploadedFile $thumbnailFile = null, ?UploadedFile $previewVideoFile = null)
    {
        try {
            return DB::transaction(function () use ($course, $data, $thumbnailFile, $previewVideoFile) {
                $updateData = [
                    'title' => $data['title'],
                    'subtitle' => $data['subtitle'] ?? null,
                    'description' => $data['description'] ?? null,
                    'language' => $data['language'] ?? 'English',
                    'level' => $data['level'],
                    'category_id' => $data['category_id'],
                    'is_free' => $data['is_free'] ?? false,
                    'price' => $data['price'] ?? 0,
                    'status' => $data['status'] ?? $course->status,
                ];

                // Handle thumbnail upload if provided
                if ($thumbnailFile) {
                    // Delete old thumbnail if exists
                    if ($course->thumbnail_path) {
                        $this->deleteFile($course->thumbnail_path);
                    }
                    $updateData['thumbnail_path'] = $this->uploadFile($thumbnailFile, 'courses/thumbnails');
                }

                // Handle preview video upload to Vimeo asynchronously if provided
                if ($previewVideoFile) {
                    // Delete old preview video from Vimeo if exists
                    if ($course->preview_video_id) {
                        $this->vimeoService->deleteVideo($course->preview_video_id);
                        $updateData['preview_video_id'] = null; // Will be set by background job
                    }

                    // Store temporarily and dispatch background job
                    $tempPath = $previewVideoFile->store('temp-videos', 'local');

                    $videoMetadata = [
                        'name' => $data['title'] . ' - Preview',
                        'description' => $data['subtitle'] ?? $data['description'] ?? 'Course preview video',
                    ];

                    $privacySettings = [
                        'view' => 'anybody',
                        'embed' => 'public',
                        'download' => false,
                        'add' => false,
                        'comments' => 'nobody',
                    ];

                    // Update course first
                    $course->update($updateData);

                    // Dispatch background job
                    \App\Jobs\ProcessCoursePreviewVideoUpload::dispatch(
                        $course->id,
                        $tempPath,
                        $videoMetadata,
                        $privacySettings
                    );

                    Log::info('Course preview video update job dispatched', [
                        'course_id' => $course->id,
                        'temp_path' => $tempPath,
                    ]);

                    return $course;
                } elseif ($course->preview_video_id) {
                    // Update existing Vimeo video metadata if title or description changed
                    $titleChanged = $course->title !== $data['title'];
                    $descriptionChanged = $course->subtitle !== ($data['subtitle'] ?? null) ||
                                         $course->description !== ($data['description'] ?? null);

                    if ($titleChanged || $descriptionChanged) {
                        $videoMetadata = [
                            'name' => $data['title'] . ' - Preview',
                            'description' => $data['subtitle'] ?? $data['description'] ?? 'Course preview video',
                        ];

                        $this->vimeoService->updateVideoMetadata($course->preview_video_id, $videoMetadata);
                    }
                }

                $course->update($updateData);
                return $course;
            });
        } catch (\Throwable $th) {
            Log::error("Error updating course", ['error' => $th->getMessage()]);
            throw $th;
        }
    }

    public function deleteCourse(Course $course): bool
    {
        try {
            return DB::transaction(function () use ($course) {
                // Load relationships to access files before deletion
                $course->load('modules.lessons');

                // Delete course files
                if ($course->thumbnail_path) {
                    $this->deleteFile($course->thumbnail_path);
                }

                // Delete preview video from Vimeo
                if ($course->preview_video_id) {
                    $this->vimeoService->deleteVideo($course->preview_video_id);
                }

                // Delete lesson content files and Vimeo videos
                $vimeoDeletedCount = 0;
                $vimeoTotalCount = 0;

                foreach ($course->modules as $module) {
                    foreach ($module->lessons as $lesson) {
                        // Delete Vimeo video if it exists
                        if ($lesson->type === 'video' && $lesson->vimeo_id) {
                            $vimeoTotalCount++;
                            Log::info("Attempting to delete Vimeo video from course deletion", [
                                'course_id' => $course->id,
                                'lesson_id' => $lesson->id,
                                'vimeo_id' => $lesson->vimeo_id
                            ]);

                            if ($this->vimeoService->deleteVideo($lesson->vimeo_id)) {
                                $vimeoDeletedCount++;
                            }
                        }

                        // Delete lesson content files (PDFs, etc)
                        if ($lesson->content_path && $lesson->type !== 'video') {
                            $this->deleteFile($lesson->content_path);
                        }
                    }
                }

                if ($vimeoTotalCount > 0) {
                    Log::info("Deleted Vimeo videos from course", [
                        'course_id' => $course->id,
                        'videos_deleted' => $vimeoDeletedCount,
                        'total_videos' => $vimeoTotalCount
                    ]);
                }

                // Delete related records
                $course->requirements()->delete();
                $course->outcomes()->delete();

                // Detach enrolled students
                $course->students()->detach();

                // Delete the course (database cascade will handle modules, lessons, and lesson_progress)
                $course->delete();

                return true;
            });
        } catch (\Throwable $th) {
            Log::error("Error deleting course", [
                'course_id' => $course->id,
                'error' => $th->getMessage()
            ]);
            throw $th;
        }
    }
}
