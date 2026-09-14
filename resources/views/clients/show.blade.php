@extends('layouts.app')

@section('title', 'Client Profile — ' . $client->name)
@section('header_title', 'Client 360 Workspace — ' . $client->name)

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'profile' }">
    <!-- Header Card -->
    <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-amber-500 text-white font-extrabold text-2xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                {{ strtoupper(substr($client->name, 0, 2)) }}
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-extrabold tracking-tight">{{ $client->name }}</h2>
                    <span class="px-3 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $client->status === 'active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                        {{ $client->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1">
                    {{ $client->company ?? 'Independent Client' }} · {{ $client->location ?? 'Bangladesh' }} · Onboarded {{ $client->onboarded_at ? $client->onboarded_at->format('M Y') : 'Recently' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('clients.edit', $client->id) }}" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl transition-all shadow-md flex items-center gap-1.5">
                <i class="fa fa-edit"></i> Edit Profile
            </a>
            <a href="{{ route('clients.index') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-xl transition-all flex items-center gap-1.5">
                <i class="fa fa-arrow-left"></i> Back to Clients
            </a>
        </div>
    </div>

    <!-- 8-Section Tab Navigation Bar -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-1.5 overflow-x-auto custom-scrollbar">
        <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'bg-slate-900 text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2">
            <i class="fa fa-id-card text-amber-500"></i> Client Profile
        </button>
        <button @click="activeTab = 'services'" :class="activeTab === 'services' ? 'bg-slate-900 text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2">
            <i class="fa fa-boxes-packing text-emerald-500"></i> Services ({{ $client->clientServices->count() }})
        </button>
        <button @click="activeTab = 'team'" :class="activeTab === 'team' ? 'bg-slate-900 text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2">
            <i class="fa fa-users text-blue-500"></i> Assigned Team
        </button>
        <button @click="activeTab = 'contract'" :class="activeTab === 'contract' ? 'bg-slate-900 text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2">
            <i class="fa fa-file-contract text-purple-500"></i> Contract & Billing
        </button>
        <button @click="activeTab = 'payments'" :class="activeTab === 'payments' ? 'bg-slate-900 text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2">
            <i class="fa fa-receipt text-amber-500"></i> Payments & Invoices ({{ $client->invoices->count() }})
        </button>
        <button @click="activeTab = 'notes'" :class="activeTab === 'notes' ? 'bg-slate-900 text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2">
            <i class="fa fa-note-sticky text-teal-500"></i> Account Notes
        </button>
        <button @click="activeTab = 'documents'" :class="activeTab === 'documents' ? 'bg-slate-900 text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2">
            <i class="fa fa-folder-open text-rose-500"></i> Documents & Assets
        </button>
        <button @click="activeTab = 'history'" :class="activeTab === 'history' ? 'bg-slate-900 text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'" class="px-3.5 py-2 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-2">
            <i class="fa fa-clock-rotate-left text-slate-500"></i> History & Activity
        </button>
    </div>

    <!-- TAB 1: CLIENT PROFILE -->
    <div x-show="activeTab === 'profile'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa fa-user-tie text-amber-500"></i> Client Contact & Organization Information
                </h3>
                <span class="text-xs font-semibold text-slate-500">ID: #CLI-{{ str_pad($client->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Contact Name</span>
                    <span class="font-bold text-slate-900 text-sm">{{ $client->name }}</span>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Company Name</span>
                    <span class="font-bold text-slate-900 text-sm">{{ $client->company ?? 'Independent Client' }}</span>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Phone (WhatsApp)</span>
                    <span class="font-bold text-slate-900">{{ $client->phone ?? '—' }}</span>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Email Address</span>
                    <span class="font-bold text-slate-900">{{ $client->email ?? '—' }}</span>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Location / Address</span>
                    <span class="font-bold text-slate-900">{{ $client->location ?? '—' }}</span>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Onboarding Date</span>
                    <span class="font-bold text-slate-900">{{ $client->onboarded_at ? $client->onboarded_at->format('F d, Y') : '—' }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Summary Side Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider pb-3 border-b border-slate-100">Account Health Overview</h3>

            <div class="space-y-4 text-xs">
                <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50 border border-amber-100">
                    <span class="font-semibold text-amber-900">Monthly Contract Value</span>
                    <span class="font-mono font-extrabold text-amber-700 text-sm">৳{{ number_format($client->total_monthly_value, 2) }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                    <span class="font-semibold text-emerald-900">Advance Credit Deposit</span>
                    <span class="font-mono font-extrabold text-emerald-700 text-sm">৳{{ number_format($client->advance, 2) }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-purple-50 border border-purple-100">
                    <span class="font-semibold text-purple-900">Billing Cycle</span>
                    <span class="font-bold text-purple-700 uppercase">{{ $client->billing_cycle }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: SERVICES & PACKAGES -->
    <div x-show="activeTab === 'services'" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Subscribed Services & Packages</h3>
                <p class="text-xs text-slate-500">Active services delivered to this client</p>
            </div>
            <span class="text-xs font-extrabold text-emerald-600 font-mono bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                Total Recurring: ৳{{ number_format($client->total_monthly_value, 2) }}/month
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-extrabold uppercase text-slate-400">
                        <th class="py-3 px-4">Service Name</th>
                        <th class="py-3 px-4">Unit Rate (৳)</th>
                        <th class="py-3 px-4 text-center">Quantity</th>
                        <th class="py-3 px-4 text-right">Subtotal (৳)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($client->clientServices as $cs)
                    <tr>
                        <td class="py-3.5 px-4 font-bold text-slate-900 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 font-bold flex items-center justify-center">
                                <i class="fa fa-tag"></i>
                            </div>
                            <span>{{ $cs->service->name ?? $cs->custom_name }}</span>
                        </td>
                        <td class="py-3.5 px-4 font-mono">৳{{ number_format($cs->price, 2) }}</td>
                        <td class="py-3.5 px-4 text-center font-mono font-bold">{{ $cs->qty }}</td>
                        <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-900">৳{{ number_format($cs->price * $cs->qty, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-400 font-medium">No active services configured for this client.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 3: ASSIGNED TEAM -->
    <div x-show="activeTab === 'team'" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900">Assigned Account Team & Responsibilities</h3>
            <p class="text-xs text-slate-500">Account managers, sales representatives, and assigned task execution team</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
            <!-- Assigned SMM -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Social Media Manager (SMM)</span>
                @if($client->assignedSmm)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white text-xs shadow-sm" style="background-color: {{ $client->assignedSmm->color ?? '#f59e0b' }}">
                            {{ strtoupper(substr($client->assignedSmm->name, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">{{ $client->assignedSmm->name }}</h4>
                            <span class="text-[10px] text-slate-500 font-semibold">{{ $client->assignedSmm->email }}</span>
                        </div>
                    </div>
                @else
                    <span class="text-xs text-slate-400 font-semibold italic">Unassigned</span>
                @endif
            </div>

            <!-- Assigned Sales Rep -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Sales Representative</span>
                @if($client->assignedSales)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white text-xs shadow-sm" style="background-color: {{ $client->assignedSales->color ?? '#3b82f6' }}">
                            {{ strtoupper(substr($client->assignedSales->name, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">{{ $client->assignedSales->name }}</h4>
                            <span class="text-[10px] text-slate-500 font-semibold">{{ $client->assignedSales->email }}</span>
                        </div>
                    </div>
                @else
                    <span class="text-xs text-slate-400 font-semibold italic">Unassigned</span>
                @endif
            </div>
        </div>
    </div>

    <!-- TAB 4: CONTRACT & BILLING -->
    <div x-show="activeTab === 'contract'" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900">Contract & Billing Agreement</h3>
            <p class="text-xs text-slate-500">Billing cycle, advance credit deposit, and contract lifecycle status</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-xs">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase">Billing Cycle</span>
                <h4 class="text-sm font-extrabold text-slate-900 uppercase">{{ $client->billing_cycle }}</h4>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase">Advance Deposit</span>
                <h4 class="text-sm font-extrabold text-emerald-600 font-mono">৳{{ number_format($client->advance, 2) }}</h4>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase">Contract Status</span>
                <h4 class="text-sm font-extrabold text-slate-900 uppercase">{{ $client->status }}</h4>
            </div>
        </div>
    </div>

    <!-- TAB 5: PAYMENTS & INVOICES -->
    <div x-show="activeTab === 'payments'" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Payment & Invoice History</h3>
                <p class="text-xs text-slate-500">Generated invoices and payment transaction records</p>
            </div>
            <a href="{{ route('invoices.create') }}" class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1">
                <i class="fa fa-plus"></i> Issue New Invoice
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-extrabold uppercase text-slate-400">
                        <th class="py-3 px-4">Invoice #</th>
                        <th class="py-3 px-4">Issued Date</th>
                        <th class="py-3 px-4">Due Date</th>
                        <th class="py-3 px-4 text-right">Total (৳)</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($client->invoices as $inv)
                    <tr>
                        <td class="py-3.5 px-4 font-bold text-slate-900 font-mono">{{ $inv->invoice_number }}</td>
                        <td class="py-3.5 px-4 text-slate-600">{{ $inv->issued_date->format('M d, Y') }}</td>
                        <td class="py-3.5 px-4 text-slate-600">{{ $inv->due_date ? $inv->due_date->format('M d, Y') : '—' }}</td>
                        <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-900">৳{{ number_format($inv->total, 2) }}</td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $inv->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                {{ $inv->status }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <a href="{{ route('invoices.show', $inv->id) }}" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100" title="View Invoice">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 font-medium">No invoices issued to this client yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 6: ACCOUNT NOTES -->
    <div x-show="activeTab === 'notes'" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900">Account Notes & Brand Instructions</h3>
            <p class="text-xs text-slate-500">Internal notes, special instructions, and client brand guidelines</p>
        </div>

        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-xs text-slate-700 whitespace-pre-line leading-relaxed">
            {{ $client->notes ?? 'No internal notes added for this client.' }}
        </div>
    </div>

    <!-- TAB 7: DOCUMENTS & ASSETS -->
    <div x-show="activeTab === 'documents'" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900">Documents & Brand Assets</h3>
            <p class="text-xs text-slate-500">Contracts, agreements, brand guidelines, and client media files</p>
        </div>

        <div class="py-12 text-center text-slate-400 border-2 border-dashed border-slate-200 rounded-2xl">
            <i class="fa fa-folder-open text-3xl mb-2 text-slate-300 block"></i>
            <p class="text-xs font-semibold text-slate-600">Client Document Repository</p>
            <p class="text-[11px] text-slate-400 mt-1">Contracts, proposals, and brand assets linked to {{ $client->name }}.</p>
        </div>
    </div>

    <!-- TAB 8: HISTORY & ACTIVITY LOG -->
    <div x-show="activeTab === 'history'" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900">History & Deliverables Log</h3>
            <p class="text-xs text-slate-500">Meetings log, deliverables history, and account activity timeline</p>
        </div>

        <div class="space-y-4 text-xs">
            <h4 class="font-bold text-slate-800 uppercase text-[10px] tracking-wider">Scheduled & Past Meetings</h4>
            <div class="divide-y divide-slate-100 border border-slate-100 rounded-xl overflow-hidden">
                @forelse($client->meetings as $m)
                <div class="p-3 flex items-center justify-between hover:bg-slate-50">
                    <div>
                        <span class="font-bold text-slate-900">{{ $m->title }}</span>
                        <span class="block text-[11px] text-slate-400">{{ $m->date->format('M d, Y') }} at {{ $m->time }}</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-700">
                        {{ $m->status }}
                    </span>
                </div>
                @empty
                <div class="p-4 text-center text-slate-400">No meeting logs recorded.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
