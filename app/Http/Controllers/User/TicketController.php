<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use App\Notifications\NewTicketCreatedNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
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

        $tickets = auth()->user()->tickets()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('reference_code', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

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

        return redirect()->route('user.tickets.show', $ticket)->with([
            'message' => "Ticket #{$ticket->reference_code} created successfully! We'll respond as soon as possible.",
            'type' => 'success'
        ]);
    }

    public function show(Ticket $ticket)
    {
        // Security: Use policy-based authorization instead of manual checks
        $this->authorize('view', $ticket);

        $ticket->load(['replies.user', 'members']);

        return Inertia::render('User/Tickets/Show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        // Security: Use policy-based authorization instead of manual checks
        $this->authorize('reply', $ticket);

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
