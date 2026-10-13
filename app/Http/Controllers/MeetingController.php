<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Meeting, Client, Lead, User, Notification};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Meeting::with(['client', 'lead', 'attendees', 'createdBy']);

        if ($user && !$user->isOwner()) {
            $query->where(function($q) use ($user) {
                $q->whereHas('attendees', fn($sq) => $sq->where('users.id', $user->id))
                  ->orWhere('created_by', $user->id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('agenda', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $allMeetings = $query->orderBy('date', 'desc')->orderBy('time', 'desc')->get();
        $upcomingMeetings = $allMeetings->where('status', 'scheduled')->where('date', '>=', now()->toDateString());
        $pastMeetings = $allMeetings->reject(fn($m) => $upcomingMeetings->contains('id', $m->id));

        $clients = Client::orderBy('name')->get();
        $leads = Lead::orderBy('name')->get();
        $teamUsers = User::where('active', true)->orderBy('name')->get();

        if ($request->wantsJson()) {
            return response()->json($allMeetings);
        }

        return view('meetings.index', compact('allMeetings', 'upcomingMeetings', 'pastMeetings', 'clients', 'leads', 'teamUsers'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $leads = Lead::orderBy('name')->get();
        $teamUsers = User::where('active', true)->orderBy('name')->get();

        return view('meetings.create', compact('clients', 'leads', 'teamUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agenda' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'lead_id' => 'nullable|exists:leads,id',
            'client_name' => 'nullable|string|max:255',
            'date' => 'required|date',
            'time' => 'nullable',
            'duration' => 'required|integer|min:15',
            'location' => 'nullable|string|max:255',
            'attendees' => 'nullable|array',
            'attendees.*' => 'exists:users,id',
            'outcome' => 'nullable|string',
            'next_action' => 'nullable|string',
            'next_followup' => 'nullable|date',
        ]);

        // Auto-resolve client name if client_id or lead_id selected
        $clientName = $validated['client_name'] ?? null;
        if (!empty($validated['client_id'])) {
            $client = Client::find($validated['client_id']);
            $clientName = $client->name ?? $clientName;
        } elseif (!empty($validated['lead_id'])) {
            $lead = Lead::find($validated['lead_id']);
            $clientName = $lead->name ?? $clientName;
        }

        $meeting = Meeting::create([
            'client_id' => $validated['client_id'] ?? null,
            'lead_id' => $validated['lead_id'] ?? null,
            'created_by' => Auth::id(),
            'client_name' => $clientName,
            'agenda' => $validated['agenda'],
            'date' => $validated['date'],
            'time' => $validated['time'] ?? '10:00',
            'duration' => $validated['duration'],
            'location' => $validated['location'] ?? null,
            'status' => 'scheduled',
            'outcome' => $validated['outcome'] ?? null,
            'next_action' => $validated['next_action'] ?? null,
            'next_followup' => $validated['next_followup'] ?? null,
        ]);

        if (!empty($validated['attendees'])) {
            $meeting->attendees()->sync($validated['attendees']);

            // Notify attendees
            foreach ($validated['attendees'] as $userId) {
                if ($userId != Auth::id()) {
                    Notification::create([
                        'user_id' => $userId,
                        'icon' => 'calendar',
                        'bg_color' => '#dbeafe',
                        'message' => "You have been invited to a meeting: '{$meeting->agenda}' on {$meeting->date->format('d M, Y')}",
                        'is_admin_only' => false,
                    ]);
                }
            }
        }

        // Priority 3: When sales or any team member sets a new lead meeting, notify Admin
        if ($meeting->lead_id) {
            $lead = Lead::find($meeting->lead_id);
            $leadName = $lead ? $lead->name : 'Prospect Lead';
            $creator = Auth::user();

            $admins = User::whereIn('role', ['owner', 'admin'])
                ->where('id', '!=', $creator ? $creator->id : 0)
                ->get();

            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'icon' => 'calendar',
                    'bg_color' => '#fef3c7',
                    'message' => "New Lead Meeting Scheduled: " . ($creator ? $creator->name : 'Sales') . " scheduled '{$meeting->agenda}' with lead '{$leadName}' on {$meeting->date->format('d M, Y')}",
                    'is_admin_only' => true,
                ]);
            }

            \App\Models\LeadTimeline::create([
                'lead_id' => $meeting->lead_id,
                'user_id' => Auth::id(),
                'type' => 'meeting',
                'text' => "Scheduled meeting: '{$meeting->agenda}' on {$meeting->date->format('d M, Y')} at {$meeting->time}",
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json($meeting->load(['client', 'attendees']), 201);
        }

        return redirect()->route('meetings.index')->with('success', "Meeting '{$meeting->agenda}' scheduled successfully!");
    }

    public function show(Meeting $meeting)
    {
        $meeting->load(['client', 'lead', 'attendees', 'createdBy']);
        $teamUsers = User::where('active', true)->orderBy('name')->get();

        return view('meetings.show', compact('meeting', 'teamUsers'));
    }

    public function edit(Meeting $meeting)
    {
        $meeting->load('attendees');
        $clients = Client::orderBy('name')->get();
        $leads = Lead::orderBy('name')->get();
        $teamUsers = User::where('active', true)->orderBy('name')->get();

        return view('meetings.edit', compact('meeting', 'clients', 'leads', 'teamUsers'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'agenda' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'lead_id' => 'nullable|exists:leads,id',
            'client_name' => 'nullable|string|max:255',
            'date' => 'required|date',
            'time' => 'nullable',
            'duration' => 'required|integer|min:15',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,completed,cancelled',
            'attendees' => 'nullable|array',
            'attendees.*' => 'exists:users,id',
            'outcome' => 'nullable|string',
            'next_action' => 'nullable|string',
            'next_followup' => 'nullable|date',
        ]);

        $meeting->update($validated);

        if (isset($validated['attendees'])) {
            $meeting->attendees()->sync($validated['attendees']);
        }

        if ($request->wantsJson()) {
            return response()->json($meeting->fresh(['client', 'attendees']));
        }

        return redirect()->route('meetings.show', $meeting->id)->with('success', "Meeting details updated successfully!");
    }

    public function updateStatus(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled',
            'outcome' => 'nullable|string',
            'next_action' => 'nullable|string',
            'next_followup' => 'nullable|date',
        ]);

        $meeting->update($validated);

        // If outcome is logged for a linked lead, update lead timeline too
        if (!empty($validated['outcome']) && $meeting->lead_id) {
            \App\Models\LeadTimeline::create([
                'lead_id' => $meeting->lead_id,
                'user_id' => Auth::id(),
                'type' => 'meeting',
                'text' => "Meeting Outcome ({$meeting->agenda}): " . $validated['outcome'],
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'meeting' => $meeting]);
        }

        return back()->with('success', "Meeting status updated to " . ucfirst($meeting->status) . "!");
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Meeting deleted']);
        }

        return redirect()->route('meetings.index')->with('success', "Meeting removed from schedule!");
    }
}
