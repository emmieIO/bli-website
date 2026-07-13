<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminGuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            $previous = url()->previous();
            $current = $request->fullUrl();

            // Avoid redirect loop
            if ($previous && $previous !== $current && ! str_contains($previous, '/login')) {
                return redirect()->to($previous);
            }

            return redirect()->route('user_dashboard');
        }

        return $next($request);
    }
}
