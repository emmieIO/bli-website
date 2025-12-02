<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use App\Notifications\NewTicketCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = auth()->user()->tickets()->latest()->paginate(10);
        return Inertia::render('User/Tickets/Index', compact('tickets'));
    }

    public function create()
    {
        return Inertia::render('User/Tickets/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'priority' => $request->priority,
        ]);

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Notify all admins about the new ticket
        $admins = User::role('admin')->get();
        Notification::send($admins, new NewTicketCreatedNotification($ticket));

        return redirect()->route('user.tickets.show', $ticket);
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->load(['replies.user', 'members']);

        return Inertia::render('User/Tickets/Show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return redirect()->route('user.tickets.show', $ticket);
    }
}
