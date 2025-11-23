<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\InstructorRating;
use App\Models\User;
use Illuminate\Http\Request;

class InstructorRatingController extends Controller
{
    /**
     * Store a new instructor rating
     */
    public function store(Request $request, User $instructor)
    {
        $request->validate([
            'course_id' => 'nullable|exists:courses,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        if (!$user) {
            return redirect()->back()->with([
                'message' => 'Please login to rate instructors',
                'type' => 'error'
            ]);
        }

        // Check if user has completed a course with this instructor
        $hasCompletedCourse = false;
        if ($request->course_id) {
            $course = Course::find($request->course_id);
            if ($course && $course->instructor_id === $instructor->id) {
                $hasCompletedCourse = $course->students()->where('user_id', $user->id)->exists();
            }
        } else {
            // Check if user has completed any course with this instructor
            $hasCompletedCourse = Course::where('instructor_id', $instructor->id)
                ->whereHas('students', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->exists();
        }

        if (!$hasCompletedCourse) {
            return redirect()->back()->with([
                'message' => 'You can only rate instructors whose courses you have enrolled in',
                'type' => 'error'
            ]);
        }

        // Update or create rating
        InstructorRating::updateOrCreate(
            [
                'instructor_id' => $instructor->id,
                'user_id' => $user->id,
                'course_id' => $request->course_id,
            ],
            [
                'rating' => $request->rating,
                'review' => $request->review,
            ]
        );

        return redirect()->back()->with([
            'message' => 'Rating submitted successfully',
            'type' => 'success'
        ]);
    }

    /**
     * Get instructor ratings
     */
    public function index(User $instructor)
    {
        $ratings = $instructor->ratingsReceived()
            ->with(['user', 'course'])
            ->latest()
            ->paginate(10);

        $averageRating = $instructor->ratingsReceived()->avg('rating') ?? 0;
        $totalRatings = $instructor->ratingsReceived()->count();

        // Rating distribution
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $instructor->ratingsReceived()->where('rating', $i)->count();
            $percentage = $totalRatings > 0 ? ($count / $totalRatings) * 100 : 0;
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => round($percentage, 1),
            ];
        }

        return response()->json([
            'average_rating' => round($averageRating, 1),
            'total_ratings' => $totalRatings,
            'rating_distribution' => $ratingDistribution,
            'ratings' => $ratings,
        ]);
    }
}
