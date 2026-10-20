<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Target, TargetItem, TargetDeal, User, Client, Service};
use Illuminate\Support\Facades\{Auth, DB};
use Carbon\Carbon;

class TargetController extends Controller
{
    /**
     * Sales Targets & Quotas Overview
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedMonth = $request->query('month', now()->format('Y-m'));

        $query = Target::with(['user', 'createdBy', 'items', 'deals.client'])
            ->where('month', $selectedMonth);

        // Sales reps see only their assigned targets
        if ($user && $user->isSales()) {
            $query->where('user_id', $user->id);
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $targets = $query->latest('id')->get();

        // Calculate Overview KPI Stats
        $totalGoal = $targets->sum(fn($t) => $t->total);
        $totalAchieved = $targets->sum(fn($t) => $t->achieved);
        $overallProgress = $totalGoal > 0 ? min(100, round(($totalAchieved / $totalGoal) * 100, 1)) : 0;
        $totalDealsCount = $targets->sum(fn($t) => $t->deals->count());

        // Dropdown data for Modals
        $salesReps = User::whereIn('role', ['sales', 'admin', 'owner'])
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $services = Service::orderBy('name')->get();
        $clients = Client::where('status', 'active')->orderBy('name')->get();

        // Available Months list
        $monthOptions = [];
        for ($i = -2; $i <= 6; $i++) {
            $m = now()->addMonths($i);
            $monthOptions[$m->format('Y-m')] = $m->format('F Y');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'targets' => $targets,
                'totalGoal' => $totalGoal,
                'totalAchieved' => $totalAchieved,
                'overallProgress' => $overallProgress,
            ]);
        }

        return view('targets.index', compact(
            'targets',
            'selectedMonth',
            'totalGoal',
            'totalAchieved',
            'overallProgress',
            'totalDealsCount',
            'salesReps',
            'services',
            'clients',
            'monthOptions'
        ));
    }

    /**
     * Create a new sales target with item quotas
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->isSales()) {
            return back()->with('error', 'Sales representatives cannot assign targets. Targets must be assigned by Admin/Management.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|date_format:Y-m',
            'label' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.service_name' => 'required|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $target = Target::create([
                'user_id' => $validated['user_id'],
                'created_by' => Auth::id(),
                'month' => $validated['month'],
                'label' => $validated['label'],
            ]);

            foreach ($validated['items'] as $item) {
                TargetItem::create([
                    'target_id' => $target->id,
                    'service_name' => $item['service_name'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('targets.index', ['month' => $validated['month']])
            ->with('success', 'Sales target and quota breakdown created successfully!');
    }

    /**
     * Record deal attribution towards a target
     */
    public function addDeal(Request $request, Target $target)
    {
        $user = Auth::user();

        // Security check: sales reps can only log deals on their own targets
        if ($user && $user->isSales() && $target->user_id !== $user->id) {
            return back()->with('error', 'You can only attribute deals towards your own assigned sales target.');
        }

        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'target_item_id' => 'nullable|exists:target_items,id',
            'service_name' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        TargetDeal::create([
            'target_id' => $target->id,
            'target_item_id' => $validated['target_item_id'] ?? null,
            'client_id' => $validated['client_id'] ?? null,
            'service_name' => $validated['service_name'],
            'qty' => $validated['qty'],
            'amount' => $validated['amount'],
            'date' => $validated['date'],
        ]);

        return back()->with('success', "Deal attribution of ৳" . number_format($validated['amount']) . " recorded successfully!");
    }

    /**
     * Update target metadata
     */
    public function update(Request $request, Target $target)
    {
        $user = Auth::user();
        if ($user && $user->isSales()) {
            return back()->with('error', 'Unauthorized: Sales staff cannot modify target parameters.');
        }

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'month' => 'required|date_format:Y-m',
        ]);

        $target->update($validated);

        return back()->with('success', 'Sales target updated successfully!');
    }

    /**
     * Delete target
     */
    public function destroy(Target $target)
    {
        $user = Auth::user();
        if ($user && $user->isSales()) {
            return back()->with('error', 'Unauthorized: Sales staff cannot delete targets.');
        }

        $target->items()->delete();
        $target->deals()->delete();
        $target->delete();

        return back()->with('success', 'Target deleted successfully.');
    }
}
