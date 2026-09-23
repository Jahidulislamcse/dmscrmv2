<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Mail, File};
use App\Models\{Reminder, ReminderTemplate, Invoice, Client, Notification};

class ReminderController extends Controller
{
    private function cleanTemplateBodies()
    {
        try {
            ReminderTemplate::all()->each(function ($tmpl) {
                if (str_contains($tmpl->body, '{{')) {
                    $cleaned = str_replace(
                        ['{{client_name}}', '{{company_name}}', '{{invoice_number}}', '{{amount}}', '{{due_date}}', '{{total_amount}}', '{{balance_due}}'],
                        ['{client_name}', '{company_name}', '{invoice_number}', '{balance_due}', '{due_date}', '{total_amount}', '{balance_due}'],
                        $tmpl->body
                    );
                    $tmpl->update(['body' => $cleaned]);
                }
            });
        } catch (\Throwable $e) {
            // Silence if DB table missing during early setup
        }
    }

    private function seedDefaultTemplatesIfNeeded()
    {
        $this->cleanTemplateBodies();

        if (ReminderTemplate::count() === 0) {
            ReminderTemplate::create([
                'name' => 'Friendly Payment Reminder',
                'type' => 'whatsapp',
                'days_before' => 3,
                'active' => true,
                'title' => 'Upcoming Invoice Due — {client_name}',
                'body' => "Assalamu Alaikum {client_name},\n\nThis is a friendly reminder from {agency_name} regarding Invoice #{invoice_number} for {balance_due}.\nDue Date: {due_date}\n\nView & Pay Invoice: {payment_link}\n\nThank you for working with us!",
            ]);

            ReminderTemplate::create([
                'name' => 'Urgent Overdue Notice',
                'type' => 'email',
                'days_before' => 0,
                'active' => true,
                'title' => 'URGENT PAYMENT NOTICE — Invoice #{invoice_number}',
                'body' => "URGENT PAYMENT NOTICE\n\nDear {client_name} ({company_name}),\n\nYour invoice #{invoice_number} was due on {due_date}. The balance of {balance_due} is now OVERDUE.\n\nPlease arrange payment immediately using this link: {payment_link}\n\nIf you have already paid, please ignore this notice.\n\nRegards,\n{agency_name} Finance Team",
            ]);

            ReminderTemplate::create([
                'name' => 'Advance / Partial Balance Notice',
                'type' => 'sms',
                'days_before' => 1,
                'active' => true,
                'title' => 'Payment Reminder: {invoice_number}',
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
        
        $numericAmount = number_format($invoice->balance > 0 ? $invoice->balance : $invoice->total, 2);
        $totalAmount = $currency . number_format($invoice->total, 2);
        $balanceDue = $currency . $numericAmount;
        $paymentLink = route('invoices.show', $invoice->id);

        $replacements = [
            // Double curly braces tags (e.g. {{client_name}})
            '{{client_name}}' => $clientName,
            '{{client}}' => $clientName,
            '{{company_name}}' => $companyName,
            '{{invoice_number}}' => $invoiceNumber,
            '{{invoice_no}}' => $invoiceNumber,
            '{{due_date}}' => $dueDate,
            '{{issued_date}}' => $issuedDate,
            '৳{{amount}}' => $balanceDue,
            '৳{{balance_due}}' => $balanceDue,
            '৳{{total_amount}}' => $totalAmount,
            '{{amount}}' => $balanceDue,
            '{{total_amount}}' => $totalAmount,
            '{{balance_due}}' => $balanceDue,
            '{{payment_link}}' => $paymentLink,
            '{{agency_name}}' => $agencyName,

            // Single curly braces tags (e.g. {client_name})
            '{client_name}' => $clientName,
            '{client}' => $clientName,
            '{company_name}' => $companyName,
            '{invoice_number}' => $invoiceNumber,
            '{invoice_no}' => $invoiceNumber,
            '{due_date}' => $dueDate,
            '{issued_date}' => $issuedDate,
            '৳{amount}' => $balanceDue,
            '৳{balance_due}' => $balanceDue,
            '৳{total_amount}' => $totalAmount,
            '{amount}' => $balanceDue,
            '{total_amount}' => $totalAmount,
            '{balance_due}' => $balanceDue,
            '{payment_link}' => $paymentLink,
            '{agency_name}' => $agencyName,
        ];

        $parsed = str_replace(array_keys($replacements), array_values($replacements), $body);

        // Sanitize any remaining double currency symbols (e.g. ৳৳8,500.00 -> ৳8,500.00)
        $parsed = str_replace($currency . $currency, $currency, $parsed);

        return $parsed;
    }

    public function preview(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'template_id' => 'nullable',
            'title' => 'nullable|string',
            'body' => 'nullable|string',
        ]);

        $invoice = Invoice::with('client')->findOrFail($request->invoice_id);
        $title = $request->title;
        $body = $request->body;

        if ((empty($title) || empty($body)) && $request->filled('template_id')) {
            $template = ReminderTemplate::find($request->template_id);
            if ($template) {
                if (empty($title)) $title = $template->title ?? "Upcoming Invoice Due — {client_name}";
                if (empty($body)) $body = $template->body;
            }
        }

        if (empty($title)) {
            $title = "Upcoming Invoice Due — {client_name}";
        }

        $parsedTitle = $this->parsePlaceholders($title, $invoice);
        $parsedBody = $this->parsePlaceholders($body ?? '', $invoice);

        return response()->json([
            'parsed_title' => $parsedTitle,
            'parsed_message' => $parsedBody,
            'client_phone' => $invoice->client->phone ?? '',
            'client_email' => $invoice->client->email ?? '',
        ]);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'template_id' => 'nullable',
            'channel' => 'required|in:whatsapp,email,sms,manual',
            'title' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $invoice = Invoice::with('client')->findOrFail($validated['invoice_id']);
        
        $templateId = !empty($validated['template_id']) ? $validated['template_id'] : null;
        $titleRaw = $validated['title'] ?? null;
        if (empty($titleRaw) && !empty($templateId)) {
            $template = ReminderTemplate::find($templateId);
            $titleRaw = $template->title ?? null;
        }
        if (empty($titleRaw)) {
            $titleRaw = "Upcoming Invoice Due — {client_name}";
        }

        $title = $this->parsePlaceholders($titleRaw, $invoice);
        $message = $this->parsePlaceholders($validated['message'], $invoice);

        $reminder = Reminder::create([
            'invoice_id' => $invoice->id,
            'template_id' => $templateId,
            'sent_by' => Auth::id(),
            'channel' => $validated['channel'],
            'title' => $title,
            'message' => $message,
            'sent_at' => now(),
        ]);

        // Trigger Notification
        Notification::create([
            'user_id' => Auth::id(),
            'icon' => 'bell',
            'bg_color' => '#fef3c7',
            'message' => "Payment reminder '{$title}' sent for Invoice {$invoice->invoice_number} ({$invoice->client->name}) via " . strtoupper($validated['channel']),
            'is_admin_only' => true,
        ]);

        // Handle Channel specifics
        $whatsappUrl = null;
        if ($validated['channel'] === 'whatsapp') {
            $phone = preg_replace('/[^0-9]/', '', $invoice->client->phone ?? '');
            if (!empty($phone)) {
                if (strlen($phone) === 11 && str_starts_with($phone, '01')) {
                    $phone = '88' . $phone;
                }
                $whatsappUrl = 'https://wa.me/' . $phone . '?text=' . urlencode($message);
            }
        } elseif ($validated['channel'] === 'email') {
            try {
                if (!empty($invoice->client->email)) {
                    Mail::raw($message, function ($mail) use ($invoice, $title) {
                        $mail->to($invoice->client->email)
                             ->subject($title);
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

        return redirect()->back()->with('success', "Payment reminder '{$title}' for Invoice {$invoice->invoice_number} logged successfully via " . strtoupper($validated['channel']) . "!");
    }

    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'type' => 'required|in:email,whatsapp,sms',
            'body' => 'required|string',
            'days_before' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $template = ReminderTemplate::create([
            'name' => $validated['name'],
            'title' => $validated['title'] ?? null,
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
            'title' => 'nullable|string|max:255',
            'type' => 'required|in:email,whatsapp,sms',
            'body' => 'required|string',
            'days_before' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $template->update([
            'name' => $validated['name'],
            'title' => $validated['title'] ?? null,
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
