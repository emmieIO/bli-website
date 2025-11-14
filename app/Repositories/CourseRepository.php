<?php

namespace App\Repositories;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    public function find(int $id): ?Course
    {
        return Course::find($id);
    }

    public function findBySlug(string $slug): ?Course
    {
        return Course::where('slug', $slug)->first();
    }

    public function getWithRelations(int $id, array $relations = []): ?Course
    {
        return Course::with($relations)->find($id);
    }

    public function getUserEnrolledCourses(User $user): Collection
    {
        return $user->courseEnrollments()
            ->with(['instructor', 'modules.lessons'])
            ->get();
    }

    public function isUserEnrolled(User $user, Course $course): bool
    {
        return $course->students()->where('user_id', $user->id)->exists();
    }

    public function enrollUser(User $user, Course $course): void
    {
        if (!$this->isUserEnrolled($user, $course)) {
            $course->students()->attach($user->id, ['enrolled_at' => now()]);
        }
    }

    public function getPublishedCourses(): Collection
    {
        return Course::where('status', 'published')
            ->with(['instructor', 'modules.lessons'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}