<?php

use App\Http\Middleware\AdminGuestMiddleware;
use App\Http\Middleware\CheckMentorshipExpirations;
use App\Http\Middleware\EnsureInstructorAccess;
use App\Http\Middleware\EnsureMentorAccess;
use App\Http\Middleware\EnsureValidQueueToken;
use App\Http\Middleware\EnsureSpeakerWorkspaceAccess;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            CheckMentorshipExpirations::class,
        ]);

        $middleware->alias([
            'admin.guest' => AdminGuestMiddleware::class,
            'instructor.access' => EnsureInstructorAccess::class,
            'mentor.access' => EnsureMentorAccess::class,
            'queue.token' => EnsureValidQueueToken::class,
            'speaker.workspace' => EnsureSpeakerWorkspaceAccess::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function (\Symfony\Component\HttpFoundation\Response $response, \Throwable $_, Request $request) {
            if ($response->getStatusCode() === 419) {
                return back()->with([
                    'type' => 'warning',
                    'message' => 'Your secure session was refreshed. Please submit the form again.',
                ]);
            }

            // Only render custom Inertia error pages in production
            // In development, let Laravel's error handler (Ignition) show detailed errors
            if (!app()->environment('local', 'development') &&
                in_array($response->getStatusCode(), [401, 403, 404, 429, 500, 503])) {
                return inertia('Errors/Error', [
                    'status' => $response->getStatusCode(),
                ])
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());
            }

            return $response;
        });
    })->create();
