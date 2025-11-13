<?php

namespace App\Services\Instructors;

class InstructorStatsService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function getInstructorStats(int $instructorId)
    {
        // Implement logic to gather stats like:
        // - Total courses
        // - Total students enrolled
        // - Total earnings
        // - Average rating
        $courseIds = \DB::table('courses')
            ->where('instructor_id', $instructorId)
            ->pluck('id')
            ->toArray();

        if (empty($courseIds)) {
            return [
                'total_courses'   => 0,
                'total_students'  => 0,
                'total_earnings'  => 0.0,
                'average_rating'  => 0.0,
            ];
        }

        $totalCourses = count($courseIds);

        $totalStudents = \DB::table('enrollments')
            ->whereIn('course_id', $courseIds)
            ->distinct()
            ->count('student_id');

        $totalEarnings = 0.0;
        // (float) \DB::table('payments')
        //     ->whereIn('course_id', $courseIds)
        //     ->sum('amount');

        $averageRating =5.0;
        //  \DB::table('reviews')
        //     ->whereIn('course_id', $courseIds)
        //     ->avg('rating');

        return [
            'total_courses'  => $totalCourses,
            'total_students' => $totalStudents,
            'total_earnings' => $totalEarnings,
            'average_rating' => $averageRating ? round((float) $averageRating, 2) : 0.0,
        ];
    }
}
