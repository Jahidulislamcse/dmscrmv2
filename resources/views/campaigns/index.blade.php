@extends('layouts.app')

@section('title', 'Ad Campaigns & Performance Marketing — Deep CRM')

@section('content')
<div class="space-y-6">

    <!-- TOP HEADER -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-blue-500/20">
                    <i class="fa fa-bullseye"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        Campaign Management Hub
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 border border-blue-200">
                            REQ-CAM-01
                        </span>
                    </h1>
                    <p class="text-xs text-slate-500">Meta & Google Ads tracking: Budget, Spend, Leads, CPL, CTR, ROAS & Performance Metrics.</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <button onclick="openCreateModal()" class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-black shadow-md shadow-blue-500/20 flex items-center gap-2 transition-all">
                <i class="fa fa-plus"></i>
                <span>Launch New Campaign</span>
            </button>
        </div>
    </div>

    <!-- BLENDED KPI SUMMARY CARDS -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <!-- 1: Total Spend -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400 text-xs mb-1">
                <span class="font-bold uppercase tracking-wider text-[10px]">Total Ad Spend</span>
                <i class="fa fa-receipt text-blue-500"></i>
            </div>
            <div class="text-xl font-black text-slate-900 font-mono">
                ৳{{ number_format($totalSpend, 0) }}
            </div>
            <div class="text-[10px] text-slate-500 font-semibold mt-1">
                Budget: ৳{{ number_format($totalBudget, 0) }}
            </div>
        </div>

        <!-- 2: Total Leads -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400 text-xs mb-1">
                <span class="font-bold uppercase tracking-wider text-[10px]">Leads Acquired</span>
                <i class="fa fa-user-plus text-emerald-500"></i>
            </div>
            <div class="text-xl font-black text-emerald-600 font-mono">
                {{ number_format($totalLeads) }}
            </div>
            <div class="text-[10px] text-slate-500 font-semibold mt-1">
                Across all active campaigns
            </div>
        </div>

        <!-- 3: Average CPL -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400 text-xs mb-1">
                <span class="font-bold uppercase tracking-wider text-[10px]">Cost Per Lead (CPL)</span>
                <i class="fa fa-tag text-purple-500"></i>
            </div>
            <div class="text-xl font-black text-purple-700 font-mono">
                ৳{{ number_format($blendedCpl, 2) }}
            </div>
            <div class="text-[10px] text-slate-500 font-semibold mt-1">
                Blended agency average
            </div>
        </div>

        <!-- 4: Return On Ad Spend (ROAS) -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400 text-xs mb-1">
                <span class="font-bold uppercase tracking-wider text-[10px]">Return on Ad Spend</span>
                <i class="fa fa-chart-line-up text-amber-500"></i>
            </div>
            <div class="text-xl font-black text-amber-600 font-mono">
                {{ number_format($blendedRoas, 2) }}x
            </div>
            <div class="text-[10px] text-slate-500 font-semibold mt-1">
                Rev: ৳{{ number_format($totalRevenue, 0) }}
            </div>
        </div>

        <!-- 5: Click-Through Rate (CTR) -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400 text-xs mb-1">
                <span class="font-bold uppercase tracking-wider text-[10px]">Click-Through Rate</span>
                <i class="fa fa-arrow-pointer text-indigo-500"></i>
            </div>
            <div class="text-xl font-black text-indigo-600 font-mono">
                {{ number_format($blendedCtr, 2) }}%
            </div>
            <div class="text-[10px] text-slate-500 font-semibold mt-1">
                {{ number_format($totalClicks) }} clicks
            </div>
        </div>

        <!-- 6: Cost Per Click (CPC) -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400 text-xs mb-1">
                <span class="font-bold uppercase tracking-wider text-[10px]">Cost Per Click (CPC)</span>
                <i class="fa fa-coins text-rose-500"></i>
            </div>
            <div class="text-xl font-black text-rose-600 font-mono">
                ৳{{ number_format($blendedCpc, 2) }}
            </div>
            <div class="text-[10px] text-slate-500 font-semibold mt-1">
                {{ number_format($totalImpressions) }} views
            </div>
        </div>
    </div>

    <!-- FILTER BAR & PLATFORM SELECTOR -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs space-y-3">
        <!-- Platform Quick Pills -->
        <div class="flex flex-wrap items-center gap-2 pb-2 border-b border-slate-100">
            <a href="{{ request()->fullUrlWithQuery(['platform' => null]) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ !request('platform') ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                All Platforms
            </a>
            @foreach(\App\Models\Campaign::PLATFORMS as $pKey => $pMeta)
            <a href="{{ request()->fullUrlWithQuery(['platform' => $pKey]) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all {{ request('platform') === $pKey ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="{{ $pMeta['icon'] }}" style="color: {{ request('platform') === $pKey ? '#ffffff' : $pMeta['color'] }};"></i>
                <span>{{ $pMeta['label'] }}</span>
            </a>
            @endforeach
        </div>

        <!-- Search & Filter Controls -->
        <form method="GET" action="{{ route('campaigns.index') }}" class="flex flex-wrap items-center gap-3">
            @if(request('platform'))
            <input type="hidden" name="platform" value="{{ request('platform') }}">
            @endif

            <!-- Client Filter -->
            <div class="flex-1 min-w-[180px]">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Client</label>
                <select name="client_id" onchange="this.form.submit()" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Clients</option>
                    @foreach($clients as $c)
                    <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="w-36">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Status</label>
                <select name="status" onchange="this.form.submit()" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    @foreach(\App\Models\Campaign::STATUSES as $sKey => $sMeta)
                    <option value="{{ $sKey }}" {{ request('status') === $sKey ? 'selected' : '' }}>{{ $sMeta['label'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Objective Filter -->
            <div class="w-44">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Campaign Objective</label>
                <select name="objective" onchange="this.form.submit()" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Objectives</option>
                    @foreach(\App\Models\Campaign::OBJECTIVES as $oKey => $oMeta)
                    <option value="{{ $oKey }}" {{ request('objective') === $oKey ? 'selected' : '' }}>{{ $oMeta['label'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Clear Filter Button -->
            @if(request('client_id') || request('platform') || request('status') || request('objective'))
            <div class="self-end pb-0.5">
                <a href="{{ route('campaigns.index') }}" class="px-3 py-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold flex items-center gap-1">
                    <i class="fa fa-xmark"></i> Clear
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- CAMPAIGNS DATA TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 font-bold uppercase tracking-wider text-slate-500 text-[10px]">
                        <th class="py-3.5 px-4">Campaign Name & Objective</th>
                        <th class="py-3.5 px-4">Client</th>
                        <th class="py-3.5 px-3">Platform</th>
                        <th class="py-3.5 px-4">Budget & Spend</th>
                        <th class="py-3.5 px-3 text-center">Leads</th>
                        <th class="py-3.5 px-3 text-center">CPL (Cost/Lead)</th>
                        <th class="py-3.5 px-3 text-center">CTR %</th>
                        <th class="py-3.5 px-3 text-center">ROAS</th>
                        <th class="py-3.5 px-3 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($campaigns as $camp)
                    @php
                        $plf = $camp->platform_info;
                        $stg = $camp->status_info;
                        $obj = $camp->objective_info;
                        $burnPct = $camp->budget_utilization;
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-all">
                        <!-- Campaign & Objective -->
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            <div>
                                <span class="text-sm font-extrabold text-slate-900 cursor-pointer hover:text-blue-600 transition-colors" onclick="openEditModal({{ json_encode($camp) }})">
                                    {{ $camp->name }}
                                </span>
                                <div class="text-[10px] font-semibold text-slate-500 flex items-center gap-1.5 mt-0.5">
                                    <i class="fa {{ $obj['icon'] }} text-slate-400"></i>
                                    <span>{{ $obj['label'] }}</span>
                                    @if($camp->ad_account_id)
                                    <span class="text-slate-400 font-mono">({{ $camp->ad_account_id }})</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Client -->
                        <td class="py-3.5 px-4 font-semibold text-slate-700">
                            <a href="{{ route('clients.show', $camp->client_id) }}" class="hover:text-blue-600 transition-colors">
                                {{ $camp->client->name ?? '—' }}
                            </a>
                            <div class="text-[10px] text-slate-400 font-normal">
                                Buyer: {{ $camp->assignedTo->name ?? 'Unassigned' }}
                            </div>
                        </td>

                        <!-- Platform -->
                        <td class="py-3.5 px-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $plf['badge'] }}">
                                <i class="{{ $plf['icon'] }}"></i>
                                {{ $plf['label'] }}
                            </span>
                        </td>

                        <!-- Budget & Spend -->
                        <td class="py-3.5 px-4 font-mono">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-900">৳{{ number_format($camp->spend, 0) }}</span>
                                <span class="text-slate-400 text-[10px]">/ ৳{{ number_format($camp->budget, 0) }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-1.5 overflow-hidden">
                                <div class="h-1.5 rounded-full {{ $burnPct > 90 ? 'bg-rose-500' : ($burnPct > 70 ? 'bg-amber-500' : 'bg-blue-500') }}" style="width: {{ $burnPct }}%"></div>
                            </div>
                        </td>

                        <!-- Leads -->
                        <td class="py-3.5 px-3 text-center font-mono font-bold text-emerald-700 text-sm">
                            {{ number_format($camp->leads_count) }}
                        </td>

                        <!-- CPL -->
                        <td class="py-3.5 px-3 text-center">
                            @if($camp->leads_count > 0)
                            <span class="px-2 py-0.5 rounded-md font-mono font-bold text-xs bg-purple-50 text-purple-700 border border-purple-200">
                                ৳{{ number_format($camp->cpl, 2) }}
                            </span>
                            @else
                            <span class="text-slate-400 font-mono text-[10px]">0 Leads</span>
                            @endif
                        </td>

                        <!-- CTR -->
                        <td class="py-3.5 px-3 text-center font-mono font-bold text-indigo-700">
                            {{ number_format($camp->ctr, 2) }}%
                        </td>

                        <!-- ROAS -->
                        <td class="py-3.5 px-3 text-center">
                            @if($camp->spend > 0)
                            <span class="px-2 py-0.5 rounded-md font-mono font-bold text-xs {{ $camp->roas >= 2.0 ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-slate-100 text-slate-700' }}">
                                {{ number_format($camp->roas, 2) }}x
                            </span>
                            @else
                            <span class="text-slate-400 font-mono text-[10px]">—</span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="py-3.5 px-3 text-center">
                            <form method="POST" action="{{ route('campaigns.status', $camp) }}" class="inline">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="text-[10px] font-extrabold uppercase tracking-wider rounded-full px-2 py-0.5 border cursor-pointer {{ $stg['badge'] }} focus:outline-none">
                                    <option value="active" {{ $camp->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="paused" {{ $camp->status === 'paused' ? 'selected' : '' }}>Paused</option>
                                    <option value="completed" {{ $camp->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="draft" {{ $camp->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </form>
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <!-- Quick Metrics Log Button -->
                                <button onclick="openMetricsModal({{ json_encode($camp) }})" class="p-1.5 text-blue-600 hover:text-blue-800 rounded-lg hover:bg-blue-50 transition-colors" title="Update Live Performance Metrics">
                                    <i class="fa fa-chart-simple text-xs"></i>
                                </button>

                                <!-- Edit Full Details -->
                                <button onclick="openEditModal({{ json_encode($camp) }})" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition-colors" title="Edit Campaign Details">
                                    <i class="fa fa-pen-to-square text-xs"></i>
                                </button>

                                <!-- Delete -->
                                <form method="POST" action="{{ route('campaigns.destroy', $camp) }}" onsubmit="return confirm('Delete this campaign?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition-colors" title="Delete">
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 text-xl">
                                    <i class="fa fa-bullseye"></i>
                                </div>
                                <span class="font-bold text-slate-600">No campaigns found</span>
                                <p class="text-xs text-slate-400 max-w-sm">No advertising campaigns match the selected filters. Click "Launch New Campaign" to get started.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ======================================================== -->
<!-- MODAL: LAUNCH NEW CAMPAIGN                                -->
<!-- ======================================================== -->
<div id="createModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                    <i class="fa fa-plus"></i>
                </div>
                <h3 class="text-base font-black text-slate-900">Launch New Advertising Campaign</h3>
            </div>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
        </div>

        <form method="POST" action="{{ route('campaigns.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Campaign Name *</label>
                <input type="text" name="name" required placeholder="e.g. Q4 Eid Festive Promo — Meta Lead Gen" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Client *</label>
                    <select name="client_id" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Client...</option>
                        @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Assigned Media Buyer / Ads Specialist</label>
                    <select name="assigned_to" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Unassigned</option>
                        @foreach($mediaBuyers as $b)
                        <option value="{{ $b->id }}">{{ $b->name }} ({{ ucfirst($b->role) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Advertising Platform *</label>
                    <select name="platform" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(\App\Models\Campaign::PLATFORMS as $pKey => $pMeta)
                        <option value="{{ $pKey }}">{{ $pMeta['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Primary Objective *</label>
                    <select name="objective" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(\App\Models\Campaign::OBJECTIVES as $oKey => $oMeta)
                        <option value="{{ $oKey }}">{{ $oMeta['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Initial Status *</label>
                    <select name="status" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active">Active</option>
                        <option value="paused">Paused</option>
                        <option value="draft">Draft</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Total Budget (৳) *</label>
                    <input type="number" step="0.01" name="budget" required placeholder="e.g. 25000" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Ad Account ID (Optional)</label>
                    <input type="text" name="ad_account_id" placeholder="e.g. act_19482019482" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ now()->format('Y-m-d') }}" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">End Date</label>
                    <input type="date" name="end_date" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Target Audience & Geo Demographics</label>
                <textarea name="target_audience" rows="2" placeholder="e.g. Dhaka & Chittagong, Age 22-45, Interests: Online Shopping, Fashion..." class="w-full text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Strategy & Optimization Notes</label>
                <textarea name="notes" rows="2" placeholder="e.g. Bid strategy: Lowest cost per result. Pixel event: Lead form submit..." class="w-full text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-black shadow-md shadow-blue-500/20 transition-all">
                    Launch Campaign
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: QUICK UPDATE PERFORMANCE METRICS                   -->
<!-- ======================================================== -->
<div id="metricsModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i class="fa fa-chart-line text-blue-600"></i>
                    Update Campaign Performance
                </h3>
                <p id="metricsCampTitle" class="text-xs font-semibold text-slate-500 truncate max-w-sm"></p>
            </div>
            <button onclick="closeMetricsModal()" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
        </div>

        <form id="metricsForm" method="POST" action="" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Spend to Date (৳) *</label>
                    <input type="number" step="0.01" name="spend" id="mSpend" required oninput="calculateLiveKpis()" class="w-full text-xs font-mono font-bold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-900 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Leads Generated *</label>
                    <input type="number" name="leads_count" id="mLeads" required oninput="calculateLiveKpis()" class="w-full text-xs font-mono font-bold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-emerald-700 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Revenue (৳)</label>
                    <input type="number" step="0.01" name="revenue" id="mRevenue" oninput="calculateLiveKpis()" class="w-full text-xs font-mono font-bold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-amber-700 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Clicks</label>
                    <input type="number" name="clicks" id="mClicks" oninput="calculateLiveKpis()" class="w-full text-xs font-mono font-bold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Impressions</label>
                    <input type="number" name="impressions" id="mImpressions" oninput="calculateLiveKpis()" class="w-full text-xs font-mono font-bold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- LIVE CALCULATED PREVIEW CARDS -->
            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Live Calculated Metrics Preview</span>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="p-2 rounded-xl bg-white border border-slate-100">
                        <span class="text-[9px] text-slate-400 uppercase font-bold block">Cost/Lead (CPL)</span>
                        <span id="previewCpl" class="text-xs font-black font-mono text-purple-700">৳0.00</span>
                    </div>
                    <div class="p-2 rounded-xl bg-white border border-slate-100">
                        <span class="text-[9px] text-slate-400 uppercase font-bold block">CTR</span>
                        <span id="previewCtr" class="text-xs font-black font-mono text-indigo-700">0.00%</span>
                    </div>
                    <div class="p-2 rounded-xl bg-white border border-slate-100">
                        <span class="text-[9px] text-slate-400 uppercase font-bold block">ROAS</span>
                        <span id="previewRoas" class="text-xs font-black font-mono text-amber-600">0.00x</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeMetricsModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black shadow-md shadow-blue-500/20 transition-all">
                    Save Live Metrics
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: EDIT CAMPAIGN DETAILS                              -->
<!-- ======================================================== -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                    <i class="fa fa-pen-to-square"></i>
                </div>
                <h3 class="text-base font-black text-slate-900">Edit Campaign Parameters</h3>
            </div>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
        </div>

        <form id="editForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Campaign Name *</label>
                <input type="text" name="name" id="editName" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Client *</label>
                    <select name="client_id" id="editClientId" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                        @foreach($clients as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Assigned Media Buyer</label>
                    <select name="assigned_to" id="editAssignedTo" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                        <option value="">Unassigned</option>
                        @foreach($mediaBuyers as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Platform *</label>
                    <select name="platform" id="editPlatform" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                        @foreach(\App\Models\Campaign::PLATFORMS as $pKey => $pMeta)
                        <option value="{{ $pKey }}">{{ $pMeta['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Objective *</label>
                    <select name="objective" id="editObjective" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                        @foreach(\App\Models\Campaign::OBJECTIVES as $oKey => $oMeta)
                        <option value="{{ $oKey }}">{{ $oMeta['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Status *</label>
                    <select name="status" id="editStatus" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                        <option value="active">Active</option>
                        <option value="paused">Paused</option>
                        <option value="completed">Completed</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Total Budget (৳) *</label>
                    <input type="number" step="0.01" name="budget" id="editBudget" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Ad Account ID</label>
                    <input type="text" name="ad_account_id" id="editAdAccountId" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Start Date</label>
                    <input type="date" name="start_date" id="editStartDate" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">End Date</label>
                    <input type="date" name="end_date" id="editEndDate" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Target Audience & Geo Demographics</label>
                <textarea name="target_audience" id="editTargetAudience" rows="2" class="w-full text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800"></textarea>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Strategy & Notes</label>
                <textarea name="notes" id="editNotes" rows="2" class="w-full text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black shadow-md shadow-indigo-500/20 transition-all">
                    Update Campaign
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openEditModal(camp) {
    document.getElementById('editForm').action = '/campaigns/' + camp.id;
    document.getElementById('editName').value = camp.name || '';
    document.getElementById('editClientId').value = camp.client_id || '';
    document.getElementById('editAssignedTo').value = camp.assigned_to || '';
    document.getElementById('editPlatform').value = camp.platform || 'meta';
    document.getElementById('editObjective').value = camp.objective || 'leads';
    document.getElementById('editStatus').value = camp.status || 'active';
    document.getElementById('editBudget').value = camp.budget || '0';
    document.getElementById('editAdAccountId').value = camp.ad_account_id || '';
    document.getElementById('editStartDate').value = camp.start_date ? camp.start_date.substring(0, 10) : '';
    document.getElementById('editEndDate').value = camp.end_date ? camp.end_date.substring(0, 10) : '';
    document.getElementById('editTargetAudience').value = camp.target_audience || '';
    document.getElementById('editNotes').value = camp.notes || '';
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function openMetricsModal(camp) {
    document.getElementById('metricsForm').action = '/campaigns/' + camp.id + '/metrics';
    document.getElementById('metricsCampTitle').innerText = camp.name + ' (' + (camp.client ? camp.client.name : '') + ')';
    document.getElementById('mSpend').value = camp.spend || 0;
    document.getElementById('mLeads').value = camp.leads_count || 0;
    document.getElementById('mRevenue').value = camp.revenue || 0;
    document.getElementById('mClicks').value = camp.clicks || 0;
    document.getElementById('mImpressions').value = camp.impressions || 0;
    calculateLiveKpis();
    document.getElementById('metricsModal').classList.remove('hidden');
}

function closeMetricsModal() {
    document.getElementById('metricsModal').classList.add('hidden');
}

function calculateLiveKpis() {
    const spend = parseFloat(document.getElementById('mSpend').value) || 0;
    const leads = parseInt(document.getElementById('mLeads').value) || 0;
    const revenue = parseFloat(document.getElementById('mRevenue').value) || 0;
    const clicks = parseInt(document.getElementById('mClicks').value) || 0;
    const impressions = parseInt(document.getElementById('mImpressions').value) || 0;

    const cpl = leads > 0 ? (spend / leads).toFixed(2) : '0.00';
    const ctr = impressions > 0 ? ((clicks / impressions) * 100).toFixed(2) : '0.00';
    const roas = spend > 0 ? (revenue / spend).toFixed(2) : '0.00';

    document.getElementById('previewCpl').innerText = '৳' + cpl;
    document.getElementById('previewCtr').innerText = ctr + '%';
    document.getElementById('previewRoas').innerText = roas + 'x';
}
</script>
@endsection
