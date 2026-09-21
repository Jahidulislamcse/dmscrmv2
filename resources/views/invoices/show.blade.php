@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number)
@section('header_title', 'Tax Invoice — ' . $invoice->invoice_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Action Buttons (Hidden on Print) -->
    <div class="flex items-center justify-between print:hidden">
        <a href="{{ route('invoices.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition-colors">
            <i class="fa fa-arrow-left"></i> Back to Invoices Overview
        </a>
        <div class="flex items-center gap-2">
            @if($invoice->balance > 0 && auth()->user()->canAccess('reminders'))
            <a href="{{ route('reminders.index') }}" class="px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
                <i class="fa fa-bell"></i> Send Payment Reminder
            </a>
            @endif
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                <i class="fa fa-print"></i> Print / Download PDF
            </button>
        </div>
    </div>

    <!-- Formal Printable Invoice Document Card -->
    @php 
        $agencySettings = \App\Http\Controllers\SettingsController::getAgencySettings(); 
        $currency = $agencySettings['agency_currency'] ?? '৳';
    @endphp

    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-lg overflow-hidden print:shadow-none print:border-none print:p-0">
        <!-- Top Accent Strip -->
        <div class="h-2 bg-gradient-to-r from-slate-800 via-slate-900 to-amber-600 print:hidden"></div>

        <div class="p-8 md:p-10 space-y-8">
            <!-- Header Section: Logo & Document Title -->
            <div class="flex flex-col sm:flex-row items-start justify-between gap-6 pb-6 border-b border-slate-200/80">
                <!-- Agency Branding & Info -->
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        @if(!empty($agencySettings['agency_logo']))
                            <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 p-1 flex items-center justify-center overflow-hidden shadow-sm">
                                <img src="{{ asset(ltrim($agencySettings['agency_logo'], '/')) }}" alt="Agency Logo" class="max-h-full max-w-full object-contain">
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center text-lg font-extrabold shadow-md">
                                {{ strtoupper(substr($agencySettings['agency_name'] ?? 'A', 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $agencySettings['agency_name'] ?? 'DMS Creative Agency' }}</h1>
                            <p class="text-xs font-semibold text-slate-500">Official Tax Invoice</p>
                        </div>
                    </div>

                    <div class="text-xs text-slate-500 leading-relaxed space-y-0.5">
                        <p><i class="fa fa-location-dot w-4 text-slate-400"></i> {{ $agencySettings['agency_address'] ?? 'Dhaka, Bangladesh' }}</p>
                        @if(!empty($agencySettings['agency_email']))
                        <p><i class="fa fa-envelope w-4 text-slate-400"></i> {{ $agencySettings['agency_email'] }}</p>
                        @endif
                        @if(!empty($agencySettings['agency_phone']))
                        <p><i class="fa fa-phone w-4 text-slate-400"></i> {{ $agencySettings['agency_phone'] }}</p>
                        @endif
                        @if(!empty($agencySettings['tax_id']))
                        <p class="font-mono text-[11px] text-slate-600"><i class="fa fa-id-card w-4 text-slate-400"></i> Tax / BIN ID: <strong>{{ $agencySettings['tax_id'] }}</strong></p>
                        @endif
                    </div>
                </div>

                <!-- Invoice Meta & Status -->
                <div class="sm:text-right space-y-2">
                    <div class="inline-block">
                        <span class="text-xs font-black uppercase tracking-widest text-slate-400 block mb-1">INVOICE</span>
                        <h2 class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ $invoice->invoice_number }}</h2>
                    </div>

                    <div class="pt-1">
                        @if(strtolower($invoice->status) === 'paid')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 uppercase tracking-wider">
                                <i class="fa fa-circle-check mr-1.5"></i> Paid
                            </span>
                        @elseif(strtolower($invoice->status) === 'partial')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300 uppercase tracking-wider">
                                <i class="fa fa-clock mr-1.5"></i> Partial Payment
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-rose-100 text-rose-800 border border-rose-300 uppercase tracking-wider">
                                <i class="fa fa-triangle-exclamation mr-1.5"></i> Unpaid / Due
                            </span>
                        @endif
                    </div>

                    <div class="text-xs text-slate-500 space-y-0.5 pt-2">
                        <p><strong>Issued Date:</strong> {{ $invoice->issued_date->format('F d, Y') }}</p>
                        <p><strong>Due Date:</strong> {{ $invoice->due_date ? $invoice->due_date->format('F d, Y') : 'Upon Receipt' }}</p>
                    </div>
                </div>
            </div>

            <!-- Billed To & Issued By Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-5 bg-slate-50/80 border border-slate-200/80 rounded-xl text-xs">
                <!-- Billed To -->
                <div class="space-y-1.5">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Billed To (Client Details)</span>
                    <h3 class="text-sm font-bold text-slate-900">{{ $invoice->client->name ?? 'Client' }}</h3>
                    @if(!empty($invoice->client->company))
                    <p class="font-bold text-slate-700">{{ $invoice->client->company }}</p>
                    @endif
                    <div class="text-slate-500 space-y-0.5">
                        @if(!empty($invoice->client->email))
                        <p><i class="fa fa-envelope text-slate-400 mr-1.5"></i> {{ $invoice->client->email }}</p>
                        @endif
                        @if(!empty($invoice->client->phone))
                        <p><i class="fa fa-phone text-slate-400 mr-1.5"></i> {{ $invoice->client->phone }}</p>
                        @endif
                        @if(!empty($invoice->client->address))
                        <p><i class="fa fa-location-dot text-slate-400 mr-1.5"></i> {{ $invoice->client->address }}</p>
                        @endif
                    </div>
                </div>

                <!-- Issued By -->
                <div class="space-y-1.5 sm:text-right">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Issued By (Service Provider)</span>
                    <h3 class="text-sm font-bold text-slate-900">{{ $agencySettings['agency_name'] ?? 'DMS Creative Agency' }}</h3>
                    <p class="text-slate-600 font-semibold">Prepared by: {{ $invoice->createdBy->name ?? 'Billing Department' }}</p>
                    <div class="text-slate-500 space-y-0.5">
                        <p>{{ $agencySettings['agency_email'] ?? 'info@dmssoftware.agency' }}</p>
                        <p>{{ $agencySettings['agency_phone'] ?? '+880-171-0000000' }}</p>
                    </div>
                </div>
            </div>

            <!-- Formal Items Table -->
            <div class="space-y-3">
                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white font-bold uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 w-12 text-center">#</th>
                                <th class="py-3 px-4">Service Item / Description</th>
                                <th class="py-3 px-4 text-center w-24">Qty</th>
                                <th class="py-3 px-4 text-right w-32">Unit Price ({{ $currency }})</th>
                                <th class="py-3 px-4 text-right w-36">Total Amount ({{ $currency }})</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                            @forelse($invoice->items as $index => $item)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 text-center font-mono text-slate-400 font-bold">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    {{ $item->service_name }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-bold">{{ $item->qty }}</td>
                                <td class="py-3.5 px-4 text-right font-mono">{{ $currency }}{{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-900">{{ $currency }}{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    No item breakdown attached to this invoice.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Financial Totals & Payment Summary -->
            <div class="flex flex-col sm:flex-row items-start justify-between gap-6 pt-2 text-xs">
                <!-- Payment Notes & Instructions -->
                <div class="space-y-2 max-w-sm">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Notes & Terms of Service</span>
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 text-slate-600 space-y-1 text-[11px] leading-relaxed">
                        <p>{{ $invoice->notes ?? 'Payment is due as per agreed billing cycle. Please reference invoice number when sending payment.' }}</p>
                        <p class="text-slate-400 text-[10px] pt-1 border-t border-slate-200/60">Thank you for your business!</p>
                    </div>
                </div>

                <!-- Financial Calculation Card -->
                <div class="w-full sm:w-72 bg-slate-50 border border-slate-200/90 rounded-xl p-4 space-y-2 font-medium">
                    <div class="flex justify-between text-slate-600 pb-1">
                        <span>Gross Subtotal:</span>
                        <span class="font-mono font-bold text-slate-900">{{ $currency }}{{ number_format($invoice->subtotal, 2) }}</span>
                    </div>

                    @if($invoice->advance_paid > 0)
                    <div class="flex justify-between text-emerald-700 pb-1">
                        <span>Advance / Credit Paid:</span>
                        <span class="font-mono font-bold">-{{ $currency }}{{ number_format($invoice->advance_paid, 2) }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between pt-2 border-t border-slate-300/80 text-sm font-black text-slate-900">
                        <span>Net Balance Due:</span>
                        <span class="font-mono {{ $invoice->balance > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ $currency }}{{ number_format($invoice->balance, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Signature & Seal Section (Formal Corporate Invoice Standard) -->
            <div class="pt-12 flex items-center justify-between border-t border-slate-200 text-xs">
                <div class="text-slate-400 text-[10px]">
                    <p class="font-bold text-slate-500">Computer Generated Document</p>
                    <p>Issued by {{ $agencySettings['agency_name'] ?? 'DMS Creative Agency' }} Management System</p>
                </div>

                <div class="text-center space-y-2">
                    <div class="w-48 border-b-2 border-slate-400 pb-1"></div>
                    <span class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider">Authorized Signature</span>
                    <span class="block text-[10px] text-slate-400">{{ $agencySettings['agency_name'] ?? 'DMS Creative Agency' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Reminders Sent History (Hidden on Print) -->
    @if($invoice->reminders->count() > 0)
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-4 shadow-sm print:hidden">
        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
            <i class="fa fa-history text-amber-500"></i> Payment Reminders Sent for This Invoice ({{ $invoice->reminders->count() }})
        </h3>

        <div class="space-y-3">
            @foreach($invoice->reminders as $rem)
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 text-xs space-y-1.5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 font-bold text-slate-800">
                        @if($rem->channel === 'whatsapp')
                            <span class="px-2 py-0.5 rounded text-[10px] bg-emerald-100 text-emerald-800 border border-emerald-300">WhatsApp</span>
                        @elseif($rem->channel === 'email')
                            <span class="px-2 py-0.5 rounded text-[10px] bg-blue-100 text-blue-800 border border-blue-300">Email</span>
                        @elseif($rem->channel === 'sms')
                            <span class="px-2 py-0.5 rounded text-[10px] bg-purple-100 text-purple-800 border border-purple-300">SMS</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] bg-slate-200 text-slate-700">Manual</span>
                        @endif
                        <span>Dispatched by {{ $rem->sentBy->name ?? 'Staff' }}</span>
                    </div>
                    <span class="text-[11px] text-slate-400 font-mono">{{ $rem->sent_at ? $rem->sent_at->format('M d, Y h:i A') : $rem->created_at->format('M d, Y') }}</span>
                </div>
                <p class="text-slate-700 font-medium whitespace-pre-line leading-relaxed pl-2 border-l-2 border-amber-400">{{ $rem->message }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
