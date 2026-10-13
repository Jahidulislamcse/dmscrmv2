@extends('layouts.app')

@section('title', 'CRM Deals & Lead Pipeline')
@section('header_title', 'CRM Lead Pipeline & Deals')

@section('content')
<div class="space-y-6" x-data="{ viewMode: 'kanban' }">

    <!-- Top Action Bar & Metrics -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Sales Lead Pipeline</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Track, manage and convert prospect leads through sales stages</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- View Mode Switcher -->
            <div class="bg-slate-200/80 p-1 rounded-xl flex items-center gap-1 border border-slate-300/60 shadow-inner">
                <button @click="viewMode = 'kanban'"
                        :class="viewMode === 'kanban' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                        class="px-3 py-1.5 rounded-lg text-xs flex items-center gap-2 transition-all">
                    <i class="fa fa-columns text-amber-500"></i>
                    <span>Kanban Board</span>
                </button>
                <button @click="viewMode = 'table'"
                        :class="viewMode === 'table' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                        class="px-3 py-1.5 rounded-lg text-xs flex items-center gap-2 transition-all">
                    <i class="fa fa-list text-slate-500"></i>
                    <span>Table View</span>
                </button>
            </div>

            <!-- Export Leads (Restricted: Sales role cannot export lists) -->
            @if(!auth()->user()->isSales())
            <a href="{{ route('crm.export') }}" class="px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs shadow-sm flex items-center gap-1.5 transition-all" title="Export Leads List to CSV / Excel">
                <i class="fa fa-file-excel text-emerald-600"></i>
                <span>Export CSV</span>
            </a>
            @else
            <span class="px-3 py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 font-semibold text-xs flex items-center gap-1.5" title="Sales representatives have view-only list access and cannot export">
                <i class="fa fa-lock text-slate-400"></i>
                <span>View-Only Mode</span>
            </span>
            @endif

            <a href="{{ route('crm.create') }}" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-brand-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold text-xs shadow-md shadow-amber-500/20 flex items-center gap-2 transition-all">
                <i class="fa fa-plus-circle text-sm"></i>
                <span>Add New Lead</span>
            </a>
        </div>
    </div>

    <!-- Metrics Cards Overview -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold text-xl">
                <i class="fa fa-vault"></i>
            </div>
            <div>
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pipeline Value</span>
                <h4 class="text-lg font-extrabold text-slate-900">৳{{ number_format($allLeads->sum('budget'), 2) }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center font-bold text-xl">
                <i class="fa fa-filter"></i>
            </div>
            <div>
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Active Leads</span>
                <h4 class="text-lg font-extrabold text-slate-900">{{ $allLeads->count() }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center font-bold text-xl">
                <i class="fa fa-trophy"></i>
            </div>
            <div>
                @php
                    $wonCount = $allLeads->filter(fn($l) => str_contains(strtolower($l->stage->name ?? ''), 'won'))->count();
                @endphp
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Deals Won</span>
                <h4 class="text-lg font-extrabold text-emerald-600">{{ $wonCount }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center font-bold text-xl">
                <i class="fa fa-percent"></i>
            </div>
            <div>
                @php
                    $winRate = $allLeads->count() > 0 ? round(($wonCount / $allLeads->count()) * 100, 1) : 0;
                @endphp
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Win Rate</span>
                <h4 class="text-lg font-extrabold text-purple-600">{{ $winRate }}%</h4>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('crm.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fa fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, company, phone..."
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                </div>
            </div>

            <div class="w-44">
                <select name="assigned_to" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                    <option value="">All Sales Reps</option>
                    @foreach($salesReps as $rep)
                        <option value="{{ $rep->id }}" {{ request('assigned_to') == $rep->id ? 'selected' : '' }}>
                            {{ $rep->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-40">
                <select name="source" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                    <option value="">All Lead Sources</option>
                    <option value="Facebook" {{ request('source') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                    <option value="Website" {{ request('source') == 'Website' ? 'selected' : '' }}>Website</option>
                    <option value="Referral" {{ request('source') == 'Referral' ? 'selected' : '' }}>Referral</option>
                    <option value="Cold Call" {{ request('source') == 'Cold Call' ? 'selected' : '' }}>Cold Call</option>
                    <option value="WhatsApp" {{ request('source') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all">
                <i class="fa fa-filter mr-1"></i> Filter
            </button>

            @if(request()->anyFilled(['search', 'assigned_to', 'source']))
            <a href="{{ route('crm.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200 transition-all">
                <i class="fa fa-rotate-left mr-1"></i> Reset
            </a>
            @endif
        </form>
    </div>

    <!-- KANBAN BOARD VIEW -->
    <div x-show="viewMode === 'kanban'" class="overflow-x-auto pb-6">
        <div class="flex items-start gap-4 min-w-[1200px]">
            @foreach($stages as $stage)
            @php
                $stageLeads = $kanbanLeads[$stage->id] ?? collect();
                $stageValue = $stageLeads->sum('budget');
            @endphp
            <div class="w-80 flex-shrink-0 bg-slate-100/80 rounded-2xl border border-slate-200/90 flex flex-col max-h-[calc(100vh-280px)] shadow-sm">
                
                <!-- Stage Header -->
                <div class="p-3.5 border-b border-slate-200/80 flex items-center justify-between bg-white rounded-t-2xl">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full" style="background-color: {{ $stage->color ?? '#f59e0b' }}"></span>
                        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">{{ $stage->name }}</h3>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                            {{ $stageLeads->count() }}
                        </span>
                    </div>
                    <span class="text-[11px] font-extrabold text-slate-500">
                        ৳{{ number_format($stageValue, 0) }}
                    </span>
                </div>

                <!-- Stage Cards Column -->
                <div class="p-3 space-y-3 overflow-y-auto flex-1 custom-scrollbar">
                    @forelse($stageLeads as $lead)
                    <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md hover:border-amber-400 transition-all group relative">
                        <!-- Top Meta -->
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                <i class="fa fa-globe mr-1 text-[9px]"></i> {{ $lead->source }}
                            </span>
                            <span class="text-xs font-extrabold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                ৳{{ number_format($lead->budget, 0) }}
                            </span>
                        </div>

                        <!-- Lead Title & Company -->
                        <a href="{{ route('crm.show', $lead->id) }}" class="block group-hover:text-amber-600 transition-all">
                            <h4 class="font-bold text-slate-900 text-sm line-clamp-1">{{ $lead->name }}</h4>
                            @if($lead->company)
                            <p class="text-xs text-slate-500 font-medium truncate flex items-center gap-1 mt-0.5">
                                <i class="fa fa-building text-[10px] text-slate-400"></i>
                                <span>{{ $lead->company }}</span>
                            </p>
                            @endif
                        </a>

                        <!-- Contact details snippet -->
                        @if($lead->phone)
                        <div class="mt-2 text-[11px] text-slate-500 font-semibold flex items-center gap-1.5">
                            <i class="fa fa-phone text-slate-400 text-[10px]"></i>
                            <span>{{ $lead->phone }}</span>
                        </div>
                        @endif

                        <!-- Requirements tags -->
                        @if($lead->requirements->count() > 0)
                        <div class="mt-2.5 flex flex-wrap gap-1">
                            @foreach($lead->requirements->take(2) as $req)
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                {{ $req->service_name }}
                            </span>
                            @endforeach
                            @if($lead->requirements->count() > 2)
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-500">
                                +{{ $lead->requirements->count() - 2 }}
                            </span>
                            @endif
                        </div>
                        @endif

                        <!-- Card Footer -->
                        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <!-- Quick Stage Change Select (Restricted: Sales role cannot change lead stages) -->
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('crm.update-stage', $lead->id) }}" method="POST" class="inline-block">
                                @csrf
                                <select name="stage_id" onchange="this.form.submit()"
                                        class="text-[11px] font-bold py-1 px-2 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 focus:outline-none focus:ring-1 focus:ring-amber-500 cursor-pointer">
                                    @foreach($stages as $st)
                                        <option value="{{ $st->id }}" {{ $lead->stage_id == $st->id ? 'selected' : '' }}>
                                            {{ $st->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            @else
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-lg border border-slate-200" title="Lead stages are managed by admin (View-only for sales)">
                                <i class="fa fa-lock text-[9px] text-slate-400"></i> {{ $lead->stage->name ?? 'Stage' }}
                            </span>
                            @endif

                            <!-- Sales Rep Avatar -->
                            <div class="flex items-center gap-1.5" title="Assigned to {{ $lead->assignedTo->name ?? 'Sales' }}">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-white text-[10px] shadow-sm" style="background-color: {{ $lead->assignedTo->color ?? '#f59e0b' }}">
                                    {{ strtoupper(substr($lead->assignedTo->name ?? 'U', 0, 2)) }}
                                </div>
                            </div>
                        </div>

                    </div>
                    @empty
                    <div class="py-8 text-center bg-white/50 rounded-xl border border-dashed border-slate-200">
                        <i class="fa fa-inbox text-slate-300 text-2xl mb-1"></i>
                        <p class="text-xs font-semibold text-slate-400">No leads in stage</p>
                    </div>
                    @endforelse
                </div>

            </div>
            @endforeach
        </div>
    </div>

    <!-- TABLE LIST VIEW -->
    <div x-show="viewMode === 'table'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" x-cloak>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">Lead Name</th>
                        <th class="py-3.5 px-4">Company</th>
                        <th class="py-3.5 px-4">Contact</th>
                        <th class="py-3.5 px-4">Stage</th>
                        <th class="py-3.5 px-4">Est. Budget</th>
                        <th class="py-3.5 px-4">Assigned Rep</th>
                        <th class="py-3.5 px-4">Source</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($allLeads as $lead)
                    <tr class="hover:bg-slate-50/80 transition-all">
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            <a href="{{ route('crm.show', $lead->id) }}" class="hover:text-amber-600">
                                {{ $lead->name }}
                            </a>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-600">
                            {{ $lead->company ?? '—' }}
                        </td>
                        <td class="py-3.5 px-4 font-medium text-slate-600">
                            <div>{{ $lead->phone ?? '—' }}</div>
                            <div class="text-[10px] text-slate-400">{{ $lead->email }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold text-white shadow-sm" style="background-color: {{ $lead->stage->color ?? '#f59e0b' }}">
                                {{ $lead->stage->name ?? 'New' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-extrabold text-slate-900">
                            ৳{{ number_format($lead->budget, 2) }}
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-700">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-white text-[9px]" style="background-color: {{ $lead->assignedTo->color ?? '#f59e0b' }}">
                                    {{ strtoupper(substr($lead->assignedTo->name ?? 'U', 0, 2)) }}
                                </div>
                                <span>{{ $lead->assignedTo->name ?? 'Unassigned' }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                {{ $lead->source }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('crm.quotation', $lead->id) }}" class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-amber-600" title="Proposal & Quotation">
                                    <i class="fa fa-file-invoice"></i>
                                </a>
                                <a href="{{ route('crm.show', $lead->id) }}" class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-900" title="View Profile">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('crm.edit', $lead->id) }}" class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50" title="Edit Lead">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400 font-semibold">
                            No sales leads found matching criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
