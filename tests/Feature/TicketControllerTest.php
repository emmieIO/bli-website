<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_own_tickets()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('user.tickets.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('User/Tickets/Index')
            ->has('tickets.data', 1)
            ->where('tickets.data.0.id', $ticket->id));
    }

    public function test_user_cannot_view_other_users_tickets()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $this->actingAs($user);

        Ticket::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->get(route('user.tickets.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('User/Tickets/Index')
            ->has('tickets.data', 0));
    }

    public function test_user_can_view_a_single_ticket()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('user.tickets.show', $ticket));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('User/Tickets/Show')
            ->where('ticket.id', $ticket->id));
    }

    public function test_user_cannot_view_another_users_single_ticket()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $this->actingAs($user);

        $ticket = Ticket::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->get(route('user.tickets.show', $ticket));

        $response->assertStatus(403);
    }
}
