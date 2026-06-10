<?php

namespace App\Http\Middleware;

use App\Models\Event;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSpeakerWorkspaceAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        abort_unless($user, 403);

        $event = $request->route('event');

        if (! $event instanceof Event) {
            $slug = $request->route('slug');

            if ($slug) {
                $event = Event::findBySlug($slug)->first();
            }
        }

        abort_unless($event instanceof Event, 404);
        abort_unless($user->hasSpeakerEventContext($event), 404);

        return $next($request);
    }
}
