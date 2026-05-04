<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->withCount('replies')
            ->latest()
            ->paginate(10);

        return view('frontend.tickets.index', compact('tickets'));
    }

    // User: নতুন ticket form
    public function create()
    {
        return view('frontend.tickets.create');
    }

    // User: ticket submit
    public function store(Request $request)
    {
        $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = [
            'user_id'     => auth()->id(),
            'subject'     => $request->subject,
            'description' => $request->description,
            'status'      => 0,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tickets', 'public');
        }

        Ticket::create($data);

        return redirect()->route('tickets.index')->with('success', 'Ticket submitted successfully.');
    }

    // User + Admin: ticket detail + replies
    public function show(Ticket $ticket)
    {
        // user শুধু নিজের ticket দেখতে পারবে
        if (!auth()->user()->hasRole('admin') && $ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->load('replies.user');

        return view('frontend.tickets.show', compact('ticket'));
    }

    // User + Admin: reply submit
    public function reply(Request $request, Ticket $ticket)
    {
        if (!auth()->user()->hasRole('admin') && $ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
            'image'   => 'nullable|image|max:2048',
        ]);

        $data = [
            'ticket_id' => $ticket->id,
            'user_id'   => auth()->id(),
            'message'   => $request->message,
            'is_admin'  => auth()->user()->hasRole('admin'),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tickets/replies', 'public');
        }

        TicketReply::create($data);

        // admin reply দিলে status in_progress করো
        if (auth()->user()->hasRole('admin')) {
            $ticket->update(['status' => 1]);
        }

        return redirect()->back()->with('success', 'Reply sent.');
    }

    // Admin: ticket close
    public function close(Ticket $ticket)
    {
        $ticket->update(['status' => 2]);
        return redirect()->back()->with('success', 'Ticket closed.');
    }
    // Admin: সব ticket list
    public function list()
    {
        $tickets = Ticket::with('user')
            ->withCount('replies')
            ->when(request('status') !== null && request('status') !== '', function ($q) {
                $q->where('status', request('status'));
            })
            ->when(request('search'), function ($q) {
                $q->where('subject', 'like', '%' . request('search') . '%')
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . request('search') . '%'));
            })
            ->latest()
            ->paginate(20);

        return view('support-ticket.list', compact('tickets'));
    }

    // Admin: ticket detail
    public function showAdmin(Ticket $ticket)
    {
        $ticket->load('replies.user', 'user');
        return view('support-ticket.show', compact('ticket'));
    }
    public function adminReply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'image'   => 'nullable|image|max:2048',
        ]);

        $data = [
            'user_id'  => auth()->id(),
            'message'  => $request->message,
            'is_admin' => true,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tickets/replies', 'public');
        }

        $ticket->replies()->create($data);

        // dd($ticket->image, $ticket->toArray());
        if ($ticket->status == 0) {
            $ticket->update(['status' => 1]);
        }

        return back()->with('success', 'Reply sent to user.');
    }

    // Admin: ticket destroy
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('ticket.admin')->with('success', 'Ticket #' . $ticket->id . ' deleted successfully.');
    }
}
