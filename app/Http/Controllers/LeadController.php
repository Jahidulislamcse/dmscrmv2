<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\{Lead, LeadStage, LeadRequirement, LeadTimeline, User, Service, Requisition, RequisitionItem, Notification};

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $stages = LeadStage::orderBy('order')->get();
        $salesReps = User::where('active', true)->whereIn('role', ['owner', 'admin', 'sales'])->get();

        $query = Lead::with(['stage', 'assignedTo', 'createdBy', 'requirements']);

        if ($user && !$user->isAdmin()) {
            $query->where('assigned_to', $user->id);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $allLeads = $query->latest()->get();

        // Group leads by stage for Kanban board view
        $kanbanLeads = [];
        foreach ($stages as $stage) {
            $kanbanLeads[$stage->id] = $allLeads->where('stage_id', $stage->id);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'stages' => $stages,
                'leads' => $allLeads
            ]);
        }

        return view('crm.index', compact('stages', 'kanbanLeads', 'allLeads', 'salesReps'));
    }

    public function create()
    {
        $stages = LeadStage::orderBy('order')->get();
        $salesReps = User::where('active', true)->whereIn('role', ['owner', 'sales'])->get();
        $services = Service::where('active', true)->get();

        return view('crm.create', compact('stages', 'salesReps', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'source' => 'required|string|max:100',
            'stage_id' => 'required|exists:lead_stages,id',
            'assigned_to' => 'required|exists:users,id',
            'budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'next_followup' => 'nullable|date',
            'requirements' => 'nullable|array',
            'requirements.*.service_name' => 'required|string|max:255',
            'requirements.*.qty' => 'required|integer|min:1',
            'requirements.*.unit_price' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();

        $lead = Lead::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'company' => $validated['company'] ?? null,
            'location' => $validated['location'] ?? null,
            'source' => $validated['source'],
            'stage_id' => $validated['stage_id'],
            'assigned_to' => $validated['assigned_to'],
            'created_by' => $user->id,
            'budget' => $validated['budget'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'next_followup' => $validated['next_followup'] ?? null,
        ]);

        // Log Timeline
        LeadTimeline::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'type' => 'created',
            'text' => "Lead created and assigned to " . ($lead->assignedTo->name ?? 'Sales Rep'),
        ]);

        // Add Requirements
        if (!empty($validated['requirements'])) {
            foreach ($validated['requirements'] as $req) {
                LeadRequirement::create([
                    'lead_id' => $lead->id,
                    'service_name' => $req['service_name'],
                    'qty' => $req['qty'],
                    'unit_price' => $req['unit_price'],
                ]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json($lead->load(['stage', 'assignedTo', 'requirements']), 201);
        }

        return redirect()->route('crm.index')->with('success', "Lead '{$lead->name}' added to pipeline!");
    }

    public function show(Lead $lead)
    {
        $lead->load(['stage', 'assignedTo', 'createdBy', 'requirements', 'timelines.user', 'convertedClient']);
        $stages = LeadStage::orderBy('order')->get();
        $salesReps = User::where('active', true)->whereIn('role', ['owner', 'sales'])->get();
        $services = Service::where('active', true)->get();

        return view('crm.show', compact('lead', 'stages', 'salesReps', 'services'));
    }

    public function edit(Lead $lead)
    {
        $lead->load('requirements');
        $stages = LeadStage::orderBy('order')->get();
        $salesReps = User::where('active', true)->whereIn('role', ['owner', 'sales'])->get();

        return view('crm.edit', compact('lead', 'stages', 'salesReps'));
    }

    public function update(Request $request, Lead $lead)
    {
        $user = Auth::user();
        $oldStageId = $lead->stage_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'source' => 'required|string|max:100',
            'stage_id' => 'required|exists:lead_stages,id',
            'assigned_to' => 'required|exists:users,id',
            'budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'next_followup' => 'nullable|date',
        ]);

        // Priority 7 Rule: Sales member cannot change lead stage
        if ($user && $user->isSales()) {
            $validated['stage_id'] = $lead->stage_id;
        }

        $lead->update($validated);

        if ($oldStageId !== (int)$validated['stage_id']) {
            $newStage = LeadStage::find($validated['stage_id']);
            LeadTimeline::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'type' => 'stage',
                'text' => "Stage changed to: " . ($newStage->name ?? 'New Stage'),
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json($lead->fresh(['stage', 'assignedTo']));
        }

        return redirect()->route('crm.show', $lead->id)->with('success', "Lead profile updated!");
    }

    public function updateStage(Request $request, Lead $lead)
    {
        $user = Auth::user();

        // Priority 7 Rule: Sales member cannot change lead stage
        if ($user && $user->isSales()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Access denied: Sales representatives cannot modify lead stages. Lead stages are view-only.'], 403);
            }
            return back()->with('error', 'Access denied: Sales representatives cannot change lead stages. Stage progression is view-only for the sales team.');
        }

        $request->validate(['stage_id' => 'required|exists:lead_stages,id']);

        $oldStageId = $lead->stage_id;
        $lead->stage_id = $request->stage_id;
        $lead->save();

        if ($oldStageId !== (int)$request->stage_id) {
            $newStage = LeadStage::find($request->stage_id);
            LeadTimeline::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'type' => 'stage',
                'text' => "Moved lead to: " . ($newStage->name ?? 'Stage'),
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'lead' => $lead->load('stage')]);
        }

        return back()->with('success', "Lead stage updated!");
    }

    public function addTimelineNote(Request $request, Lead $lead)
    {
        $request->validate(['text' => 'required|string']);

        LeadTimeline::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => 'note',
            'text' => $request->text,
        ]);

        return back()->with('success', "Activity note added to timeline!");
    }

    public function convertToRequisition(Request $request, Lead $lead)
    {
        if ($lead->converted_client_id) {
            return back()->with('error', 'This lead has already been converted into a client/requisition!');
        }

        DB::transaction(function() use ($request, $lead) {
            $wonStage = LeadStage::where('name', 'like', '%won%')->first();
            if ($wonStage) {
                $lead->update(['stage_id' => $wonStage->id]);
            }

            $totalVal = $lead->requirements->sum(function($r) {
                return $r->qty * $r->unit_price;
            });
            if ($totalVal == 0 && $lead->budget > 0) {
                $totalVal = $lead->budget;
            }

            $requisition = Requisition::create([
                'lead_id' => $lead->id,
                'submitted_by' => Auth::id(),
                'client_name' => $lead->name,
                'company' => $lead->company ?? $lead->name,
                'phone' => $lead->phone,
                'email' => $lead->email,
                'location' => $lead->location,
                'total_value' => $totalVal,
                'advance_paid' => $request->input('advance_paid', 0),
                'billing_cycle' => $request->input('billing_cycle', 'monthly'),
                'status' => 'pending',
                'special_notes' => "Converted from CRM Lead #{$lead->id}. " . ($lead->notes ?? ''),
            ]);

            foreach ($lead->requirements as $req) {
                RequisitionItem::create([
                    'requisition_id' => $requisition->id,
                    'service_name' => $req->service_name,
                    'qty' => $req->qty,
                    'unit_price' => $req->unit_price,
                    'total' => $req->qty * $req->unit_price,
                ]);
            }

            LeadTimeline::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'type' => 'requisition',
                'text' => "Lead converted into Sales Requisition #{$requisition->id}",
            ]);

            // Priority 3 Rule: When sales close happens, notify Admins so Superadmin assigns project team members
            $admins = User::whereIn('role', ['owner', 'admin'])->get();
            $actor = Auth::user();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'icon' => 'trophy',
                    'bg_color' => '#fef3c7',
                    'message' => "Sale Closed! '{$lead->name}' was closed and converted to Requisition #{$requisition->id} by {$actor->name}. Superadmin action required: Assign project team members.",
                    'is_admin_only' => true,
                ]);
            }
        });

        return redirect()->route('requisitions.index')->with('success', "Lead successfully converted into Sales Requisition! Project team assignment requested.");
    }

    public function destroy(Lead $lead)
    {
        $user = Auth::user();
        if ($user && $user->isSales()) {
            return back()->with('error', 'Access denied: Sales representatives cannot delete leads.');
        }

        $lead->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Deleted']);
        }

        return redirect()->route('crm.index')->with('success', "Lead deleted!");
    }

    public function quotation(Lead $lead)
    {
        $lead->load(['stage', 'assignedTo', 'createdBy', 'requirements', 'quotationApprovedBy']);

        $subtotal = $lead->requirements->sum(fn($r) => $r->qty * $r->unit_price);
        if ($subtotal == 0 && $lead->budget > 0) {
            $subtotal = $lead->budget;
        }

        $discount = $lead->quotation_discount ?? 0;
        $afterDiscount = max(0, $subtotal - $discount);
        $taxPercent = $lead->quotation_tax_percent ?? 0;
        $tax = round(($afterDiscount * $taxPercent) / 100, 2);
        $grandTotal = $afterDiscount + $tax;

        return view('crm.quotation', compact('lead', 'subtotal', 'discount', 'tax', 'grandTotal'));
    }

    public function updateQuotation(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'quotation_notes' => 'nullable|string',
            'quotation_valid_until' => 'nullable|date',
            'quotation_discount' => 'nullable|numeric|min:0',
            'quotation_tax_percent' => 'nullable|numeric|min:0|max:100',
            'items' => 'nullable|array',
            'items.*.service_name' => 'required|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $lead->update([
            'quotation_notes' => $validated['quotation_notes'] ?? null,
            'quotation_valid_until' => $validated['quotation_valid_until'] ?? null,
            'quotation_discount' => $validated['quotation_discount'] ?? 0,
            'quotation_tax_percent' => $validated['quotation_tax_percent'] ?? 0,
        ]);

        if (isset($validated['items'])) {
            $lead->requirements()->delete();
            foreach ($validated['items'] as $item) {
                LeadRequirement::create([
                    'lead_id' => $lead->id,
                    'service_name' => $item['service_name'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                ]);
            }
        }

        return redirect()->route('crm.quotation', $lead->id)->with('success', 'Quotation details updated successfully!');
    }

    public function requestQuotationApproval(Request $request, Lead $lead)
    {
        $user = Auth::user();
        $totalVal = $lead->requirements->sum(fn($r) => $r->qty * $r->unit_price);
        if ($totalVal == 0 && $lead->budget > 0) {
            $totalVal = $lead->budget;
        }

        $lead->update([
            'quotation_status' => 'pending_approval',
        ]);

        // Priority 3 Rule: Quotation system theke jawar aghe admin approval request asbe
        $admins = User::whereIn('role', ['owner', 'admin'])->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'icon' => 'file-invoice',
                'bg_color' => '#dbeafe',
                'message' => "Quotation Approval Request: {$user->name} requested approval for quotation on lead '{$lead->name}' (৳" . number_format($totalVal, 2) . ") before sending to client.",
                'is_admin_only' => true,
            ]);
        }

        LeadTimeline::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'type' => 'quotation',
            'text' => "Submitted Quotation for Admin Approval (Valued at ৳" . number_format($totalVal, 2) . ").",
        ]);

        return redirect()->route('crm.quotation', $lead->id)->with('success', 'Quotation approval request sent to Admin!');
    }

    public function approveQuotation(Request $request, Lead $lead)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return back()->with('error', 'Only Admins or Owners can approve quotations.');
        }

        $lead->update([
            'quotation_status' => 'approved',
            'quotation_approved_by' => $user->id,
            'quotation_approved_at' => now(),
        ]);

        $notifyTargets = array_unique(array_filter([$lead->assigned_to, $lead->created_by]));
        foreach ($notifyTargets as $targetId) {
            Notification::create([
                'user_id' => $targetId,
                'icon' => 'check-circle',
                'bg_color' => '#d1fae5',
                'message' => "Quotation Approved! Admin ({$user->name}) approved the quotation for '{$lead->name}'. You can now download or send the proposal PDF to the client.",
                'is_admin_only' => false,
            ]);
        }

        LeadTimeline::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'type' => 'quotation',
            'text' => "Quotation officially approved by Admin ({$user->name}). Ready for client dispatch.",
        ]);

        return redirect()->route('crm.quotation', $lead->id)->with('success', 'Quotation approved successfully!');
    }

    public function rejectQuotation(Request $request, Lead $lead)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return back()->with('error', 'Only Admins or Owners can reject quotations.');
        }

        $reason = $request->input('reason', 'Revisions requested by Admin.');

        $lead->update([
            'quotation_status' => 'rejected',
            'quotation_notes' => ($lead->quotation_notes ? $lead->quotation_notes . "\n\n" : '') . "Revision Feedback: " . $reason,
        ]);

        $notifyTargets = array_unique(array_filter([$lead->assigned_to, $lead->created_by]));
        foreach ($notifyTargets as $targetId) {
            Notification::create([
                'user_id' => $targetId,
                'icon' => 'exclamation-circle',
                'bg_color' => '#fee2e2',
                'message' => "Quotation Revision Required for '{$lead->name}': {$reason}",
                'is_admin_only' => false,
            ]);
        }

        LeadTimeline::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'type' => 'quotation',
            'text' => "Quotation rejected/revision requested: {$reason}",
        ]);

        return redirect()->route('crm.quotation', $lead->id)->with('warning', 'Quotation sent back for revision.');
    }

    public function exportLeads(Request $request)
    {
        $user = Auth::user();

        // Priority 7 Rule: Sales member cannot download any list in PDF and in Excel (view-only)
        if ($user && $user->isSales()) {
            abort(403, 'Access denied: Sales representatives have view-only access and are not permitted to download or export lists.');
        }

        $leads = Lead::with(['stage', 'assignedTo', 'createdBy'])->latest()->get();

        $filename = "leads_export_" . date('Y-m-d_His') . ".csv";
        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"{$filename}\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($leads) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['ID', 'Lead Name', 'Company', 'Phone', 'Email', 'Location', 'Source', 'Stage', 'Budget', 'Assigned Sales Rep', 'Created Date']);

            foreach ($leads as $l) {
                fputcsv($handle, [
                    $l->id,
                    $l->name,
                    $l->company ?? '',
                    $l->phone ?? '',
                    $l->email ?? '',
                    $l->location ?? '',
                    $l->source ?? '',
                    $l->stage->name ?? '',
                    $l->budget ?? 0,
                    $l->assignedTo->name ?? '',
                    $l->created_at->format('Y-m-d H:i')
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
