<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstructorRating;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstructorRatingController extends Controller
{
    /**
     * Display a listing of all instructor ratings
     */
    public function index(Request $request)
    {
        $query = InstructorRating::with(['instructor', 'user', 'course']);

        // Filter by instructor if provided
        if ($request->instructor_id) {
            $query->where('instructor_id', $request->instructor_id);
        }

        // Filter by rating if provided
        if ($request->rating) {
            $query->where('rating', $request->rating);
        }

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->whereHas('instructor', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('review', 'like', "%{$search}%");
        }

        $ratings = $query->latest()->paginate(20);

        // Get all instructors for filter dropdown
        $instructors = User::role('instructor')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Statistics
        $stats = [
            'total' => InstructorRating::count(),
            'average_rating' => round(InstructorRating::avg('rating') ?? 0, 1),
            'five_star' => InstructorRating::where('rating', 5)->count(),
            'one_star' => InstructorRating::where('rating', 1)->count(),
        ];

        return Inertia::render('Admin/InstructorRatings/Index', [
            'ratings' => $ratings,
            'instructors' => $instructors,
            'stats' => $stats,
            'filters' => $request->only(['instructor_id', 'rating', 'search']),
        ]);
    }

    /**
     * Remove a rating
     */
    public function destroy(InstructorRating $rating)
    {
        $rating->delete();

        return redirect()->route('admin.ratings.index')
            ->with('success', 'Rating removed successfully.');
    }

    /**
     * View detailed rating
     */
    public function show(InstructorRating $rating)
    {
        $rating->load(['instructor', 'user', 'course']);

        return Inertia::render('Admin/InstructorRatings/Show', [
            'rating' => $rating,
        ]);
    }
}
