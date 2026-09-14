@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number)
@section('header_title', 'Invoice Details — ' . $invoice->invoice_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between print:hidden">
        <a href="{{ route('invoices.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1">
            <i class="fa fa-arrow-left"></i> Back to Invoices List
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-2">
            <i class="fa fa-print"></i> Print Invoice
        </button>
    </div>

    <!-- Printable Invoice Card -->
    @php $agencySettings = \App\Http\Controllers\SettingsController::getAgencySettings(); @endphp
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-8 space-y-8 print:shadow-none print:border-none print:p-0">
        <!-- Header -->
        <div class="flex items-start justify-between pb-6 border-b border-slate-100">
            <div>
                <div class="inline-flex items-center gap-3 text-xl font-extrabold text-slate-900 tracking-tight mb-1">
                    @if(!empty($agencySettings['agency_logo']))
                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 p-1 flex items-center justify-center overflow-hidden shadow-sm">
                            <img src="{{ asset(ltrim($agencySettings['agency_logo'], '/')) }}" alt="Logo" class="max-h-full max-w-full object-contain">
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-sm font-extrabold shadow-md shadow-amber-500/20">
                            {{ strtoupper(substr($agencySettings['agency_name'] ?? 'D', 0, 1)) }}
                        </div>
                    @endif
                    <span>{{ $agencySettings['agency_name'] ?? 'DMS Creative Agency' }}</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    {{ $agencySettings['agency_address'] ?? 'Dhaka, Bangladesh' }}
                    @if(!empty($agencySettings['agency_email']))
                        · <a href="mailto:{{ $agencySettings['agency_email'] }}" class="text-slate-600 font-semibold">{{ $agencySettings['agency_email'] }}</a>
                    @endif
                    @if(!empty($agencySettings['agency_phone']))
                        · <span>{{ $agencySettings['agency_phone'] }}</span>
                    @endif
                </p>
            </div>

            <div class="text-right">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $invoice->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }} mb-2">
                    {{ $invoice->status }}
                </span>
                <h3 class="text-xl font-extrabold text-slate-900 font-mono tracking-tight">{{ $invoice->invoice_number }}</h3>
                <p class="text-xs text-slate-400 mt-1">Issued: {{ $invoice->issued_date->format('M d, Y') }} · Due: {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '—' }}</p>
            </div>
        </div>

        <!-- Billed To & Issued By -->
        <div class="grid grid-cols-2 gap-6 text-xs">
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Billed To</span>
                <h4 class="text-sm font-bold text-slate-900">{{ $invoice->client->name ?? '—' }}</h4>
                <p class="text-slate-500 font-medium">{{ $invoice->client->company ?? '' }}</p>
                <p class="text-slate-400">{{ $invoice->client->phone ?? '' }} · {{ $invoice->client->email ?? '' }}</p>
            </div>

            <div class="text-right">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Issued By</span>
                <h4 class="text-sm font-bold text-slate-900">{{ $invoice->createdBy->name ?? 'Admin' }}</h4>
                <p class="text-slate-500">{{ $agencySettings['agency_name'] ?? 'DMS Creative Agency' }}</p>
                @if(!empty($agencySettings['tax_id']))
                <p class="text-[11px] text-slate-400 font-mono">Tax / BIN ID: {{ $agencySettings['tax_id'] }}</p>
                @endif
            </div>
        </div>

        <!-- Billed Items Table -->
        <div class="border border-slate-200/80 rounded-xl overflow-hidden">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 font-bold text-slate-400 uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Item Description</th>
                        <th class="py-3 px-4 text-center">Qty</th>
                        <th class="py-3 px-4 text-right">Unit Price (৳)</th>
                        <th class="py-3 px-4 text-right">Total (৳)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @foreach($invoice->items as $item)
                    <tr>
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $item->service_name }}</td>
                        <td class="py-3 px-4 text-center">{{ $item->qty }}</td>
                        <td class="py-3 px-4 text-right font-mono">৳{{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-3 px-4 text-right font-mono font-bold">৳{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals Summary -->
        <div class="flex flex-col sm:flex-row items-start justify-between gap-6 pt-4 border-t border-slate-100 text-xs">
            <div class="space-y-1 text-slate-500 max-w-sm">
                <span class="block font-bold text-slate-700">Notes & Payment Terms</span>
                <p>{{ $invoice->notes ?? 'Thank you for your business!' }}</p>
            </div>

            <div class="w-full sm:w-64 space-y-2 font-medium">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal:</span>
                    <span class="font-mono font-bold text-slate-900">৳{{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-emerald-600">
                    <span>Advance Credit Paid:</span>
                    <span class="font-mono font-bold">-৳{{ number_format($invoice->advance_paid, 2) }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-slate-200 text-sm font-bold text-slate-900">
                    <span>Balance Due:</span>
                    <span class="font-mono text-rose-600">৳{{ number_format($invoice->balance, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
