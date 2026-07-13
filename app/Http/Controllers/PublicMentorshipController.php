<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use App\Models\InstructorProfile;
use Inertia\Inertia;
use Inertia\Response;

class PublicMentorshipController extends Controller
{
    public function index(): Response
    {
        $mentors = InstructorProfile::query()
            ->where('is_approved', true)
            ->whereHas('user', fn ($query) => $query->role(UserRoles::MENTOR->value))
            ->with(['user' => fn ($query) => $query
                ->select(['id', 'name', 'headline', 'photo'])
                ->withAvg('ratingsReceived', 'rating')
                ->withCount('ratingsReceived')])
            ->latest('approved_at')
            ->get()
            ->map(fn (InstructorProfile $profile) => [
                'id' => $profile->id,
                'user_id' => $profile->user_id,
                'name' => $profile->user->name,
                'headline' => $profile->user->headline,
                'photo' => $profile->user->photo,
                'bio' => $profile->bio,
                'area_of_expertise' => $profile->area_of_expertise,
                'experience_years' => $profile->experience_years,
                'average_rating' => round((float) ($profile->user->ratings_received_avg_rating ?? 0), 1),
                'ratings_count' => (int) ($profile->user->ratings_received_count ?? 0),
            ]);

        return Inertia::render('Mentorship/PublicIndex', [
            'mentors' => $mentors,
        ]);
    }
}
