<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Followup, Lead, Client, Meeting, User, Notification};

class FollowupController extends Controller
{
    public function index(Request $request)
    {
        $query = Followup::with(['lead', 'client', 'meeting', 'assignedTo', 'createdBy']);

        // Tab filter
        $tab = $request->get('tab', 'today');
        if ($tab === 'today') {
            $query->where('status', 'pending')->whereDate('scheduled_at', now()->toDateString());
        } elseif ($tab === 'overdue') {
            $query->where('status', 'pending')->where('scheduled_at', '<', now());
        } elseif ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'completed') {
            $query->where('status', 'completed');
        }

        // Channel / Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Assigned user filter
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Search text
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('outcome', 'like', "%{$search}%")
                  ->orWhereHas('lead', fn($lq) => $lq->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"))
                  ->orWhereHas('client', fn($cq) => $cq->where('name', 'like', "%{$search}%")->orWhere('company', 'like', "%{$search}%"));
            });
        }

        $followups = $query->orderBy('scheduled_at', 'asc')->paginate(20);

        // Metrics Summary
        $todayCount = Followup::where('status', 'pending')->whereDate('scheduled_at', now()->toDateString())->count();
        $overdueCount = Followup::where('status', 'pending')->where('scheduled_at', '<', now())->count();
        $pendingTotal = Followup::where('status', 'pending')->count();
        $completedTotal = Followup::where('status', 'completed')->count();

        // Relation Dropdowns
        $leads = Lead::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        $meetings = Meeting::latest()->limit(30)->get();
        $users = User::where('active', true)->orderBy('name')->get();

        return view('followups.index', compact(
            'followups',
            'tab',
            'todayCount',
            'overdueCount',
            'pendingTotal',
            'completedTotal',
            'leads',
            'clients',
            'meetings',
            'users'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:call,email,whatsapp,meeting,visit,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'scheduled_at' => 'required|date',
            'lead_id' => 'nullable|exists:leads,id',
            'client_id' => 'nullable|exists:clients,id',
            'meeting_id' => 'nullable|exists:meetings,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $followup = Followup::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'priority' => $validated['priority'],
            'scheduled_at' => $validated['scheduled_at'],
            'status' => 'pending',
            'lead_id' => $validated['lead_id'] ?? null,
            'client_id' => $validated['client_id'] ?? null,
            'meeting_id' => $validated['meeting_id'] ?? null,
            'assigned_to' => $validated['assigned_to'] ?? Auth::id(),
            'created_by' => Auth::id(),
        ]);

        // Sync lead next_followup date if linked to a lead
        if (!empty($validated['lead_id'])) {
            $lead = Lead::find($validated['lead_id']);
            if ($lead) {
                $lead->update(['next_followup' => $validated['scheduled_at']]);
            }
        }

        // Notify assigned user if different
        if (!empty($validated['assigned_to']) && $validated['assigned_to'] != Auth::id()) {
            Notification::create([
                'user_id' => $validated['assigned_to'],
                'icon' => 'calendar-check',
                'bg_color' => '#dbeafe',
                'message' => "You have been assigned a new follow-up: '{$followup->title}' scheduled for " . \Carbon\Carbon::parse($followup->scheduled_at)->format('d M, Y h:i A'),
                'is_admin_only' => false,
            ]);
        }

        return redirect()->route('followups.index')->with('success', "Follow-up '{$followup->title}' scheduled successfully!");
    }

    public function update(Request $request, Followup $followup)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:call,email,whatsapp,meeting,visit,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'scheduled_at' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
            'outcome' => 'nullable|string',
            'lead_id' => 'nullable|exists:leads,id',
            'client_id' => 'nullable|exists:clients,id',
            'meeting_id' => 'nullable|exists:meetings,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $followup->update($validated);

        if ($validated['status'] === 'completed' && empty($followup->completed_at)) {
            $followup->update(['completed_at' => now()]);
        }

        return redirect()->route('followups.index')->with('success', "Follow-up updated successfully!");
    }

    public function complete(Request $request, Followup $followup)
    {
        $validated = $request->validate([
            'outcome' => 'nullable|string',
            'next_followup_date' => 'nullable|date',
            'next_followup_title' => 'nullable|string|max:255',
        ]);

        $followup->update([
            'status' => 'completed',
            'completed_at' => now(),
            'outcome' => $validated['outcome'] ?? 'Completed successfully.',
        ]);

        // Create next follow-up if requested
        if (!empty($validated['next_followup_date'])) {
            $nextTitle = $validated['next_followup_title'] ?? ("Next Follow-up for " . ($followup->lead->name ?? $followup->client->name ?? $followup->title));
            Followup::create([
                'title' => $nextTitle,
                'type' => $followup->type,
                'priority' => $followup->priority,
                'scheduled_at' => $validated['next_followup_date'],
                'status' => 'pending',
                'lead_id' => $followup->lead_id,
                'client_id' => $followup->client_id,
                'meeting_id' => $followup->meeting_id,
                'assigned_to' => $followup->assigned_to ?? Auth::id(),
                'created_by' => Auth::id(),
            ]);

            if ($followup->lead_id) {
                Lead::where('id', $followup->lead_id)->update(['next_followup' => $validated['next_followup_date']]);
            }
        }

        return redirect()->route('followups.index')->with('success', "Follow-up marked as completed!");
    }

    public function destroy(Followup $followup)
    {
        $followup->delete();
        return redirect()->route('followups.index')->with('success', 'Follow-up deleted!');
    }
}
