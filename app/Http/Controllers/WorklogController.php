<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Worklog, User, Client, Task};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorklogController extends Controller
{
    /**
     * Daily Worklogs Feed & QA Review Hub
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedMonth = $request->query('month', now()->format('Y-m'));

        $query = Worklog::with(['user', 'client', 'task', 'reviewedBy'])
            ->where('date', 'like', "{$selectedMonth}%");

        // Creative/production staff see their own worklogs by default; SMM and Managers see all
        $isReviewer = $user && ($user->isOwner() || $user->isAdmin() || $user->isSMM());
        if (!$isReviewer) {
            $query->where('user_id', $user->id);
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $worklogs = $query->latest('date')->latest('id')->paginate(15)->withQueryString();

        // Calculate KPI Metrics for this month
        $baseQuery = Worklog::where('date', 'like', "{$selectedMonth}%");
        if (!$isReviewer) {
            $baseQuery->where('user_id', $user->id);
        }

        $totalSubmissions = (clone $baseQuery)->count();
        $totalApproved = (clone $baseQuery)->where('status', 'approved')->count();
        $totalPending = (clone $baseQuery)->where('status', 'pending')->count();
        $totalRevision = (clone $baseQuery)->where('status', 'revision')->count();
        $avgRating = round((float) ((clone $baseQuery)->whereNotNull('quality_rating')->avg('quality_rating') ?: 0), 1);

        // Dropdown Data
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $teamMembers = User::where('active', true)->orderBy('name')->get();

        // Open tasks assigned to current user for easy linking
        $myTasks = Task::where('assigned_to', $user->id)
            ->where('status', '!=', 'done')
            ->orderBy('deadline')
            ->get();

        // Month filter options
        $monthOptions = [];
        for ($i = -2; $i <= 3; $i++) {
            $m = now()->addMonths($i);
            $monthOptions[$m->format('Y-m')] = $m->format('F Y');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'worklogs' => $worklogs,
                'totalSubmissions' => $totalSubmissions,
                'totalApproved' => $totalApproved,
                'totalPending' => $totalPending,
                'avgRating' => $avgRating,
            ]);
        }

        return view('worklogs.index', compact(
            'worklogs',
            'selectedMonth',
            'totalSubmissions',
            'totalApproved',
            'totalPending',
            'totalRevision',
            'avgRating',
            'clients',
            'teamMembers',
            'myTasks',
            'monthOptions',
            'isReviewer'
        ));
    }

    /**
     * Submit daily work output deliverable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
            'task_id' => 'nullable|exists:tasks,id',
            'qty_done' => 'required|integer|min:1',
            'qty_total' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        $worklog = Worklog::create([
            'user_id' => Auth::id(),
            'client_id' => $validated['client_id'] ?? null,
            'task_id' => $validated['task_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'qty_done' => $validated['qty_done'],
            'qty_total' => $validated['qty_total'],
            'date' => $validated['date'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Daily work output submitted! Awaiting SMM quality evaluation.');
    }

    /**
     * SMM / Manager Review & Quality Rating (1-5 stars + remarks)
     */
    public function review(Request $request, Worklog $worklog)
    {
        $user = Auth::user();
        $isReviewer = $user && ($user->isOwner() || $user->isAdmin() || $user->isSMM());

        if (!$isReviewer) {
            return back()->with('error', 'Unauthorized: Only SMM leads and Administrators can review worklogs.');
        }

        $validated = $request->validate([
            'quality_rating' => 'required|integer|min:1|max:5',
            'status' => 'required|in:approved,revision',
            'smm_feedback' => 'nullable|string|max:1000',
        ]);

        $worklog->update([
            'quality_rating' => $validated['quality_rating'],
            'status' => $validated['status'],
            'smm_feedback' => $validated['smm_feedback'] ?? null,
            'reviewed_by' => Auth::id(),
        ]);

        return back()->with('success', "Worklog rated {$validated['quality_rating']} stars and updated to " . ucfirst(str_replace('_', ' ', $validated['status'])));
    }

    /**
     * Delete worklog
     */
    public function destroy(Worklog $worklog)
    {
        $user = Auth::user();

        // Staff can delete their own worklog only if not yet approved
        if ($worklog->user_id === $user->id && $worklog->status !== 'approved') {
            $worklog->delete();
            return back()->with('success', 'Worklog deleted successfully.');
        }

        if ($user->isOwner() || $user->isAdmin()) {
            $worklog->delete();
            return back()->with('success', 'Worklog deleted successfully.');
        }

        return back()->with('error', 'Cannot delete this worklog.');
    }
}
