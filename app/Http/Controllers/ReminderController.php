<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Mail, File};
use App\Models\{Reminder, ReminderTemplate, Invoice, Client, Notification};

class ReminderController extends Controller
{
    private function seedDefaultTemplatesIfNeeded()
    {
        if (ReminderTemplate::count() === 0) {
            ReminderTemplate::create([
                'name' => 'Friendly Payment Reminder',
                'type' => 'whatsapp',
                'days_before' => 3,
                'active' => true,
                'body' => "Dear {client_name},\n\nThis is a friendly reminder from {agency_name} regarding Invoice #{invoice_number} for {total_amount}.\nDue Date: {due_date}\nBalance Due: {balance_due}\n\nView & Pay Invoice: {payment_link}\n\nThank you for working with us!",
            ]);

            ReminderTemplate::create([
                'name' => 'Urgent Overdue Notice',
                'type' => 'email',
                'days_before' => 0,
                'active' => true,
                'body' => "URGENT PAYMENT NOTICE\n\nDear {client_name} ({company_name}),\n\nYour invoice #{invoice_number} was due on {due_date}. The balance of {balance_due} is now OVERDUE.\n\nPlease arrange payment immediately using this link: {payment_link}\n\nIf you have already paid, please ignore this notice.\n\nRegards,\n{agency_name} Finance Team",
            ]);

            ReminderTemplate::create([
                'name' => 'Advance / Partial Balance Notice',
                'type' => 'sms',
                'days_before' => 1,
                'active' => true,
                'body' => "Hello {client_name}, please clear the remaining balance of {balance_due} for Invoice #{invoice_number} ({agency_name}). View: {payment_link}",
            ]);
        }
    }

    public function index(Request $request)
    {
        $this->seedDefaultTemplatesIfNeeded();

        // Metrics
        $totalSent = Reminder::count();
        $unpaidInvoices = Invoice::with('client')
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->where('balance', '>', 0)
            ->latest('due_date')
            ->get();

        $overdueInvoices = $unpaidInvoices->filter(fn($inv) => $inv->due_date && $inv->due_date->isPast());
        $totalOutstanding = $unpaidInvoices->sum('balance');

        // Sent Reminders History Log
        $remindersQuery = Reminder::with(['invoice.client', 'template', 'sentBy']);
        if ($request->filled('channel')) {
            $remindersQuery->where('channel', $request->channel);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $remindersQuery->whereHas('invoice', function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('client', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }
        $sentReminders = $remindersQuery->latest('sent_at')->paginate(15);

        // Templates
        $templates = ReminderTemplate::orderBy('name')->get();

        $agencySettings = SettingsController::getAgencySettings();

        return view('reminders.index', compact(
            'totalSent',
            'unpaidInvoices',
            'overdueInvoices',
            'totalOutstanding',
            'sentReminders',
            'templates',
            'agencySettings'
        ));
    }

    public function parsePlaceholders(string $body, Invoice $invoice): string
    {
        $agencySettings = SettingsController::getAgencySettings();
        $agencyName = $agencySettings['agency_name'] ?? 'DMS Creative Agency';
        $currency = $agencySettings['agency_currency'] ?? '৳';

        $clientName = $invoice->client->name ?? 'Valued Client';
        $companyName = $invoice->client->company ?? $clientName;
        $invoiceNumber = $invoice->invoice_number;
        $dueDate = $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'Upon Receipt';
        $issuedDate = $invoice->issued_date ? $invoice->issued_date->format('M d, Y') : 'Today';
        $totalAmount = $currency . number_format($invoice->total, 2);
        $balanceDue = $currency . number_format($invoice->balance, 2);
        $paymentLink = route('invoices.show', $invoice->id);

        $replacements = [
            '{client_name}' => $clientName,
            '{company_name}' => $companyName,
            '{invoice_number}' => $invoiceNumber,
            '{due_date}' => $dueDate,
            '{issued_date}' => $issuedDate,
            '{total_amount}' => $totalAmount,
            '{balance_due}' => $balanceDue,
            '{payment_link}' => $paymentLink,
            '{agency_name}' => $agencyName,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $body);
    }

    public function preview(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'template_id' => 'nullable|exists:reminder_templates,id',
            'body' => 'nullable|string',
        ]);

        $invoice = Invoice::with('client')->findOrFail($request->invoice_id);
        $body = $request->body;

        if (empty($body) && $request->filled('template_id')) {
            $template = ReminderTemplate::find($request->template_id);
            $body = $template ? $template->body : '';
        }

        $parsed = $this->parsePlaceholders($body ?? '', $invoice);

        return response()->json([
            'parsed_message' => $parsed,
            'client_phone' => $invoice->client->phone ?? '',
            'client_email' => $invoice->client->email ?? '',
        ]);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'template_id' => 'nullable|exists:reminder_templates,id',
            'channel' => 'required|in:whatsapp,email,sms,manual',
            'message' => 'required|string',
        ]);

        $invoice = Invoice::with('client')->findOrFail($validated['invoice_id']);
        $message = $this->parsePlaceholders($validated['message'], $invoice);

        $reminder = Reminder::create([
            'invoice_id' => $invoice->id,
            'template_id' => $validated['template_id'] ?? null,
            'sent_by' => Auth::id(),
            'channel' => $validated['channel'],
            'message' => $message,
            'sent_at' => now(),
        ]);

        // Trigger Notification
        Notification::create([
            'title' => 'Payment Reminder Sent',
            'message' => "Payment reminder sent for Invoice {$invoice->invoice_number} ({$invoice->client->name}) via " . strtoupper($validated['channel']),
            'user_id' => Auth::id(),
            'is_admin_only' => true,
        ]);

        // Handle Channel specifics
        $whatsappUrl = null;
        if ($validated['channel'] === 'whatsapp') {
            $phone = preg_replace('/[^0-9]/', '', $invoice->client->phone ?? '');
            if (!empty($phone)) {
                // Handle Bangladesh default country code if missing
                if (strlen($phone) === 11 && str_starts_with($phone, '01')) {
                    $phone = '88' . $phone;
                }
                $whatsappUrl = 'https://wa.me/' . $phone . '?text=' . urlencode($message);
            }
        } elseif ($validated['channel'] === 'email') {
            try {
                if (!empty($invoice->client->email)) {
                    Mail::raw($message, function ($mail) use ($invoice) {
                        $mail->to($invoice->client->email)
                             ->subject("Payment Reminder: Invoice #{$invoice->invoice_number}");
                    });
                }
            } catch (\Throwable $e) {
                // Email logging fallback
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment reminder recorded successfully!',
                'reminder' => $reminder->load(['invoice.client', 'sentBy']),
                'whatsapp_url' => $whatsappUrl,
            ]);
        }

        if ($whatsappUrl && $request->input('open_whatsapp') == '1') {
            return redirect()->away($whatsappUrl);
        }

        return redirect()->back()->with('success', "Payment reminder for Invoice {$invoice->invoice_number} logged successfully via " . strtoupper($validated['channel']) . "!");
    }

    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,whatsapp,sms',
            'body' => 'required|string',
            'days_before' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $template = ReminderTemplate::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'body' => $validated['body'],
            'days_before' => $validated['days_before'] ?? 0,
            'active' => $request->boolean('active', true),
        ]);

        return redirect()->route('reminders.index')->with('success', "Reminder template '{$template->name}' created!");
    }

    public function updateTemplate(Request $request, ReminderTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,whatsapp,sms',
            'body' => 'required|string',
            'days_before' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $template->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'body' => $validated['body'],
            'days_before' => $validated['days_before'] ?? 0,
            'active' => $request->boolean('active', true),
        ]);

        return redirect()->route('reminders.index')->with('success', "Reminder template '{$template->name}' updated!");
    }

    public function destroyTemplate(ReminderTemplate $template)
    {
        $template->delete();
        return redirect()->route('reminders.index')->with('success', 'Reminder template deleted!');
    }
}
