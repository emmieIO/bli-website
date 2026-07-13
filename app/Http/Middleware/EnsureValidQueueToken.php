<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidQueueToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $configuredToken = config('queue.token');
        $providedToken = $request->bearerToken() ?: $request->query('token');

        // Fail closed when deployment configuration is incomplete. The old
        // null-to-null comparison accidentally authorized anonymous requests.
        if (! is_string($configuredToken) || $configuredToken === '' ||
            ! is_string($providedToken) || ! hash_equals($configuredToken, $providedToken)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
