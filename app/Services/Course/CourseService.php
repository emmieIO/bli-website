<?php

namespace App\Services\Course;

use App\Enums\ApplicationStatus;
use App\Models\Course;
use App\Models\CourseOutcome;
use App\Models\CourseRequirement;
use App\Traits\HasFileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseService
{
    use HasFileUpload;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createCourse(array $data, UploadedFile $thumbnail = null, UploadedFile $previewVideo = null)
    {
        try {
            return DB::transaction(function () use ($data, $thumbnail, $previewVideo) {
                $thumbnailPath = null;
                $previewVideoId = null;

                // Upload thumbnail
                if ($thumbnail) {
                    $thumbnailPath = $this->uploadFile($thumbnail, 'courses/thumbnails');
                }

                // Upload preview video
                if ($previewVideo) {
                    $previewVideoId = $this->uploadFile($previewVideo, 'courses/videos');
                }

                $course = Course::create([
                    'title' => $data['title'],
                    'subtitle' => $data['subtitle'] ?? null,
                    'description' => $data['description'],
                    'language' => $data['language'],
                    'thumbnail_path' => $thumbnailPath,
                    'preview_video_id' => $previewVideoId,
                    'level' => $data['level'],
                    'category_id' => $data['category_id'],
                    'is_free' => (bool) $data['is_free'],
                    'price' => $data['is_free'] ? 0 : $data['price'],
                    'instructor_id' => auth()->id(),
                    'status' => ApplicationStatus::DRAFT->value,
                ]);
                
                return $course;
            });
        } catch (\Throwable $th) {
            Log::error("Error creating course", ['error' => $th->getMessage()]);
            
            // Clean up uploaded files on error
            if (!empty($thumbnailPath)) {
                $this->deleteFile($thumbnailPath);
            }
            if (!empty($previewVideoId)) {
                $this->deleteFile($previewVideoId);
            }
            
            throw $th;
        }
    }

    public function fetchCourses()
    {
        return Course::with(['category', 'instructor'])->latest()->paginate(10);
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

    public function updateCourse(Course $course, array $data, UploadedFile $file = null)
    {
        try {
            return DB::transaction(function () use ($course, $data, $file) {
                $updateData = [
                    'title' => $data['title'],
                    'description' => $data['description'] ?? null,
                    'level' => $data['level'],
                    'category_id' => $data['category_id'],
                    'price' => $data['price'],
                    'status' => $data['status'] ?? $course->status,
                ];

                // Handle file upload if provided
                if ($file) {
                    // Delete old thumbnail if exists
                    if ($course->thumbnail_path) {
                        $this->deleteFile($course->thumbnail_path);
                    }
                    $updateData['thumbnail_path'] = $this->uploadFile($file, 'courses');
                }

                $course->update($updateData);
                return $course;
            });
        } catch (\Throwable $th) {
            Log::error("Error updating course", ['error' => $th->getMessage()]);
            throw $th;
        }
    }
}
