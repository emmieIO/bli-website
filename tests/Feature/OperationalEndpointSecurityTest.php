<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class OperationalEndpointSecurityTest extends TestCase
{
    public function test_operational_endpoints_fail_closed_without_a_configured_token(): void
    {
        config(['queue.token' => null]);

        $this->get('/process-queue')->assertForbidden();
        $this->get('/send-event-reminders')->assertForbidden();
    }

    public function test_operational_endpoints_reject_an_invalid_token(): void
    {
        config(['queue.token' => 'expected-token']);

        $this->get('/process-queue?token=wrong-token')->assertForbidden();
        $this->withToken('wrong-token')->get('/send-event-reminders')->assertForbidden();
    }

    public function test_queue_endpoint_accepts_the_configured_bearer_token(): void
    {
        config(['queue.token' => 'expected-token']);
        Artisan::shouldReceive('call')->once()->with('queue:work', [
            '--stop-when-empty' => true,
            '--max-time' => 3600,
        ])->andReturn(0);

        $this->withToken('expected-token')->get('/process-queue')
            ->assertOk()
            ->assertSee('Queue Processed.');
    }

    public function test_reminder_endpoint_keeps_query_token_compatibility(): void
    {
        config(['queue.token' => 'expected-token']);
        Artisan::shouldReceive('call')->once()->with('app:send-event-reminders')->andReturn(0);

        $this->get('/send-event-reminders?token=expected-token')
            ->assertOk()
            ->assertSee('Event reminders queued successfully.');
    }
}
