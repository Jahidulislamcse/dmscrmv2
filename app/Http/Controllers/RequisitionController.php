<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Requisition, Client, ClientService, Service, Task, User, Notification};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequisitionController extends Controller
{
    public function index(Request $request)
    {
        $query = Requisition::with(['submittedBy', 'assignedSmm', 'items', 'convertedClient']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requisitions = $query->latest()->paginate(10);
        $smmUsers = User::where('role', 'smm')->where('active', true)->get();
        $teamUsers = User::where('active', true)->where('role', '!=', 'owner')->get();

        return view('requisitions.index', compact('requisitions', 'smmUsers', 'teamUsers'));
    }

    public function show(Requisition $requisition)
    {
        $requisition->load(['submittedBy', 'reviewedBy', 'assignedSmm', 'items', 'team', 'convertedClient']);
        $smmUsers = User::where('role', 'smm')->where('active', true)->get();
        $teamUsers = User::where('active', true)->where('role', '!=', 'owner')->get();

        return view('requisitions.show', compact('requisition', 'smmUsers', 'teamUsers'));
    }

    public function approve(Request $request, Requisition $requisition)
    {
        $request->validate([
            'assigned_smm' => 'required|exists:users,id',
            'admin_notes' => 'nullable|string',
            'team' => 'nullable|array',
            'team.*' => 'exists:users,id',
        ]);

        DB::transaction(function() use ($request, $requisition) {
            // Update requisition state
            $requisition->update([
                'status' => 'approved',
                'assigned_smm' => $request->assigned_smm,
                'admin_notes' => $request->admin_notes,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            if (!empty($request->team)) {
                $requisition->team()->sync($request->team);
            }

            // Perform automatic Client Activation if not already converted
            if (!$requisition->converted_client_id) {
                $client = Client::create([
                    'name' => $requisition->client_name,
                    'company' => $requisition->company,
                    'phone' => $requisition->phone,
                    'email' => $requisition->email,
                    'location' => $requisition->location,
                    'assigned_smm' => $requisition->assigned_smm,
                    'assigned_sales' => $requisition->submitted_by,
                    'billing_cycle' => $requisition->billing_cycle,
                    'advance' => $requisition->advance_paid,
                    'notes' => $requisition->special_notes,
                    'status' => 'active',
                    'onboarded_at' => now()->toDateString(),
                ]);

                // Create client services
                foreach ($requisition->items as $item) {
                    $service = Service::firstOrCreate(
                        ['name' => $item->service_name],
                        ['base_price' => $item->unit_price, 'unit' => 'month', 'active' => true]
                    );

                    ClientService::create([
                        'client_id' => $client->id,
                        'service_id' => $service->id,
                        'price' => $item->unit_price,
                        'qty' => $item->qty,
                        'custom_name' => $item->service_name,
                    ]);
                }

                // Create initial onboard tasks for assigned team members
                $teamMembers = $requisition->team;
                if ($teamMembers->count() > 0) {
                    foreach ($teamMembers as $member) {
                        Notification::create([
                            'user_id' => $member->id,
                            'icon' => 'users',
                            'bg_color' => '#e0e7ff',
                            'message' => "Project Assignment: Superadmin assigned you to the project team for client: {$client->name}.",
                            'is_admin_only' => false,
                        ]);

                        foreach ($requisition->items as $item) {
                            Task::create([
                                'title' => "Onboarding Task: {$item->service_name} — {$client->name}",
                                'client_id' => $client->id,
                                'assigned_to' => $member->id,
                                'assigned_by' => Auth::id(),
                                'priority' => 'high',
                                'status' => 'pending',
                                'notes' => $item->notes ?? 'Initial onboard setup task.',
                                'deadline' => now()->addDays(7)->toDateString(),
                            ]);
                        }
                    }
                } else if ($requisition->assigned_smm) {
                    Task::create([
                        'title' => "Setup Content Calendar & Brand Guidelines — {$client->name}",
                        'client_id' => $client->id,
                        'assigned_to' => $requisition->assigned_smm,
                        'assigned_by' => Auth::id(),
                        'priority' => 'high',
                        'status' => 'pending',
                        'notes' => $requisition->special_notes ?? 'Set up social media calendar.',
                        'deadline' => now()->addDays(5)->toDateString(),
                    ]);
                }

                $requisition->update(['converted_client_id' => $client->id]);

                Notification::create([
                    'user_id' => $requisition->assigned_smm,
                    'icon' => 'user-check',
                    'bg_color' => '#d1fae5',
                    'message' => "New client activated: {$client->name}! Assigned to your account.",
                    'is_admin_only' => false,
                ]);
            }
        });

        return redirect()->route('requisitions.index')->with('success', "Requisition approved & Client activated!");
    }

    public function reject(Request $request, Requisition $requisition)
    {
        $request->validate(['reason' => 'required|string']);

        $requisition->update([
            'status' => 'rejected',
            'admin_notes' => $request->reason,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('requisitions.index')->with('success', "Requisition rejected.");
    }
}
