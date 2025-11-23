<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Event;
use App\Models\Speaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Global search across events, courses, and speakers
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        // Minimum search length
        if (strlen($query) < 2) {
            return response()->json([
                'events' => [],
                'courses' => [],
                'speakers' => [],
            ]);
        }

        // Search Events
        $events = Event::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('theme', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get(['id', 'slug', 'title', 'theme', 'start_date', 'mode', 'location'])
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'slug' => $event->slug,
                    'title' => $event->title,
                    'subtitle' => $event->theme,
                    'meta' => $event->start_date?->format('M d, Y') . ' • ' . ucfirst($event->mode ?? 'TBD'),
                    'type' => 'event',
                ];
            });

        // Search Courses
        $courses = Course::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->with(['instructor:id,name', 'category:id,name'])
            ->limit(5)
            ->get(['id', 'slug', 'title', 'description', 'instructor_id', 'category_id', 'thumbnail_path'])
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'slug' => $course->slug,
                    'title' => $course->title,
                    'subtitle' => $course->instructor->name ?? 'Unknown Instructor',
                    'meta' => $course->category->name ?? 'Uncategorized',
                    'thumbnail' => $course->thumbnail_path,
                    'type' => 'course',
                ];
            });

        // Search Speakers
        $speakers = Speaker::where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('bio', 'LIKE', "%{$query}%")
                    ->orWhere('expertise', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get(['id', 'slug', 'name', 'bio', 'expertise', 'profile_picture'])
            ->map(function ($speaker) {
                return [
                    'id' => $speaker->id,
                    'slug' => $speaker->slug,
                    'title' => $speaker->name,
                    'subtitle' => $speaker->expertise,
                    'meta' => strlen($speaker->bio ?? '') > 60
                        ? substr($speaker->bio, 0, 60) . '...'
                        : $speaker->bio,
                    'thumbnail' => $speaker->profile_picture,
                    'type' => 'speaker',
                ];
            });

        return response()->json([
            'events' => $events,
            'courses' => $courses,
            'speakers' => $speakers,
        ]);
    }
}
