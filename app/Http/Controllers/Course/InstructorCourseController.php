<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Services\Course\CourseCategoryService;
use App\Services\Course\CourseService;
use App\Services\Instructors\InstructorStatsService;
use Illuminate\Http\Request;
use TusPhp\Middleware\Cors;

class InstructorCourseController extends Controller
{
    public function __construct(
        public CourseService $courseService,
        public InstructorStatsService $instructorStatsService,
        public CourseCategoryService $courseCategoryService,
    ) {
    }
    public function index()
    {
        $instructorId = auth()->user()->id;
        // courses count
        // total students enrolled
        // total earnings
        //  Average rating
        $courses = $this->courseService->fetchInstructorCourses($instructorId);
        $instructorStats = $this->instructorStatsService->getInstructorStats($instructorId);
        return view("instructors.courses.index", compact("courses", "instructorStats"));
    }

    public function create()
    {
        $categories = $this->courseCategoryService->fetchAll();
        return view("instructors.courses.create-course", compact("categories"));
    }
}
