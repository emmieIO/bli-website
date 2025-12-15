<?php

namespace App\Repositories;

use App\Contracts\Repositories\MentorshipRepositoryInterface;
use App\Models\MentorshipRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MentorshipRepository implements MentorshipRepositoryInterface
{
    /**
     * Get all mentorship requests with optional filtering
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = MentorshipRequest::with(['student', 'instructor', 'adminApprover', 'instructorApprover'])
            ->orderBy('created_at', 'desc');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['student_id'])) {
            $query->where('student_id', $filters['student_id']);
        }

        if (!empty($filters['instructor_id'])) {
            $query->where('instructor_id', $filters['instructor_id']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereHas('student', function ($sq) use ($filters) {
                    $sq->where('name', 'like', '%' . $filters['search'] . '%');
                })->orWhereHas('instructor', function ($sq) use ($filters) {
                    $sq->where('name', 'like', '%' . $filters['search'] . '%');
                });
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Get a mentorship request by ID with relationships
     */
    public function getById(int $id, array $relations = []): ?MentorshipRequest
    {
        $defaultRelations = ['student', 'instructor', 'adminApprover', 'instructorApprover', 'rejector'];
        $relations = empty($relations) ? $defaultRelations : $relations;

        return MentorshipRequest::with($relations)->find($id);
    }

    /**
     * Get requests for a specific student
     */
    public function getForStudent(User $student, ?string $status = null): Collection
    {
        $query = MentorshipRequest::forStudent($student->id)
            ->with(['instructor', 'adminApprover', 'instructorApprover'])
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Get requests for a specific instructor
     */
    public function getForInstructor(User $instructor, ?string $status = null): Collection
    {
        $query = MentorshipRequest::forInstructor($instructor->id)
            ->with(['student', 'adminApprover', 'instructorApprover'])
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Get pending requests for instructor approval
     */
    public function getPendingForInstructor(User $instructor): Collection
    {
        return MentorshipRequest::forInstructor($instructor->id)
            ->byStatus('pending')
            ->with('student')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get requests pending admin approval
     */
    public function getPendingAdminApproval(): Collection
    {
        return MentorshipRequest::byStatus('instructor_approved')
            ->with(['student', 'instructor', 'instructorApprover'])
            ->orderBy('instructor_approved_at', 'asc')
            ->get();
    }

    /**
     * Create a new mentorship request
     */
    public function create(array $data): MentorshipRequest
    {
        return MentorshipRequest::create($data);
    }

    /**
     * Update a mentorship request
     */
    public function update(MentorshipRequest $request, array $data): MentorshipRequest
    {
        $request->update($data);
        return $request->fresh();
    }

    /**
     * Delete a mentorship request
     */
    public function delete(MentorshipRequest $request): bool
    {
        return $request->delete();
    }

    /**
     * Approve request at instructor level
     */
    public function instructorApprove(MentorshipRequest $request, User $instructor, ?string $response = null): MentorshipRequest
    {
        $request->update([
            'status' => 'instructor_approved',
            'instructor_response' => $response,
            'instructor_approved_at' => now(),
            'instructor_approved_by' => $instructor->id,
        ]);

        return $request->fresh();
    }

    /**
     * Approve request at admin level
     */
    public function adminApprove(MentorshipRequest $request, User $admin, ?string $response = null): MentorshipRequest
    {
        $request->update([
            'status' => 'admin_approved',
            'admin_response' => $response,
            'admin_approved_at' => now(),
            'admin_approved_by' => $admin->id,
            'started_at' => now(), // Auto-start on admin approval
        ]);

        return $request->fresh();
    }

    /**
     * Reject a mentorship request
     */
    public function reject(MentorshipRequest $request, User $rejector, string $reason): MentorshipRequest
    {
        $request->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_at' => now(),
            'rejected_by' => $rejector->id,
        ]);

        return $request->fresh();
    }

    /**
     * Cancel a mentorship request
     */
    public function cancel(MentorshipRequest $request): MentorshipRequest
    {
        $request->update([
            'status' => 'cancelled',
        ]);

        return $request->fresh();
    }

    /**
     * Start an approved mentorship
     */
    public function start(MentorshipRequest $request): MentorshipRequest
    {
        if (!$request->isAdminApproved()) {
            throw new \Exception('Mentorship must be admin approved before starting');
        }

        $request->update([
            'started_at' => now(),
        ]);

        return $request->fresh();
    }

    /**
     * End an active mentorship
     */
    public function end(MentorshipRequest $request): MentorshipRequest
    {
        $request->update([
            'ended_at' => now(),
        ]);

        return $request->fresh();
    }

    /**
     * Check if student has active mentorship with instructor
     */
    public function hasActiveMentorship(User $student, User $instructor): bool
    {
        return MentorshipRequest::forStudent($student->id)
            ->forInstructor($instructor->id)
            ->active()
            ->exists();
    }

    /**
     * Check if student has pending request with instructor
     */
    public function hasPendingRequest(User $student, User $instructor): bool
    {
        return MentorshipRequest::forStudent($student->id)
            ->forInstructor($instructor->id)
            ->whereIn('status', ['pending', 'instructor_approved'])
            ->exists();
    }

    /**
     * Get active mentorships for a student
     */
    public function getActiveMentorships(User $student): Collection
    {
        return MentorshipRequest::forStudent($student->id)
            ->active()
            ->with('instructor')
            ->get();
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics(User $user): array
    {
        $stats = [
            'total' => 0,
            'pending' => 0,
            'instructor_approved' => 0,
            'admin_approved' => 0,
            'active' => 0,
            'rejected' => 0,
        ];

        if ($user->hasRole('student')) {
            $stats['total'] = MentorshipRequest::forStudent($user->id)->count();
            $stats['pending'] = MentorshipRequest::forStudent($user->id)->byStatus('pending')->count();
            $stats['instructor_approved'] = MentorshipRequest::forStudent($user->id)->byStatus('instructor_approved')->count();
            $stats['admin_approved'] = MentorshipRequest::forStudent($user->id)->byStatus('admin_approved')->count();
            $stats['active'] = MentorshipRequest::forStudent($user->id)->active()->count();
            $stats['rejected'] = MentorshipRequest::forStudent($user->id)->byStatus('rejected')->count();
        } elseif ($user->hasRole('instructor')) {
            $stats['total'] = MentorshipRequest::forInstructor($user->id)->count();
            $stats['pending'] = MentorshipRequest::forInstructor($user->id)->byStatus('pending')->count();
            $stats['instructor_approved'] = MentorshipRequest::forInstructor($user->id)->byStatus('instructor_approved')->count();
            $stats['admin_approved'] = MentorshipRequest::forInstructor($user->id)->byStatus('admin_approved')->count();
            $stats['active'] = MentorshipRequest::forInstructor($user->id)->active()->count();
            $stats['rejected'] = MentorshipRequest::forInstructor($user->id)->byStatus('rejected')->count();
        } elseif ($user->hasRole('admin')) {
            $stats['total'] = MentorshipRequest::count();
            $stats['pending'] = MentorshipRequest::byStatus('pending')->count();
            $stats['instructor_approved'] = MentorshipRequest::byStatus('instructor_approved')->count();
            $stats['admin_approved'] = MentorshipRequest::byStatus('admin_approved')->count();
            $stats['active'] = MentorshipRequest::active()->count();
            $stats['rejected'] = MentorshipRequest::byStatus('rejected')->count();
        }

        return $stats;
    }
}
