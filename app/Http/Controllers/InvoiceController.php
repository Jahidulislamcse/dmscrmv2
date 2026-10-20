<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Invoice, Client, Service, InvoiceItem, User, Notification};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'createdBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest('issued_date')->paginate(12);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $services = Service::where('active', true)->get();

        return view('invoices.create', compact('clients', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'issued_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issued_date',
            'advance_paid' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_name' => 'required|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['unit_price']);
        $advance = $validated['advance_paid'] ?? 0;
        $total = $subtotal;
        $balance = max(0, $total - $advance);
        $status = $balance <= 0 ? 'paid' : ($advance > 0 ? 'partial' : 'unpaid');
        $invoiceNumber = 'INV-' . date('Ym') . '-' . str_pad(Invoice::count() + 1, 3, '0', STR_PAD_LEFT);

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'client_id' => $validated['client_id'],
            'created_by' => Auth::id(),
            'subtotal' => $subtotal,
            'advance_paid' => $advance,
            'total' => $total,
            'balance' => $balance,
            'status' => $status,
            'notes' => $validated['notes'] ?? null,
            'issued_date' => $validated['issued_date'],
            'due_date' => $validated['due_date'],
        ]);

        foreach ($validated['items'] as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'service_name' => $item['service_name'],
                'qty' => $item['qty'],
                'unit_price' => $item['unit_price'],
                'total' => $item['qty'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('invoices.index')->with('success', "Invoice {$invoiceNumber} created!");
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'createdBy', 'items', 'reminders.sentBy']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * UI Action to trigger monthly retainer recurring billing
     */
    public function runRecurringBilling(Request $request)
    {
        $user = Auth::user();
        if (!$user->isOwner() && !$user->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $month = $request->input('month', now()->format('Y-m'));
        $result = self::generateRecurringInvoices($month);

        return redirect()->route('invoices.index')->with(
            'success',
            "Recurring Retainer Billing executed! Generated {$result['generated_count']} invoice(s) totaling ৳" . number_format($result['total_amount'], 2) . " for " . Carbon::parse("{$month}-01")->format('F Y') . "."
        );
    }

    /**
     * Core Automated Recurring Retainer Billing Generator Engine
     * Triggered automatically on the 1st of every month via cron schedule ('invoices:generate-recurring')
     */
    public static function generateRecurringInvoices(?string $targetMonth = null): array
    {
        $targetMonth = $targetMonth ?: now()->format('Y-m');
        $activeClients = Client::where('status', 'active')
            ->whereIn('billing_cycle', ['monthly', 'quarterly'])
            ->with('services')
            ->get();

        $generatedCount = 0;
        $totalAmount = 0;
        $createdInvoices = [];

        foreach ($activeClients as $client) {
            // Check if an invoice was already issued for this client in the target month
            $exists = Invoice::where('client_id', $client->id)
                ->where('issued_date', 'like', "{$targetMonth}%")
                ->exists();

            if ($exists) {
                continue;
            }

            // Gather itemized lines from client subscribed services
            $items = [];
            foreach ($client->services as $srv) {
                $qty = $srv->pivot->qty ?: 1;
                $unitPrice = $srv->pivot->price ?: $srv->default_price;
                $serviceName = $srv->pivot->custom_name ?: $srv->name;
                $items[] = [
                    'service_name' => $serviceName,
                    'qty' => (int) $qty,
                    'unit_price' => (float) $unitPrice,
                    'total' => (float) ($qty * $unitPrice),
                ];
            }

            if (empty($items)) {
                continue;
            }

            $subtotal = collect($items)->sum('total');
            $advance = $client->advance > 0 ? min($client->advance, $subtotal) : 0;
            $total = $subtotal;
            $balance = max(0, $total - $advance);
            $status = $balance <= 0 ? 'paid' : ($advance > 0 ? 'partial' : 'unpaid');

            // Sequential invoice numbering: INV-YYYYMM-XXX
            $monthClean = str_replace('-', '', $targetMonth);
            $countThisMonth = Invoice::where('invoice_number', 'like', "INV-{$monthClean}%")->count() + 1;
            $invoiceNumber = "INV-{$monthClean}-" . str_pad($countThisMonth, 3, '0', STR_PAD_LEFT);

            if (Invoice::where('invoice_number', $invoiceNumber)->exists()) {
                $invoiceNumber = "INV-{$monthClean}-" . str_pad(Invoice::count() + 1, 3, '0', STR_PAD_LEFT);
            }

            $issuedDate = Carbon::parse("{$targetMonth}-01")->toDateString();
            $dueDate = Carbon::parse("{$targetMonth}-01")->addDays(10)->toDateString();
            $adminUser = User::whereIn('role', ['owner', 'admin'])->first();

            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'client_id' => $client->id,
                'created_by' => Auth::id() ?: ($adminUser ? $adminUser->id : 1),
                'subtotal' => $subtotal,
                'advance_paid' => $advance,
                'total' => $total,
                'balance' => $balance,
                'status' => $status,
                'notes' => "Automated Monthly Retainer Billing for " . Carbon::parse("{$targetMonth}-01")->format('F Y'),
                'issued_date' => $issuedDate,
                'due_date' => $dueDate,
            ]);

            foreach ($items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'service_name' => $item['service_name'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                ]);
            }

            if ($advance > 0) {
                $client->decrement('advance', $advance);
            }

            Notification::create([
                'user_id' => null,
                'icon' => 'file-invoice-dollar',
                'bg_color' => 'bg-emerald-500',
                'message' => "📋 Monthly Retainer Generated: {$invoiceNumber} for {$client->name} (৳" . number_format($total) . ").",
                'is_admin_only' => true,
                'is_read' => false,
            ]);

            $generatedCount++;
            $totalAmount += $total;
            $createdInvoices[] = $invoice;
        }

        return [
            'generated_count' => $generatedCount,
            'total_amount' => $totalAmount,
            'invoices' => $createdInvoices,
        ];
    }
}
