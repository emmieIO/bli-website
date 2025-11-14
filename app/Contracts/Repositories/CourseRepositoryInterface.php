<?php

namespace App\Contracts\Repositories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface
{
    public function find(int $id): ?Course;
    
    public function findBySlug(string $slug): ?Course;
    
    public function getWithRelations(int $id, array $relations = []): ?Course;
    
    public function getUserEnrolledCourses(User $user): Collection;
    
    public function isUserEnrolled(User $user, Course $course): bool;
    
    public function enrollUser(User $user, Course $course): void;
    
    public function getPublishedCourses(): Collection;
}