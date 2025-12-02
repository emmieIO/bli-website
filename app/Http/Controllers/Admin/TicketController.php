<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Notifications\TicketUpdatedNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $search = $request->input('search');

        // Security: Escape special LIKE wildcards to prevent SQL wildcard injection
        if ($search) {
            $search = addcslashes($search, '%_');
        }

        $tickets = Ticket::with('user')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('reference_code', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Tickets/Index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['replies.user', 'user']);
        return Inertia::render('Admin/Tickets/Show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        // Security: Use policy-based authorization
        $this->authorize('reply', $ticket);

        if ($ticket->status === 'closed') {
            return redirect()->route('admin.tickets.show', $ticket)->with('danger', 'This ticket is closed and cannot be updated.');
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        $ticket->update(['status' => 'in_progress']);

        $ticket->user->notify(new TicketUpdatedNotification($ticket, 'in_progress'));

        return redirect()->route('admin.tickets.show', $ticket);
    }

    public function status(Request $request, Ticket $ticket)
    {
        // Security: Use policy-based authorization
        $this->authorize('updateStatus', $ticket);

        if ($ticket->status === 'closed') {
            return redirect()->route('admin.tickets.show', $ticket)->with('danger', 'This ticket is closed and cannot be updated.');
        }

        $request->validate([
            'status' => 'required|string',
        ]);

        $ticket->update([
            'status' => $request->status,
        ]);

        $ticket->user->notify(new TicketUpdatedNotification($ticket, $request->status));

        return redirect()->route('admin.tickets.show', $ticket);
    }
}
