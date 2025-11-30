<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Notifications\TicketUpdatedNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('user')->latest()->paginate(10);
        return Inertia::render('Admin/Tickets/Index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['replies.user', 'user']);
        return Inertia::render('Admin/Tickets/Show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
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
