<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Task, MyWorkItem, Client};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkloadController extends Controller
{
    /**
     * Team Workload Capacity & Personal Day Planner Hub
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedRole = $request->query('role');
        $selectedDate = $request->query('date', now()->format('Y-m-d'));

        // Query active team members
        $teamQuery = User::where('active', true)
            ->whereNotIn('role', ['client'])
            ->orderBy('name');

        if ($request->filled('role')) {
            $teamQuery->where('role', $request->role);
        }

        $teamUsers = $teamQuery->get();

        // Calculate workload capacity metrics for each team member
        $teamWorkload = $teamUsers->map(function ($member) {
            $activeTasks = Task::with('client')
                ->where('assigned_to', $member->id)
                ->whereNotIn('status', ['done', 'completed'])
                ->orderBy('deadline')
                ->get();

            $activeCount = $activeTasks->count();
            $overdueCount = $activeTasks->filter(function ($t) {
                return $t->deadline && Carbon::parse($t->deadline)->isPast() && !Carbon::parse($t->deadline)->isToday();
            })->count();
            $dueTodayCount = $activeTasks->filter(function ($t) {
                return $t->deadline && Carbon::parse($t->deadline)->isToday();
            })->count();

            // Completed tasks this month
            $completedCount = Task::where('assigned_to', $member->id)
                ->where('status', 'done')
                ->where('updated_at', '>=', now()->startOfMonth())
                ->count();

            // Bandwidth status
            if ($activeCount >= 5 || $overdueCount >= 2) {
                $status = 'overloaded';
                $statusLabel = 'High Load / At Capacity';
                $statusColor = 'rose';
                $loadPercent = min(100, 70 + ($activeCount * 5));
            } elseif ($activeCount >= 2) {
                $status = 'balanced';
                $statusLabel = 'Optimal Bandwidth';
                $statusColor = 'indigo';
                $loadPercent = min(85, 30 + ($activeCount * 12));
            } else {
                $status = 'available';
                $statusLabel = 'Capacity Available';
                $statusColor = 'emerald';
                $loadPercent = max(15, $activeCount * 20);
            }

            return (object) [
                'user' => $member,
                'activeTasks' => $activeTasks,
                'activeCount' => $activeCount,
                'overdueCount' => $overdueCount,
                'dueTodayCount' => $dueTodayCount,
                'completedCount' => $completedCount,
                'status' => $status,
                'statusLabel' => $statusLabel,
                'statusColor' => $statusColor,
                'loadPercent' => $loadPercent,
            ];
        });

        // Aggregates for Team Capacity Cards
        $totalActiveTasks = $teamWorkload->sum('activeCount');
        $overloadedMembers = $teamWorkload->where('status', 'overloaded')->count();
        $availableMembers = $teamWorkload->where('status', 'available')->count();
        $totalOverdue = $teamWorkload->sum('overdueCount');

        // Personal Day Planner Items for logged-in user
        $myPlannerItems = MyWorkItem::where('user_id', $user->id)
            ->latest('due_date')
            ->latest('id')
            ->get();

        $plannerPendingCount = $myPlannerItems->where('status', 'pending')->count();
        $plannerInProgressCount = $myPlannerItems->where('status', 'in_progress')->count();
        $plannerDoneCount = $myPlannerItems->where('status', 'done')->count();
        $plannerTotalCount = $myPlannerItems->count();
        $plannerCompletionPct = $plannerTotalCount > 0 ? round(($plannerDoneCount / $plannerTotalCount) * 100) : 0;

        // Distinct Roles for Filtering
        $roles = User::where('active', true)
            ->whereNotIn('role', ['client'])
            ->distinct()
            ->pluck('role');

        return view('workload.index', compact(
            'teamWorkload',
            'totalActiveTasks',
            'overloadedMembers',
            'availableMembers',
            'totalOverdue',
            'myPlannerItems',
            'plannerPendingCount',
            'plannerInProgressCount',
            'plannerDoneCount',
            'plannerTotalCount',
            'plannerCompletionPct',
            'roles',
            'selectedRole',
            'selectedDate'
        ));
    }

    /**
     * Add a personal work item / to-do to Day Planner
     */
    public function storeWorkItem(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:50',
            'priority' => 'required|in:high,medium,low',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        MyWorkItem::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'category' => $validated['category'] ?? 'General',
            'priority' => $validated['priority'],
            'status' => 'pending',
            'due_date' => $validated['due_date'] ?? now()->format('Y-m-d'),
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Day planner item added successfully!');
    }

    /**
     * Update status of personal work item (pending, in_progress, done)
     */
    public function updateWorkItemStatus(Request $request, MyWorkItem $item)
    {
        $user = Auth::user();
        if ($item->user_id !== $user->id && !$user->isOwner() && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,done',
        ]);

        $item->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === 'done' ? now() : null,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $validated['status']]);
        }

        return back()->with('success', 'Planner item status updated.');
    }

    /**
     * Delete a personal work item
     */
    public function destroyWorkItem(MyWorkItem $item)
    {
        $user = Auth::user();
        if ($item->user_id !== $user->id && !$user->isOwner() && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $item->delete();
        return back()->with('success', 'Planner item deleted.');
    }
}
