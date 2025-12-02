<?php

namespace App\Http\Middleware;

use App\Services\Instructor\InstructorEarningsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to automatically update instructor earnings status
 *
 * This is a "lazy evaluation" approach perfect for shared hosting where
 * cron jobs are unreliable. It updates earnings status on-demand when
 * instructors access their dashboard/earnings pages.
 */
class UpdateInstructorEarningsStatus
{
    public function __construct(
        private InstructorEarningsService $earningsService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only run for authenticated users who are instructors
        // Update their pending earnings to available if holding period has passed
        // This happens silently in the background
        if ($request->user()?->hasRole('instructor')) {
            $this->earningsService->markPendingEarningsAsAvailable($request->user()->id);
        }

        return $next($request);
    }
}
