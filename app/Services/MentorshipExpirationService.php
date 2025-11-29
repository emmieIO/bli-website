<?php

namespace App\Services;

use App\Models\MentorshipRequest;
use Illuminate\Support\Facades\Log;

class MentorshipExpirationService
{
    /**
     * Check and expire all mentorships that have passed their expiration date
     * This runs on every request via middleware (shared hosting friendly)
     */
    public function checkAndExpireMentorships(): int
    {
        $expiredCount = 0;

        try {
            // Get all expired mentorships
            $expiredMentorships = MentorshipRequest::expired()->get();

            foreach ($expiredMentorships as $mentorship) {
                $this->expireMentorship($mentorship);
                $expiredCount++;
            }

            if ($expiredCount > 0) {
                Log::info("Expired {$expiredCount} mentorship(s)");
            }
        } catch (\Exception $e) {
            Log::error('Error checking mentorship expirations: ' . $e->getMessage());
        }

        return $expiredCount;
    }

    /**
     * Expire a single mentorship
     */
    public function expireMentorship(MentorshipRequest $mentorship): void
    {
        $mentorship->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        Log::info("Mentorship #{$mentorship->id} automatically expired");

        // You could send notifications here
        // $this->notifyParticipants($mentorship);
    }

    /**
     * Get expiring soon mentorships for a user (student or instructor)
     */
    public function getExpiringSoonForUser(int $userId, string $role = 'student'): array
    {
        $query = MentorshipRequest::expiringSoon();

        if ($role === 'student') {
            $query->where('student_id', $userId);
        } else {
            $query->where('instructor_id', $userId);
        }

        return $query->with(['student', 'instructor'])->get()->map(function ($mentorship) {
            return [
                'id' => $mentorship->id,
                'student' => $mentorship->student->name,
                'instructor' => $mentorship->instructor->name,
                'days_remaining' => $mentorship->days_remaining,
                'expiration_date' => $mentorship->expiration_date?->format('Y-m-d'),
                'expiration_status' => $mentorship->expiration_status,
            ];
        })->toArray();
    }

    /**
     * Check if a specific mentorship should be expired
     */
    public function shouldExpire(MentorshipRequest $mentorship): bool
    {
        return $mentorship->isActive() && $mentorship->isExpired();
    }

    /**
     * Manually end a mentorship before expiration
     */
    public function endMentorship(MentorshipRequest $mentorship): void
    {
        if (!$mentorship->isActive()) {
            throw new \Exception('Mentorship is not active');
        }

        $mentorship->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        Log::info("Mentorship #{$mentorship->id} manually ended");
    }

    /**
     * Get statistics about expirations
     */
    public function getExpirationStats(): array
    {
        return [
            'expired_count' => MentorshipRequest::where('status', 'ended')->count(),
            'expiring_soon_count' => MentorshipRequest::expiringSoon()->count(),
            'active_count' => MentorshipRequest::active()->count(),
        ];
    }
}
