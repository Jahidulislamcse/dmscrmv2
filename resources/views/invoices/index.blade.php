@extends('layouts.app')

@section('title', 'Invoices Management')
@section('header_title', 'Super Admin Invoices & Financial Billing')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h2 class="text-base font-bold text-slate-900">Invoices & Collections</h2>
            <p class="text-xs text-slate-500">Track client billing, advance deductions, and payment status.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('invoices.index') }}" method="GET" class="flex items-center gap-3">
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                    <option value="">All Statuses</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial Paid</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                </select>
            </form>

            <a href="{{ route('invoices.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                <i class="fa fa-file-invoice"></i> Create New Invoice
            </a>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Invoice Number</th>
                        <th class="py-3.5 px-6">Client</th>
                        <th class="py-3.5 px-6">Issued / Due Date</th>
                        <th class="py-3.5 px-6">Total Amount</th>
                        <th class="py-3.5 px-6">Due Balance</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($invoices as $inv)
                    <tr class="hover:bg-slate-50/60 transition-all">
                        <td class="py-4 px-6 font-bold text-slate-900 font-mono">
                            {{ $inv->invoice_number }}
                        </td>
                        <td class="py-4 px-6 font-semibold text-slate-900">
                            {{ $inv->client->name ?? '—' }}
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            <div>{{ $inv->issued_date->format('M d, Y') }}</div>
                            <div class="text-[11px] text-slate-400">Due: {{ $inv->due_date ? $inv->due_date->format('M d, Y') : '—' }}</div>
                        </td>
                        <td class="py-4 px-6 font-mono font-bold text-slate-900">
                            ৳{{ number_format($inv->total, 2) }}
                        </td>
                        <td class="py-4 px-6 font-mono font-bold text-rose-600">
                            ৳{{ number_format($inv->balance, 2) }}
                        </td>
                        <td class="py-4 px-6">
                            @if($inv->status === 'paid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Paid</span>
                            @elseif($inv->status === 'partial')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Partial</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Unpaid</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="inline-flex items-center gap-2">
                                @if($inv->balance > 0 && auth()->user()->canAccess('reminders'))
                                <a href="{{ route('reminders.index') }}" class="px-2.5 py-1 text-xs font-bold text-amber-600 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition-all inline-flex items-center gap-1" title="Send Payment Reminder">
                                    <i class="fa fa-bell"></i> Reminder
                                </a>
                                @endif
                                <a href="{{ route('invoices.show', $inv->id) }}" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition-all" title="View Invoice">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <i class="fa fa-file-invoice text-4xl mb-3 text-slate-300 block"></i>
                            <p class="text-sm font-semibold">No invoices generated yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
