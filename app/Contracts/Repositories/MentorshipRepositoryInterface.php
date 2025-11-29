<?php

namespace App\Contracts\Repositories;

use App\Models\MentorshipRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface MentorshipRepositoryInterface
{
    /**
     * Get all mentorship requests with optional filtering
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get a mentorship request by ID with relationships
     */
    public function getById(int $id, array $relations = []): ?MentorshipRequest;

    /**
     * Get requests for a specific student
     */
    public function getForStudent(User $student, ?string $status = null): Collection;

    /**
     * Get requests for a specific instructor
     */
    public function getForInstructor(User $instructor, ?string $status = null): Collection;

    /**
     * Get pending requests for instructor approval
     */
    public function getPendingForInstructor(User $instructor): Collection;

    /**
     * Get requests pending admin approval
     */
    public function getPendingAdminApproval(): Collection;

    /**
     * Create a new mentorship request
     */
    public function create(array $data): MentorshipRequest;

    /**
     * Update a mentorship request
     */
    public function update(MentorshipRequest $request, array $data): MentorshipRequest;

    /**
     * Delete a mentorship request
     */
    public function delete(MentorshipRequest $request): bool;

    /**
     * Approve request at instructor level
     */
    public function instructorApprove(MentorshipRequest $request, User $instructor, ?string $response = null): MentorshipRequest;

    /**
     * Approve request at admin level
     */
    public function adminApprove(MentorshipRequest $request, User $admin, ?string $response = null): MentorshipRequest;

    /**
     * Reject a mentorship request
     */
    public function reject(MentorshipRequest $request, User $rejector, string $reason): MentorshipRequest;

    /**
     * Cancel a mentorship request
     */
    public function cancel(MentorshipRequest $request): MentorshipRequest;

    /**
     * Start an approved mentorship
     */
    public function start(MentorshipRequest $request): MentorshipRequest;

    /**
     * End an active mentorship
     */
    public function end(MentorshipRequest $request): MentorshipRequest;

    /**
     * Check if student has active mentorship with instructor
     */
    public function hasActiveMentorship(User $student, User $instructor): bool;

    /**
     * Check if student has pending request with instructor
     */
    public function hasPendingRequest(User $student, User $instructor): bool;

    /**
     * Get active mentorships for a student
     */
    public function getActiveMentorships(User $student): Collection;

    /**
     * Get statistics for dashboard
     */
    public function getStatistics(User $user): array;
}
