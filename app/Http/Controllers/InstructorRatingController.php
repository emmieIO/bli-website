<?php

namespace App\Http\Controllers;

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

        // Update or create rating
        InstructorRating::updateOrCreate(
            [
                'instructor_id' => $instructor->id,
                'user_id' => $user->id,
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
            ->with(['user'])
            ->latest()
            ->paginate(10);

        $averageRating = $instructor->ratingsReceived()->avg('rating') ?? 0;
        $totalRatings = $instructor->ratingsReceived()->count();

        return \Inertia\Inertia::render('Instructors/Ratings', [
            'instructor' => $instructor,
            'ratings' => $ratings,
            'averageRating' => round($averageRating, 1),
            'totalRatings' => $totalRatings,
        ]);
    }
}
