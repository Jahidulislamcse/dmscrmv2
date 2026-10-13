<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Campaign::with(['client', 'assignedTo', 'createdBy']);

        // Scoping for non-admins
        if ($user && !$user->isOwner() && !$user->isAdmin()) {
            if ($user->role === 'mediabuyer') {
                $query->where(function ($q) use ($user) {
                    $q->where('assigned_to', $user->id)
                      ->orWhere('created_by', $user->id);
                });
            } elseif ($user->role === 'smm') {
                $clientIds = Client::where('assigned_smm', $user->id)->pluck('id');
                $query->where(function ($q) use ($clientIds, $user) {
                    $q->whereIn('client_id', $clientIds)
                      ->orWhere('assigned_to', $user->id);
                });
            }
        }

        // Filtering
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('objective')) {
            $query->where('objective', $request->objective);
        }

        // Summary metrics query (across filtered or total)
        $summaryQuery = clone $query;
        $totalBudget      = (float) $summaryQuery->sum('budget');
        $totalSpend       = (float) $summaryQuery->sum('spend');
        $totalRevenue     = (float) $summaryQuery->sum('revenue');
        $totalLeads       = (int)   $summaryQuery->sum('leads_count');
        $totalClicks      = (int)   $summaryQuery->sum('clicks');
        $totalImpressions = (int)   $summaryQuery->sum('impressions');

        $blendedCpl  = $totalLeads > 0 ? round($totalSpend / $totalLeads, 2) : 0.0;
        $blendedCtr  = $totalImpressions > 0 ? round(($totalClicks / $totalImpressions) * 100, 2) : 0.0;
        $blendedRoas = $totalSpend > 0 ? round($totalRevenue / $totalSpend, 2) : 0.0;
        $blendedCpc  = $totalClicks > 0 ? round($totalSpend / $totalClicks, 2) : 0.0;

        $campaigns = $query->orderBy('created_at', 'desc')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'campaigns' => $campaigns,
                'metrics' => [
                    'budget'      => $totalBudget,
                    'spend'       => $totalSpend,
                    'revenue'     => $totalRevenue,
                    'leads'       => $totalLeads,
                    'cpl'         => $blendedCpl,
                    'ctr'         => $blendedCtr,
                    'roas'        => $blendedRoas,
                    'cpc'         => $blendedCpc,
                ]
            ]);
        }

        $clients = Client::orderBy('name')->get();
        $mediaBuyers = User::where('active', true)->orderBy('name')->get();

        return view('campaigns.index', compact(
            'campaigns',
            'clients',
            'mediaBuyers',
            'totalBudget',
            'totalSpend',
            'totalRevenue',
            'totalLeads',
            'totalClicks',
            'totalImpressions',
            'blendedCpl',
            'blendedCtr',
            'blendedRoas',
            'blendedCpc'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'client_id'       => 'required|exists:clients,id',
            'platform'        => 'required|string|max:50',
            'objective'       => 'required|string|max:50',
            'status'          => 'required|string|max:50',
            'budget'          => 'nullable|numeric|min:0',
            'spend'           => 'nullable|numeric|min:0',
            'revenue'         => 'nullable|numeric|min:0',
            'impressions'     => 'nullable|integer|min:0',
            'clicks'          => 'nullable|integer|min:0',
            'leads_count'     => 'nullable|integer|min:0',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'target_audience' => 'nullable|string',
            'notes'           => 'nullable|string',
            'ad_account_id'   => 'nullable|string|max:100',
            'assigned_to'     => 'nullable|exists:users,id',
        ]);

        $validated['created_by']  = Auth::id() ?? 1;
        $validated['budget']      = $validated['budget'] ?? 0;
        $validated['spend']       = $validated['spend'] ?? 0;
        $validated['revenue']     = $validated['revenue'] ?? 0;
        $validated['impressions'] = $validated['impressions'] ?? 0;
        $validated['clicks']      = $validated['clicks'] ?? 0;
        $validated['leads_count'] = $validated['leads_count'] ?? 0;

        Campaign::create($validated);

        return redirect()->back()->with('success', 'Ad campaign launched successfully!');
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'client_id'       => 'required|exists:clients,id',
            'platform'        => 'required|string|max:50',
            'objective'       => 'required|string|max:50',
            'status'          => 'required|string|max:50',
            'budget'          => 'nullable|numeric|min:0',
            'spend'           => 'nullable|numeric|min:0',
            'revenue'         => 'nullable|numeric|min:0',
            'impressions'     => 'nullable|integer|min:0',
            'clicks'          => 'nullable|integer|min:0',
            'leads_count'     => 'nullable|integer|min:0',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'target_audience' => 'nullable|string',
            'notes'           => 'nullable|string',
            'ad_account_id'   => 'nullable|string|max:100',
            'assigned_to'     => 'nullable|exists:users,id',
        ]);

        $campaign->update($validated);

        return redirect()->back()->with('success', 'Campaign details updated successfully!');
    }

    public function updateMetrics(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'spend'       => 'required|numeric|min:0',
            'revenue'     => 'nullable|numeric|min:0',
            'impressions' => 'nullable|integer|min:0',
            'clicks'      => 'nullable|integer|min:0',
            'leads_count' => 'nullable|integer|min:0',
        ]);

        $campaign->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Campaign metrics updated',
                'cpl'     => $campaign->cpl,
                'ctr'     => $campaign->ctr,
                'roas'    => $campaign->roas,
            ]);
        }

        return redirect()->back()->with('success', 'Campaign live metrics updated!');
    }

    public function updateStatus(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:active,paused,completed,draft',
        ]);

        $campaign->update($validated);

        return redirect()->back()->with('success', 'Campaign status updated to ' . ucfirst($campaign->status));
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->back()->with('success', 'Campaign deleted successfully.');
    }
}
