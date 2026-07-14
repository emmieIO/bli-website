<?php

namespace Tests\Feature;

use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CsrfRecoveryTest extends TestCase
{
    public function test_expired_csrf_token_returns_the_user_to_the_form_with_a_retry_message(): void
    {
        Route::middleware('web')->post('/test/csrf-expired', function (): never {
            throw new TokenMismatchException;
        });

        $response = $this
            ->from('/test/event-form')
            ->withHeader('X-Inertia', 'true')
            ->post('/test/csrf-expired');

        $response
            ->assertRedirect('/test/event-form')
            ->assertSessionHas('type', 'warning')
            ->assertSessionHas('message', 'Your secure session was refreshed. Please submit the form again.');
    }
}
