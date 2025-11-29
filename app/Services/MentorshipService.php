<?php

namespace App\Services;

use App\Contracts\Repositories\MentorshipRepositoryInterface;
use App\Models\MentorshipRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class MentorshipService
{
    public function __construct(
        private MentorshipRepositoryInterface $mentorshipRepository
    ) {}

    /**
     * Create a new mentorship request
     */
    public function requestMentorship(User $student, User $instructor, array $data): MentorshipRequest
    {
        // Validate that student doesn't already have pending/active request with this instructor
        if ($this->mentorshipRepository->hasPendingRequest($student, $instructor)) {
            throw new \Exception('You already have a pending request with this instructor.');
        }

        if ($this->mentorshipRepository->hasActiveMentorship($student, $instructor)) {
            throw new \Exception('You already have an active mentorship with this instructor.');
        }

        // Validate that the instructor has the instructor role
        if (!$instructor->hasRole('instructor')) {
            throw new \Exception('The selected user is not an instructor.');
        }

        DB::beginTransaction();

        try {
            $mentorshipRequest = $this->mentorshipRepository->create([
                'student_id' => $student->id,
                'instructor_id' => $instructor->id,
                'message' => $data['message'],
                'goals' => $data['goals'] ?? null,
                'duration_type' => $data['duration_type'] ?? 'monthly',
                'duration_value' => $data['duration_value'] ?? 1,
                'status' => 'pending',
            ]);

            // TODO: Send notification to instructor
            // Notification::send($instructor, new MentorshipRequestReceived($mentorshipRequest));

            DB::commit();

            Log::info('Mentorship request created', [
                'request_id' => $mentorshipRequest->id,
                'student_id' => $student->id,
                'instructor_id' => $instructor->id,
            ]);

            return $mentorshipRequest;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create mentorship request', [
                'error' => $e->getMessage(),
                'student_id' => $student->id,
                'instructor_id' => $instructor->id,
            ]);
            throw $e;
        }
    }

    /**
     * Instructor approves a mentorship request
     */
    public function instructorApprove(MentorshipRequest $request, User $instructor, ?string $response = null): MentorshipRequest
    {
        // Validate that request is in pending status
        if (!$request->isPending()) {
            throw new \Exception('This request is not pending instructor approval.');
        }

        // Validate that the approver is the instructor for this request
        if ($request->instructor_id !== $instructor->id) {
            throw new \Exception('You are not the instructor for this mentorship request.');
        }

        DB::beginTransaction();

        try {
            $request = $this->mentorshipRepository->instructorApprove($request, $instructor, $response);

            // TODO: Send notification to student
            // Notification::send($request->student, new MentorshipInstructorApproved($request));

            // TODO: Send notification to admins
            // $admins = User::role('admin')->get();
            // Notification::send($admins, new MentorshipPendingAdminApproval($request));

            DB::commit();

            Log::info('Mentorship request approved by instructor', [
                'request_id' => $request->id,
                'instructor_id' => $instructor->id,
            ]);

            return $request;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve mentorship request at instructor level', [
                'error' => $e->getMessage(),
                'request_id' => $request->id,
            ]);
            throw $e;
        }
    }

    /**
     * Admin gives final approval to a mentorship request
     */
    public function adminApprove(MentorshipRequest $request, User $admin, ?string $response = null): MentorshipRequest
    {
        // Validate that request is instructor approved
        if (!$request->isInstructorApproved()) {
            throw new \Exception('This request has not been approved by the instructor yet.');
        }

        // Validate that the approver has admin role
        if (!$admin->hasRole('admin')) {
            throw new \Exception('Only administrators can give final approval.');
        }

        DB::beginTransaction();

        try {
            $request = $this->mentorshipRepository->adminApprove($request, $admin, $response);

            // TODO: Send notifications to student and instructor
            // Notification::send($request->student, new MentorshipApproved($request));
            // Notification::send($request->instructor, new MentorshipApproved($request));

            DB::commit();

            Log::info('Mentorship request approved by admin', [
                'request_id' => $request->id,
                'admin_id' => $admin->id,
            ]);

            return $request;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve mentorship request at admin level', [
                'error' => $e->getMessage(),
                'request_id' => $request->id,
            ]);
            throw $e;
        }
    }

    /**
     * Reject a mentorship request
     */
    public function reject(MentorshipRequest $request, User $rejector, string $reason): MentorshipRequest
    {
        // Validate rejection permissions
        $canReject = false;

        if ($request->isPending() && $request->instructor_id === $rejector->id) {
            $canReject = true; // Instructor can reject at pending stage
        } elseif ($request->isInstructorApproved() && $rejector->hasRole('admin')) {
            $canReject = true; // Admin can reject at instructor_approved stage
        } elseif ($rejector->hasRole('admin')) {
            $canReject = true; // Admin can reject at any stage
        }

        if (!$canReject) {
            throw new \Exception('You do not have permission to reject this request.');
        }

        DB::beginTransaction();

        try {
            $request = $this->mentorshipRepository->reject($request, $rejector, $reason);

            // TODO: Send notification to student
            // Notification::send($request->student, new MentorshipRejected($request));

            DB::commit();

            Log::info('Mentorship request rejected', [
                'request_id' => $request->id,
                'rejected_by' => $rejector->id,
                'reason' => $reason,
            ]);

            return $request;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject mentorship request', [
                'error' => $e->getMessage(),
                'request_id' => $request->id,
            ]);
            throw $e;
        }
    }

    /**
     * Student cancels their mentorship request
     */
    public function cancel(MentorshipRequest $request, User $student): MentorshipRequest
    {
        // Validate that the student owns this request
        if ($request->student_id !== $student->id) {
            throw new \Exception('You can only cancel your own mentorship requests.');
        }

        // Can only cancel if not yet admin approved
        if ($request->isAdminApproved()) {
            throw new \Exception('Cannot cancel an approved mentorship. Please contact support.');
        }

        DB::beginTransaction();

        try {
            $request = $this->mentorshipRepository->cancel($request);

            // TODO: Send notification to instructor if already instructor approved
            // if ($request->instructor_approved_at) {
            //     Notification::send($request->instructor, new MentorshipCancelled($request));
            // }

            DB::commit();

            Log::info('Mentorship request cancelled', [
                'request_id' => $request->id,
                'student_id' => $student->id,
            ]);

            return $request;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to cancel mentorship request', [
                'error' => $e->getMessage(),
                'request_id' => $request->id,
            ]);
            throw $e;
        }
    }

    /**
     * End an active mentorship
     */
    public function endMentorship(MentorshipRequest $request, User $user): MentorshipRequest
    {
        // Validate that the user can end this mentorship
        $canEnd = $request->student_id === $user->id
            || $request->instructor_id === $user->id
            || $user->hasRole('admin');

        if (!$canEnd) {
            throw new \Exception('You do not have permission to end this mentorship.');
        }

        // Validate that mentorship is active
        if (!$request->isActive()) {
            throw new \Exception('This mentorship is not currently active.');
        }

        DB::beginTransaction();

        try {
            $request = $this->mentorshipRepository->end($request);

            // TODO: Send notifications to both parties
            // Notification::send($request->student, new MentorshipEnded($request));
            // Notification::send($request->instructor, new MentorshipEnded($request));

            DB::commit();

            Log::info('Mentorship ended', [
                'request_id' => $request->id,
                'ended_by' => $user->id,
            ]);

            return $request;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to end mentorship', [
                'error' => $e->getMessage(),
                'request_id' => $request->id,
            ]);
            throw $e;
        }
    }

    /**
     * Get available instructors for mentorship
     */
    public function getAvailableInstructors(): \Illuminate\Database\Eloquent\Collection
    {
        return User::role('instructor')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get mentorship statistics for user
     */
    public function getStatistics(User $user): array
    {
        return $this->mentorshipRepository->getStatistics($user);
    }
}
