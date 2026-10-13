@extends('layouts.app')

@section('title', 'Monthly Client Reports — DMS Agency CRM')
@section('header_title', 'Monthly Client Reports & Deliverables')

@section('content')
<div class="space-y-6">
    <!-- Header Banner & Month Selector -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2 max-w-2xl">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
                    <i class="fa fa-chart-pie mr-1"></i> Executive Performance Hub
                </span>
                <span class="text-xs text-slate-400 font-mono font-bold">{{ $startDate->format('F Y') }}</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black tracking-tight">Monthly Client Performance Reporting</h2>
            <p class="text-xs sm:text-sm text-slate-300 font-medium leading-relaxed">
                Consolidated client deliverables, social media publishing milestones, Meta & Google Ad ROAS metrics, creative tasks completed, and billing health.
            </p>
        </div>

        <!-- Month Picker Form -->
        <form action="{{ route('reports.index') }}" method="GET" class="flex items-center gap-3 bg-slate-800/80 p-2 rounded-2xl border border-slate-700/80">
            <div class="flex items-center gap-2 px-2 text-xs font-bold text-slate-300">
                <i class="fa fa-calendar text-amber-400"></i>
                <span>Report Month:</span>
            </div>
            <select name="month" onchange="this.form.submit()" class="bg-slate-900 text-white border border-slate-700 rounded-xl px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-amber-500 focus:outline-none cursor-pointer">
                @foreach($monthOptions as $mKey => $mLabel)
                    <option value="{{ $mKey }}" {{ $selectedMonth === $mKey ? 'selected' : '' }}>
                        {{ $mLabel }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Agency-Wide Monthly Aggregate KPI Cards (6 Grid) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- 1. Content Posts Delivered -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Posts Delivered</span>
                <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-share-nodes"></i>
                </div>
            </div>
            <div class="text-xl font-black text-slate-900">
                {{ $agencyPostsPublished }} <span class="text-xs text-slate-400 font-normal">/ {{ $agencyPostsTotal }}</span>
            </div>
            <div class="text-[10px] text-teal-600 font-bold flex items-center gap-1">
                <i class="fa fa-circle-check"></i>
                {{ $agencyPostsTotal > 0 ? round(($agencyPostsPublished / $agencyPostsTotal) * 100) : 0 }}% Published
            </div>
        </div>

        <!-- 2. Ad Spend -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Ad Spend</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-bullseye"></i>
                </div>
            </div>
            <div class="text-xl font-black text-slate-900">
                ৳{{ number_format($totalAdSpend, 0) }}
            </div>
            <div class="text-[10px] text-blue-600 font-bold">
                {{ $clientMetrics->sum('campaignsCount') }} Active Campaigns
            </div>
        </div>

        <!-- 3. Ad Leads Acquired -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Leads Acquired</span>
                <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-user-plus"></i>
                </div>
            </div>
            <div class="text-xl font-black text-slate-900">
                {{ number_format($totalAdLeads) }}
            </div>
            <div class="text-[10px] text-indigo-600 font-bold">
                Avg CPL: ৳{{ $totalAdLeads > 0 ? round($totalAdSpend / $totalAdLeads, 1) : 0 }}
            </div>
        </div>

        <!-- 4. Client Ad Revenue & ROAS -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Agency ROAS</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-arrow-trend-up"></i>
                </div>
            </div>
            <div class="text-xl font-black text-emerald-600">
                {{ $agencyRoas }}x
            </div>
            <div class="text-[10px] text-slate-500 font-bold truncate">
                ৳{{ number_format($totalAdRevenue, 0) }} Gross
            </div>
        </div>

        <!-- 5. Creative Tasks Done -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tasks Closed</span>
                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-check-double"></i>
                </div>
            </div>
            <div class="text-xl font-black text-slate-900">
                {{ $agencyTasksCompleted }}
            </div>
            <div class="text-[10px] text-amber-600 font-bold">
                Deliverables Done
            </div>
        </div>

        <!-- 6. Invoices Billed -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Retainers Billed</span>
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-file-invoice-dollar"></i>
                </div>
            </div>
            <div class="text-xl font-black text-slate-900">
                ৳{{ number_format($agencyInvoicesBilled, 0) }}
            </div>
            <div class="text-[10px] text-emerald-600 font-bold">
                ৳{{ number_format($agencyInvoicesCollected, 0) }} Paid
            </div>
        </div>
    </div>

    <!-- Active Clients Monthly Performance Matrix -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden" x-data="{ searchQuery: '', statusFilter: 'all' }">
        <div class="p-5 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fa fa-building text-amber-500"></i>
                    <span>Client Deliverables & Performance Matrix</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Click "Generate Report" on any client to view or export their comprehensive monthly performance dossier.</p>
            </div>

            <!-- Search and Filter Bar -->
            <div class="flex items-center gap-2">
                <div class="relative">
                    <i class="fa fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" x-model="searchQuery" placeholder="Search client name..." class="pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none font-semibold w-48 sm:w-64">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200/80">
                        <th class="py-3 px-4">Client Dossier</th>
                        <th class="py-3 px-4">Assigned Team</th>
                        <th class="py-3 px-4">Social Media Posts</th>
                        <th class="py-3 px-4">Ad Campaigns & Leads</th>
                        <th class="py-3 px-4">Tasks Done</th>
                        <th class="py-3 px-4">Retainer / Invoicing</th>
                        <th class="py-3 px-4 text-center">Health Score</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($clientMetrics as $item)
                    @php 
                        $c = $item['client'];
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-all" x-show="!searchQuery || '{{ strtolower($c->name . ' ' . $c->company) }}'.includes(searchQuery.toLowerCase())">
                        <!-- Client Dossier -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white font-extrabold flex items-center justify-center text-xs shadow-sm flex-shrink-0">
                                    {{ strtoupper(substr($c->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('reports.client', ['client' => $c->id, 'month' => $selectedMonth]) }}" class="font-extrabold text-slate-900 hover:text-amber-600 transition-colors block text-sm">
                                        {{ $c->name }}
                                    </a>
                                    <span class="text-[11px] text-slate-500 font-medium">{{ $c->company ?? 'Brand Client' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Assigned Team -->
                        <td class="py-3.5 px-4">
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-1.5 text-slate-700">
                                    <span class="w-3.5 text-center text-purple-600 font-bold"><i class="fa fa-share-nodes text-[10px]"></i></span>
                                    <span>{{ $c->assignedSmm->name ?? 'Unassigned' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-slate-500 text-[11px]">
                                    <span class="w-3.5 text-center text-blue-600"><i class="fa fa-user text-[10px]"></i></span>
                                    <span>{{ $c->assignedSales->name ?? 'Sales' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Social Media Posts -->
                        <td class="py-3.5 px-4">
                            <div class="space-y-1">
                                <div class="flex items-center justify-between font-bold text-slate-900">
                                    <span>{{ $item['postsPublished'] }} Published</span>
                                    <span class="text-[11px] text-slate-400 font-normal">/ {{ $item['postsTotal'] }} Planned</span>
                                </div>
                                <div class="w-28 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    @php 
                                        $postPct = $item['postsTotal'] > 0 ? min(100, round(($item['postsPublished'] / $item['postsTotal']) * 100)) : 0;
                                    @endphp
                                    <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $postPct }}%"></div>
                                </div>
                                @if($item['postsInReview'] > 0)
                                    <span class="text-[10px] text-amber-600 font-semibold block">
                                        <i class="fa fa-clock mr-0.5"></i> {{ $item['postsInReview'] }} in review
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Ad Campaigns & Leads -->
                        <td class="py-3.5 px-4">
                            @if($item['campaignsCount'] > 0)
                                <div class="space-y-0.5">
                                    <div class="font-extrabold text-slate-900 flex items-center gap-1.5">
                                        <span>৳{{ number_format($item['adSpend'], 0) }}</span>
                                        <span class="text-[10px] text-emerald-600 font-black px-1.5 py-0.2 bg-emerald-50 rounded">{{ $item['roas'] }}x ROAS</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500">
                                        {{ $item['adLeads'] }} leads (৳{{ $item['adLeads'] > 0 ? round($item['adSpend'] / $item['adLeads'], 0) : 0 }}/lead)
                                    </div>
                                </div>
                            @else
                                <span class="text-slate-400 text-[11px] italic">No ad spend logged</span>
                            @endif
                        </td>

                        <!-- Tasks Done -->
                        <td class="py-3.5 px-4">
                            <div class="space-y-0.5">
                                <div class="font-bold text-slate-800">
                                    {{ $item['tasksCompleted'] }} <span class="text-slate-400 font-normal">/ {{ $item['tasksTotal'] }}</span>
                                </div>
                                @php 
                                    $taskPct = $item['tasksTotal'] > 0 ? round(($item['tasksCompleted'] / $item['tasksTotal']) * 100) : 0;
                                @endphp
                                <span class="text-[10px] font-bold {{ $taskPct >= 75 ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ $taskPct }}% Finished
                                </span>
                            </div>
                        </td>

                        <!-- Retainer / Invoicing -->
                        <td class="py-3.5 px-4">
                            <div class="space-y-0.5">
                                <div class="font-bold text-slate-900">
                                    ৳{{ number_format($item['monthlyRetainer'], 0) }} <span class="text-[10px] text-slate-400 font-normal">/mo</span>
                                </div>
                                @if($item['balanceDue'] > 0)
                                    <span class="text-[10px] text-rose-600 font-extrabold flex items-center gap-1">
                                        <i class="fa fa-circle-exclamation"></i> Due: ৳{{ number_format($item['balanceDue'], 0) }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1">
                                        <i class="fa fa-circle-check"></i> Paid up
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Health Score -->
                        <td class="py-3.5 px-4 text-center">
                            @php 
                                $hs = $item['healthScore'];
                                $badgeClass = $hs >= 85 ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : ($hs >= 70 ? 'bg-blue-100 text-blue-800 border-blue-300' : 'bg-amber-100 text-amber-800 border-amber-300');
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-black border {{ $badgeClass }}">
                                {{ $hs }}/100
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('reports.client', ['client' => $c->id, 'month' => $selectedMonth]) }}" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                                    <i class="fa fa-chart-pie text-amber-400"></i>
                                    <span>Generate Report</span>
                                </a>
                                <a href="{{ route('reports.client.pdf', ['client' => $c->id, 'month' => $selectedMonth]) }}" title="Download PDF" class="p-1.5 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 rounded-xl transition-all border border-slate-200">
                                    <i class="fa fa-file-pdf"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-slate-400">
                            <i class="fa fa-folder-open text-3xl mb-2 block text-slate-300"></i>
                            <p class="font-bold">No active clients found for monthly report compilation.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
