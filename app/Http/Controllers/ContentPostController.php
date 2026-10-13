<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentPost;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ContentPostController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ContentPost::with(['client', 'assignedTo', 'createdBy']);

        // SMM/Staff scoping: if staff has assigned clients or specific post assignments
        if ($user && !$user->isOwner() && !$user->isAdmin()) {
            if ($user->role === 'smm') {
                $clientIds = Client::where('assigned_smm', $user->id)->pluck('id');
                $query->where(function ($q) use ($clientIds, $user) {
                    $q->whereIn('client_id', $clientIds)
                      ->orWhere('assigned_to', $user->id)
                      ->orWhere('created_by', $user->id);
                });
            } else {
                // Creative staff (designer, motion, video, etc.) sees their assigned posts or posts where they are involved
                $query->where(function ($q) use ($user) {
                    $q->where('assigned_to', $user->id)
                      ->orWhere('created_by', $user->id);
                });
            }
        }

        // Filters
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        if ($request->filled('post_type')) {
            $query->where('post_type', $request->post_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Month / Year filtering for calendar
        $month = $request->get('month', now()->format('m'));
        $year  = $request->get('year', now()->format('Y'));
        
        $viewMode = $request->get('view', 'calendar'); // 'calendar', 'pipeline', 'list'

        if ($viewMode === 'calendar') {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            $query->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);
        }

        $posts = $query->orderBy('date', 'asc')->orderBy('publish_time', 'asc')->get();

        if ($request->wantsJson()) {
            return response()->json($posts);
        }

        // Metrics for summary badges
        $metricsQuery = ContentPost::query();
        if ($request->filled('client_id')) {
            $metricsQuery->where('client_id', $request->client_id);
        }
        $totalCount = (clone $metricsQuery)->count();
        $stageCounts = [];
        foreach (ContentPost::STAGES as $stageKey => $stageMeta) {
            $stageCounts[$stageKey] = (clone $metricsQuery)->where('status', $stageKey)->count();
        }

        $clients = Client::orderBy('name')->get();
        $teamMembers = User::where('active', true)->orderBy('name')->get();

        return view('calendar.index', compact(
            'posts',
            'clients',
            'teamMembers',
            'month',
            'year',
            'viewMode',
            'totalCount',
            'stageCounts'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'client_id'         => 'required|exists:clients,id',
            'assigned_to'       => 'nullable|exists:users,id',
            'platform'          => 'required|string|max:50',
            'post_type'         => 'required|string|max:50',
            'date'              => 'required|date',
            'publish_time'      => 'nullable|string|max:20',
            'status'            => 'required|string|max:50',
            'caption'           => 'nullable|string',
            'creative_url'      => 'nullable|string|max:1000',
            'brief'             => 'nullable|string',
            'notes'             => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id() ?? 1;

        ContentPost::create($validated);

        return redirect()->back()->with('success', 'Content item added to calendar successfully!');
    }

    public function update(Request $request, ContentPost $post)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'client_id'         => 'required|exists:clients,id',
            'assigned_to'       => 'nullable|exists:users,id',
            'platform'          => 'required|string|max:50',
            'post_type'         => 'required|string|max:50',
            'date'              => 'required|date',
            'publish_time'      => 'nullable|string|max:20',
            'status'            => 'required|string|max:50',
            'caption'           => 'nullable|string',
            'creative_url'      => 'nullable|string|max:1000',
            'brief'             => 'nullable|string',
            'notes'             => 'nullable|string',
            'internal_feedback' => 'nullable|string',
            'client_feedback'   => 'nullable|string',
        ]);

        $post->update($validated);

        return redirect()->back()->with('success', 'Content item updated successfully!');
    }

    public function updateStage(Request $request, ContentPost $post)
    {
        $validated = $request->validate([
            'status'            => 'required|string|in:draft,internal_review,client_approval,revision,approved,published',
            'internal_feedback' => 'nullable|string',
            'client_feedback'   => 'nullable|string',
        ]);

        $post->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stage updated to ' . ($post->stage_info['label'] ?? $post->status),
                'stage'   => $post->status,
            ]);
        }

        return redirect()->back()->with('success', 'Post moved to ' . ($post->stage_info['label'] ?? $post->status));
    }

    public function destroy(ContentPost $post)
    {
        $post->delete();

        return redirect()->back()->with('success', 'Content item deleted successfully.');
    }
}
