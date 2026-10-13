<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Client, Service, User, ClientService};

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with(['assignedSmm', 'assignedSales', 'clientServices']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        $clients = $query->latest()->paginate(10);
        $smmUsers = User::where('role', 'smm')->where('active', true)->get();
        $salesUsers = User::where('role', 'sales')->where('active', true)->get();

        return view('clients.index', compact('clients', 'smmUsers', 'salesUsers'));
    }

    public function create()
    {
        $services = Service::where('active', true)->get();
        $smmUsers = User::where('role', 'smm')->where('active', true)->get();
        $salesUsers = User::where('role', 'sales')->where('active', true)->get();

        return view('clients.create', compact('services', 'smmUsers', 'salesUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'assigned_smm' => 'nullable|exists:users,id',
            'assigned_sales' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive,onboarding,paused',
            'billing_cycle' => 'required|in:monthly,quarterly,one-time',
            'advance' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.price' => 'required|numeric|min:0',
            'services.*.qty' => 'required|integer|min:1',
        ]);

        $client = Client::create([
            'name' => $validated['name'],
            'company' => $validated['company'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'location' => $validated['location'] ?? null,
            'assigned_smm' => $validated['assigned_smm'] ?? null,
            'assigned_sales' => $validated['assigned_sales'] ?? null,
            'status' => $validated['status'],
            'billing_cycle' => $validated['billing_cycle'],
            'advance' => $validated['advance'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'onboarded_at' => now()->toDateString(),
        ]);

        if (!empty($validated['services'])) {
            foreach ($validated['services'] as $svc) {
                ClientService::create([
                    'client_id' => $client->id,
                    'service_id' => $svc['service_id'],
                    'price' => $svc['price'],
                    'qty' => $svc['qty'],
                ]);
            }
        }

        return redirect()->route('clients.index')->with('success', "Client '{$client->name}' created successfully!");
    }

    public function show(Client $client)
    {
        $client->load(['assignedSmm', 'assignedSales', 'clientServices.service', 'invoices', 'tasks.assignedTo', 'contentPosts']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $client->load('clientServices');
        $services = Service::where('active', true)->get();
        $smmUsers = User::where('role', 'smm')->where('active', true)->get();
        $salesUsers = User::where('role', 'sales')->where('active', true)->get();

        return view('clients.edit', compact('client', 'services', 'smmUsers', 'salesUsers'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'assigned_smm' => 'nullable|exists:users,id',
            'assigned_sales' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive,onboarding,paused',
            'billing_cycle' => 'required|in:monthly,quarterly,one-time',
            'advance' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.price' => 'required|numeric|min:0',
            'services.*.qty' => 'required|integer|min:1',
        ]);

        $client->update([
            'name' => $validated['name'],
            'company' => $validated['company'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'location' => $validated['location'] ?? null,
            'assigned_smm' => $validated['assigned_smm'] ?? null,
            'assigned_sales' => $validated['assigned_sales'] ?? null,
            'status' => $validated['status'],
            'billing_cycle' => $validated['billing_cycle'],
            'advance' => $validated['advance'] ?? 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Sync services
        ClientService::where('client_id', $client->id)->delete();
        if (!empty($validated['services'])) {
            foreach ($validated['services'] as $svc) {
                ClientService::create([
                    'client_id' => $client->id,
                    'service_id' => $svc['service_id'],
                    'price' => $svc['price'],
                    'qty' => $svc['qty'],
                ]);
            }
        }

        return redirect()->route('clients.show', $client->id)->with('success', "Client updated successfully!");
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', "Client '{$client->name}' deleted successfully!");
    }

    public function exportClients(Request $request)
    {
        $user = auth()->user();

        // Priority 7 Rule: Sales member cannot download any list in PDF and in Excel (view-only)
        if ($user && $user->isSales()) {
            abort(403, 'Access denied: Sales representatives have view-only access and are not permitted to download or export lists.');
        }

        $clients = Client::with(['assignedSmm', 'assignedSales', 'clientServices'])->latest()->get();

        $filename = "clients_export_" . date('Y-m-d_His') . ".csv";
        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"{$filename}\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($clients) {
            $handle = fopen('php://output', 'w');
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($handle, ['ID', 'Client Name', 'Company', 'Phone', 'Email', 'Location', 'Status', 'Billing Cycle', 'Monthly Value (BDT)', 'Assigned SMM', 'Assigned Sales', 'Onboarded Date']);

            foreach ($clients as $c) {
                fputcsv($handle, [
                    $c->id,
                    $c->name,
                    $c->company ?? '',
                    $c->phone ?? '',
                    $c->email ?? '',
                    $c->location ?? '',
                    ucfirst($c->status),
                    ucfirst($c->billing_cycle),
                    $c->total_monthly_value ?? 0,
                    $c->assignedSmm->name ?? 'Unassigned',
                    $c->assignedSales->name ?? 'Unassigned',
                    $c->onboarded_at ? $c->onboarded_at->format('Y-m-d') : ''
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
