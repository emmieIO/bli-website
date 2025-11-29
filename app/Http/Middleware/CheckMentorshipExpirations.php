<?php

namespace App\Http\Middleware;

use App\Services\MentorshipExpirationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMentorshipExpirations
{
    public function __construct(
        private MentorshipExpirationService $expirationService
    ) {}

    /**
     * Handle an incoming request.
     * Checks and expires mentorships on authenticated user requests.
     * This is a shared hosting friendly approach (no cron jobs needed).
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check on authenticated requests to avoid unnecessary DB queries
        if (auth()->check()) {
            // Run expiration check asynchronously to avoid blocking the request
            // This will automatically expire any mentorships that have passed their expiration date
            $this->expirationService->checkAndExpireMentorships();
        }

        return $next($request);
    }
}
