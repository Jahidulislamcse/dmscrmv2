@extends('layouts.app')

@section('title', 'Monthly Performance Report — ' . $client->name . ' (' . $startDate->format('M Y') . ')')
@section('header_title', 'Monthly Client Performance Report')

@section('content')
@php
    $agencySettings = \App\Http\Controllers\SettingsController::getAgencySettings();
    $currency = $agencySettings['agency_currency'] ?? '৳';
@endphp

<div class="space-y-6">

    <!-- Top Action Bar (Hidden in Print Mode) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.index', ['month' => $selectedMonth]) }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all flex items-center gap-1.5">
                <i class="fa fa-arrow-left"></i> Reports Hub
            </a>
            <span class="text-xs text-slate-300">|</span>
            <!-- Quick Client Switcher -->
            <form action="{{ route('reports.index') }}" method="GET" class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-500">Client:</span>
                <select onchange="if(this.value) window.location.href = this.value;" class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-amber-500 cursor-pointer">
                    @foreach($allClients as $ac)
                        <option value="{{ route('reports.client', ['client' => $ac->id, 'month' => $selectedMonth]) }}" {{ $ac->id === $client->id ? 'selected' : '' }}>
                            {{ $ac->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="flex items-center gap-2">
            <!-- Month Selector -->
            <form action="{{ route('reports.client', $client->id) }}" method="GET" class="flex items-center gap-1.5">
                <select name="month" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-amber-500 cursor-pointer">
                    @foreach($monthOptions as $mKey => $mLabel)
                        <option value="{{ $mKey }}" {{ $selectedMonth === $mKey ? 'selected' : '' }}>
                            {{ $mLabel }}
                        </option>
                    @endforeach
                </select>
            </form>

            <!-- Direct PDF Download via DomPDF -->
            <a href="{{ route('reports.client.pdf', ['client' => $client->id, 'month' => $selectedMonth]) }}" class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                <i class="fa fa-file-pdf"></i>
                <span>Download PDF</span>
            </a>

            <!-- Print / Browser PDF Save -->
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
                <i class="fa fa-print"></i>
                <span>Print Report</span>
            </button>
        </div>
    </div>

    <!-- Formal Printable Performance Report Container -->
    <div id="printable-client-report" class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-10 space-y-8 print:p-0 print:border-none print:shadow-none print:m-0 text-slate-900">
        
        <!-- Document Header / Agency Letterhead -->
        <div class="border-b border-slate-200 pb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 text-amber-400 flex items-center justify-center font-black text-xl shadow-md">
                        <i class="fa fa-bullhorn"></i>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight text-slate-900">{{ $agencySettings['agency_name'] }}</h1>
                        <p class="text-xs text-slate-500 font-medium">Digital Marketing & Creative Agency Performance Division</p>
                    </div>
                </div>
                <div class="text-[11px] text-slate-500 space-y-0.5">
                    <div>{{ $agencySettings['agency_address'] }} · {{ $agencySettings['agency_email'] }} · {{ $agencySettings['agency_phone'] }}</div>
                    <div>Agency Tax/BIN: <span class="font-mono font-bold text-slate-700">{{ $agencySettings['tax_id'] }}</span></div>
                </div>
            </div>

            <!-- Client & Report Meta Box -->
            <div class="sm:text-right space-y-1.5">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-slate-900 text-white">
                    Monthly Performance Report
                </span>
                <div class="text-sm font-extrabold text-slate-900 mt-1">{{ $startDate->format('F 1, Y') }} — {{ $endDate->format('F t, Y') }}</div>
                <div class="text-xs text-slate-500 font-mono">Report Ref: <span class="font-bold text-slate-800">DMS-REP-{{ $startDate->format('Ym') }}-{{ str_pad($client->id, 4, '0', STR_PAD_LEFT) }}</span></div>
                <div class="text-[11px] text-slate-400">Generated: {{ now()->format('d M Y, h:i A') }}</div>
            </div>
        </div>

        <!-- Client Profile Dossier Banner -->
        <div class="bg-gradient-to-r from-slate-50 to-slate-100/80 rounded-2xl p-5 border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-500 text-white font-black text-xl flex items-center justify-center shadow-md flex-shrink-0">
                    {{ strtoupper(substr($client->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-extrabold text-slate-900">{{ $client->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300">
                            {{ $client->status }} Client
                        </span>
                    </div>
                    <div class="text-xs text-slate-600 font-medium mt-0.5">
                        {{ $client->company ?? 'Commercial Account' }} · {{ $client->location ?? 'Bangladesh' }}
                    </div>
                    <div class="text-[11px] text-slate-500 mt-1 flex flex-wrap items-center gap-3">
                        <span><i class="fa fa-envelope text-slate-400 mr-1"></i>{{ $client->email ?? 'N/A' }}</span>
                        <span><i class="fa fa-phone text-slate-400 mr-1"></i>{{ $client->phone ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Client Account Management Team -->
            <div class="bg-white p-3 rounded-xl border border-slate-200/90 text-xs space-y-1 sm:min-w-[200px]">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Assigned Account Team</span>
                <div class="font-semibold text-slate-800 flex items-center justify-between">
                    <span class="text-slate-500">SMM Lead:</span>
                    <span class="font-bold text-purple-700">{{ $client->assignedSmm->name ?? 'Unassigned' }}</span>
                </div>
                <div class="font-semibold text-slate-800 flex items-center justify-between">
                    <span class="text-slate-500">Sales Exec:</span>
                    <span class="font-bold text-blue-700">{{ $client->assignedSales->name ?? 'Unassigned' }}</span>
                </div>
            </div>
        </div>

        <!-- EXECUTIVE PERFORMANCE SUMMARY (Score + 4 KPIs) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                    <i class="fa fa-gauge-high text-amber-500"></i>
                    <span>Executive Performance Overview</span>
                </h3>

                <!-- Composite Grade & Score -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-500">Overall Performance Index:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-black border flex items-center gap-1.5 {{ $gradeBadge }}">
                        <i class="fa fa-award"></i> Grade {{ $grade }} ({{ $performanceScore }}/100)
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- 1. Content Deliverables -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-1">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Social Media Deliverables</span>
                    <div class="text-2xl font-black text-slate-900">
                        {{ $postsPublished }} <span class="text-xs text-slate-400 font-normal">/ {{ $postsCount }} Posts</span>
                    </div>
                    <div class="text-[11px] text-teal-600 font-bold flex items-center gap-1">
                        <i class="fa fa-circle-check"></i>
                        {{ $postsCount > 0 ? round(($postsPublished / $postsCount) * 100) : 0 }}% Delivery Success
                    </div>
                </div>

                <!-- 2. Ad Spend & Leads -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-1">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Ad Spend & Leads</span>
                    <div class="text-2xl font-black text-slate-900">
                        {{ $currency }}{{ number_format($totalSpend, 0) }}
                    </div>
                    <div class="text-[11px] text-indigo-600 font-bold flex items-center gap-1">
                        <i class="fa fa-user-plus"></i>
                        {{ $totalLeads }} Leads Acquired ({{ $currency }}{{ $overallCpl }}/lead)
                    </div>
                </div>

                <!-- 3. Return on Ad Spend (ROAS) -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-1">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Campaign ROAS & Value</span>
                    <div class="text-2xl font-black text-emerald-600">
                        {{ $overallRoas }}x
                    </div>
                    <div class="text-[11px] text-slate-600 font-bold">
                        {{ $currency }}{{ number_format($totalRevenue, 0) }} Revenue Generated
                    </div>
                </div>

                <!-- 4. Retainer & Billing Status -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-1">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Monthly Retainer & Due</span>
                    <div class="text-2xl font-black text-slate-900">
                        {{ $currency }}{{ number_format($monthlyRetainerValue, 0) }}
                    </div>
                    <div class="text-[11px] {{ $totalBalance > 0 ? 'text-rose-600 font-extrabold' : 'text-emerald-600 font-bold' }}">
                        {{ $totalBalance > 0 ? 'Outstanding: ' . $currency . number_format($totalBalance, 0) : 'All Invoices Settled' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 1: SOCIAL MEDIA & EDITORIAL DELIVERABLES -->
        <div class="space-y-4 pt-2 border-t border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <i class="fa fa-calendar-days text-purple-600"></i>
                        <span>1. Social Media Deliverables & Creative Calendar</span>
                    </h3>
                    <p class="text-xs text-slate-500">Itemized posts created, approved, and published during this month.</p>
                </div>
                <div class="flex items-center gap-2 text-xs font-bold">
                    <span class="px-2.5 py-1 bg-teal-50 text-teal-700 rounded-lg border border-teal-200">
                        {{ $postsPublished }} Published
                    </span>
                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg border border-indigo-200">
                        {{ $postsApproved }} Approved
                    </span>
                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg border border-amber-200">
                        {{ $postsInReview }} In Review
                    </span>
                </div>
            </div>

            <!-- Format Distribution Breakdown -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                @foreach(App\Models\ContentPost::FORMATS as $fKey => $fMeta)
                    @php $fCount = $postsByFormat[$fKey] ?? 0; @endphp
                    <div class="p-3 rounded-xl border border-slate-200 bg-white space-y-1">
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span class="font-bold flex items-center gap-1.5">
                                <i class="fa {{ $fMeta['icon'] }} text-slate-400"></i>
                                {{ $fMeta['label'] }}
                            </span>
                        </div>
                        <div class="text-lg font-black text-slate-900">
                            {{ $fCount }} <span class="text-[10px] text-slate-400 font-normal">assets</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Content Deliverables Table -->
            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3">Post Title & Concept</th>
                            <th class="py-2.5 px-3">Format</th>
                            <th class="py-2.5 px-3">Platform</th>
                            <th class="py-2.5 px-3 text-center">Workflow Stage</th>
                            <th class="py-2.5 px-3 text-right">Creative Link</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($posts as $post)
                        @php 
                            $stage = $post->stage_info;
                            $format = $post->format_info;
                            $platform = $post->platform_info;
                        @endphp
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-2.5 px-3 font-mono font-bold text-slate-700 whitespace-nowrap">
                                {{ $post->date ? $post->date->format('d M Y') : 'TBD' }}
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="font-bold text-slate-900">{{ $post->title }}</div>
                                @if($post->client_feedback)
                                    <div class="text-[10px] text-amber-700 font-semibold mt-0.5">
                                        <i class="fa fa-comment-dots mr-1"></i>Feedback: {{ $post->client_feedback }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold {{ $format['badge'] }}">
                                    <i class="fa {{ $format['icon'] }}"></i> {{ $format['label'] }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 font-bold text-slate-700">
                                    <i class="fa {{ $platform['icon'] }}" style="color: {{ $platform['color'] }}"></i>
                                    {{ $platform['label'] }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $post->status === 'published' ? 'bg-teal-100 text-teal-800' : ($post->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700') }}">
                                    <i class="fa {{ $stage['icon'] }}"></i> {{ $stage['label'] }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-right whitespace-nowrap">
                                @if($post->creative_url)
                                    <a href="{{ $post->creative_url }}" target="_blank" class="text-blue-600 hover:underline font-bold text-[11px] inline-flex items-center gap-1">
                                        <span>View Asset</span>
                                        <i class="fa fa-arrow-up-right-from-square text-[9px]"></i>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">In repository</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 italic">
                                No content calendar posts recorded for this client in {{ $startDate->format('F Y') }}.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECTION 2: PERFORMANCE MARKETING & PAID ADS -->
        <div class="space-y-4 pt-2 border-t border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <i class="fa fa-bullseye text-blue-600"></i>
                        <span>2. Performance Marketing & Ad Campaigns (Meta & Google Ads)</span>
                    </h3>
                    <p class="text-xs text-slate-500">Spend breakdown, leads generated, click-through performance, and return on ad spend.</p>
                </div>
                <div class="text-xs font-mono font-bold text-slate-700">
                    Total Spend: <span class="text-blue-700 font-extrabold">{{ $currency }}{{ number_format($totalSpend, 0) }}</span> | Leads: <span class="text-emerald-700 font-extrabold">{{ $totalLeads }}</span>
                </div>
            </div>

            <!-- Ad Campaigns Table -->
            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                            <th class="py-2.5 px-3">Platform</th>
                            <th class="py-2.5 px-3">Campaign Name & Objective</th>
                            <th class="py-2.5 px-3 text-right">Budget / Spend</th>
                            <th class="py-2.5 px-3 text-right">Impressions</th>
                            <th class="py-2.5 px-3 text-right">Clicks / CTR</th>
                            <th class="py-2.5 px-3 text-right">Leads / CPL</th>
                            <th class="py-2.5 px-3 text-right">ROAS</th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-mono">
                        @forelse($campaigns as $camp)
                        @php 
                            $plat = App\Models\Campaign::PLATFORMS[$camp->platform] ?? ['label' => ucfirst($camp->platform), 'badge' => 'bg-slate-100 text-slate-800'];
                        @endphp
                        <tr class="hover:bg-slate-50/60 font-medium">
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold {{ $plat['badge'] }}">
                                    <i class="fa {{ $plat['icon'] ?? 'fa-bullseye' }}"></i> {{ $plat['label'] }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="font-bold text-slate-900 font-sans">{{ $camp->name }}</div>
                                <div class="text-[10px] text-slate-500 font-sans">
                                    Objective: {{ ucfirst($camp->objective) }} · {{ $camp->target_audience ?? 'Broad' }}
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-right whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $currency }}{{ number_format($camp->spend, 0) }}</div>
                                <div class="text-[10px] text-slate-400">Budget: {{ $currency }}{{ number_format($camp->budget, 0) }}</div>
                            </td>
                            <td class="py-2.5 px-3 text-right whitespace-nowrap font-bold text-slate-700">
                                {{ number_format($camp->impressions) }}
                            </td>
                            <td class="py-2.5 px-3 text-right whitespace-nowrap">
                                <div class="font-bold text-slate-800">{{ number_format($camp->clicks) }}</div>
                                <div class="text-[10px] text-blue-600 font-bold">{{ $camp->ctr }}% CTR</div>
                            </td>
                            <td class="py-2.5 px-3 text-right whitespace-nowrap">
                                <div class="font-bold text-emerald-700">{{ $camp->leads_count }} Leads</div>
                                <div class="text-[10px] text-slate-500">CPL: {{ $currency }}{{ $camp->cpl }}</div>
                            </td>
                            <td class="py-2.5 px-3 text-right whitespace-nowrap">
                                <span class="font-extrabold text-sm {{ $camp->roas >= 3.0 ? 'text-emerald-600' : 'text-slate-800' }}">
                                    {{ $camp->roas }}x
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider {{ $camp->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $camp->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-400 italic font-sans">
                                No ad campaigns active for this client during {{ $startDate->format('F Y') }}.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECTION 3: CREATIVE TASKS & PROJECT DELIVERABLES -->
        <div class="space-y-4 pt-2 border-t border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <i class="fa fa-list-check text-amber-500"></i>
                        <span>3. Creative Tasks & Technical Deliverables</span>
                    </h3>
                    <p class="text-xs text-slate-500">Graphic design batches, video editing, web updates, and copywriting milestones completed.</p>
                </div>
                <div class="text-xs font-bold text-slate-700">
                    <span class="text-emerald-600">{{ $tasksCompleted }} Completed</span> / {{ $tasksTotal }} Total Assigned
                </div>
            </div>

            <!-- Tasks Table -->
            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                            <th class="py-2.5 px-3">Task Deliverable</th>
                            <th class="py-2.5 px-3">Assignee</th>
                            <th class="py-2.5 px-3">Deadline</th>
                            <th class="py-2.5 px-3">Checklist Progress</th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($tasks as $t)
                        @php 
                            $clp = $t->checklist_progress;
                        @endphp
                        <tr class="hover:bg-slate-50/60 font-medium">
                            <td class="py-2.5 px-3">
                                <div class="font-bold text-slate-900">{{ $t->title }}</div>
                                @if($t->notes)
                                    <div class="text-[10px] text-slate-500 line-clamp-1">{{ $t->notes }}</div>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 whitespace-nowrap text-slate-700">
                                {{ $t->assignedTo->name ?? 'Team Member' }}
                            </td>
                            <td class="py-2.5 px-3 whitespace-nowrap font-mono text-[11px] {{ $t->deadline && $t->deadline < today() && $t->status !== 'done' ? 'text-rose-600 font-bold' : 'text-slate-600' }}">
                                {{ $t->deadline ? $t->deadline->format('d M Y') : 'Ongoing' }}
                            </td>
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                @if($clp['total'] > 0)
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $clp['percent'] }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-mono font-bold text-slate-600">{{ $clp['done'] }}/{{ $clp['total'] }}</span>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-400">Single task</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $t->status === 'done' ? 'bg-emerald-100 text-emerald-800' : ($t->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700') }}">
                                    {{ str_replace('_', ' ', $t->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400 italic">
                                No standalone project tasks logged for this client during {{ $startDate->format('F Y') }}.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECTION 4: FINANCIAL STATEMENT & RETAINER PACKAGES -->
        <div class="space-y-4 pt-2 border-t border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <i class="fa fa-receipt text-emerald-600"></i>
                        <span>4. Monthly Retainer Scope & Invoicing Summary</span>
                    </h3>
                    <p class="text-xs text-slate-500">Contracted monthly agency services catalog and billing audit.</p>
                </div>
                <div class="text-xs font-mono font-bold text-slate-700">
                    Monthly Contract Value: <span class="text-emerald-700 font-extrabold">{{ $currency }}{{ number_format($monthlyRetainerValue, 0) }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Contracted Services List -->
                <div class="border border-slate-200 rounded-2xl p-4 bg-white space-y-3">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700 block">Active Retainer Services</span>
                    <div class="divide-y divide-slate-100 text-xs">
                        @forelse($client->clientServices as $cs)
                        <div class="py-2 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-800">{{ $cs->custom_name ?: ($cs->service->name ?? 'Retainer Service') }}</span>
                                <span class="text-[10px] text-slate-400 block font-mono">Qty: {{ $cs->qty }}</span>
                            </div>
                            <span class="font-mono font-bold text-slate-900">{{ $currency }}{{ number_format($cs->price * $cs->qty, 0) }}/mo</span>
                        </div>
                        @empty
                        <div class="py-4 text-center text-slate-400 italic">No specific service package bound</div>
                        @endforelse
                    </div>
                </div>

                <!-- Invoices Issued in Reporting Period -->
                <div class="border border-slate-200 rounded-2xl p-4 bg-white space-y-3">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700 block">Invoices Billed This Month</span>
                    <div class="divide-y divide-slate-100 text-xs">
                        @forelse($invoices as $inv)
                        <div class="py-2 flex items-center justify-between">
                            <div>
                                <span class="font-bold font-mono text-slate-900">{{ $inv->invoice_number }}</span>
                                <span class="text-[10px] text-slate-400 block">Issued: {{ $inv->issued_date ? $inv->issued_date->format('d M Y') : 'N/A' }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-mono font-extrabold text-slate-900 block">{{ $currency }}{{ number_format($inv->total, 0) }}</span>
                                <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold uppercase {{ $inv->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $inv->status }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="py-4 text-center text-slate-400 italic">No invoices issued in this reporting cycle</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 5: AGENCY RECOMMENDATIONS & NEXT MONTH ROADMAP -->
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-2xl p-6 space-y-3 print:bg-none print:text-slate-900 print:border print:border-slate-300">
            <h3 class="text-sm font-extrabold uppercase tracking-wider text-amber-400 print:text-slate-900 flex items-center gap-2">
                <i class="fa fa-lightbulb"></i>
                <span>5. Strategic Observations & Next Month Action Plan</span>
            </h3>
            <p class="text-xs text-slate-300 print:text-slate-700 leading-relaxed">
                <strong>Monthly Key Takeaway:</strong> During {{ $startDate->format('F Y') }}, creative and advertising efforts generated <strong>{{ $totalLeads }} qualified customer inquiries</strong> at an average cost of <strong>{{ $currency }}{{ $overallCpl }}</strong>, with a cumulative <strong>{{ $overallRoas }}x Return on Ad Spend</strong>. 
                Social media editorial production reached <strong>{{ $postsPublished }} published creatives</strong> across target channels.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs">
                <div class="p-3 bg-slate-800/80 print:bg-slate-50 print:border print:border-slate-200 rounded-xl space-y-1">
                    <span class="font-bold text-emerald-400 print:text-emerald-700 block">Recommended Focus:</span>
                    <span class="text-slate-300 print:text-slate-600 text-[11px]">Scale video reel and carousel formats which exhibited highest engagement rates across social platforms.</span>
                </div>
                <div class="p-3 bg-slate-800/80 print:bg-slate-50 print:border print:border-slate-200 rounded-xl space-y-1">
                    <span class="font-bold text-blue-400 print:text-blue-700 block">Ad Budget Optimization:</span>
                    <span class="text-slate-300 print:text-slate-600 text-[11px]">Reallocate underperforming audience segments to top converting custom lookalikes to reduce CPL.</span>
                </div>
                <div class="p-3 bg-slate-800/80 print:bg-slate-50 print:border print:border-slate-200 rounded-xl space-y-1">
                    <span class="font-bold text-amber-400 print:text-amber-700 block">Upcoming Milestones:</span>
                    <span class="text-slate-300 print:text-slate-600 text-[11px]">Prepare seasonal promotional campaign calendar 7 business days prior to next billing cycle kickoff.</span>
                </div>
            </div>
        </div>

        <!-- FORMAL SIGN-OFF & ENDORSEMENTS -->
        <div class="pt-6 border-t border-slate-200 grid grid-cols-2 gap-8 text-xs">
            <div class="space-y-6">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Prepared & Approved By Agency</span>
                <div class="border-b border-slate-300 w-48 h-8"></div>
                <div class="space-y-0.5">
                    <div class="font-extrabold text-slate-900">{{ auth()->user()->name }}</div>
                    <div class="text-[11px] text-slate-500">{{ auth()->user()->designation ?? 'Lead Account Director' }} · {{ $agencySettings['agency_name'] }}</div>
                </div>
            </div>

            <div class="space-y-6 text-right">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Client Acknowledgment & Review</span>
                <div class="border-b border-slate-300 w-48 h-8 ml-auto"></div>
                <div class="space-y-0.5">
                    <div class="font-extrabold text-slate-900">{{ $client->name }}</div>
                    <div class="text-[11px] text-slate-500">{{ $client->company ?? 'Commercial Partner' }}</div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
