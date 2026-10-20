@extends('layouts.app')

@section('title', 'Daily Worklogs & QA Review Hub — DMS CRM')
@section('header_title', 'Daily Worklogs & QA Review Hub')

@section('content')
<div class="space-y-6" x-data="{
    showSubmitModal: false,
    showReviewModal: false,
    reviewActionUrl: '',
    reviewLog: {
        id: null,
        title: '',
        userName: '',
        clientName: '',
        rating: 5,
        status: 'approved',
        feedback: ''
    },
    openReview(log) {
        this.reviewLog = { ...log };
        this.reviewActionUrl = '/worklogs/' + log.id + '/review';
        this.showReviewModal = true;
    }
}">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 border border-indigo-900/40">
        <div class="space-y-2 max-w-2xl relative z-10">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                    <i class="fa fa-clipboard-check mr-1"></i> QA & Deliverables
                </span>
                <span class="text-xs text-slate-400 font-mono font-bold">{{ date('F Y', strtotime($selectedMonth . '-01')) }}</span>
                @if($totalPending > 0 && $isReviewer)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                    {{ $totalPending }} Pending Review
                </span>
                @endif
            </div>
            <h2 class="text-2xl sm:text-3xl font-black tracking-tight">Daily Worklogs & SMM Quality Review</h2>
            <p class="text-xs sm:text-sm text-slate-300 font-medium leading-relaxed">
                Log daily creative outputs, track completed deliverable quotas, and ensure creative excellence via SMM 1-5 star quality reviews.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 relative z-10">
            <!-- Month Selector -->
            <form action="{{ route('worklogs.index') }}" method="GET" class="flex items-center gap-2 bg-slate-800/80 p-1.5 rounded-2xl border border-slate-700/80">
                @if(request('user_id')) <input type="hidden" name="user_id" value="{{ request('user_id') }}"> @endif
                @if(request('client_id')) <input type="hidden" name="client_id" value="{{ request('client_id') }}"> @endif
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                <i class="fa fa-calendar text-indigo-400 text-xs pl-2"></i>
                <select name="month" onchange="this.form.submit()" class="bg-slate-900 text-white border border-slate-700 rounded-xl px-3 py-1.5 text-xs font-bold focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                    @foreach($monthOptions as $mKey => $mLabel)
                        <option value="{{ $mKey }}" {{ $selectedMonth === $mKey ? 'selected' : '' }}>
                            {{ $mLabel }}
                        </option>
                    @endforeach
                </select>
            </form>

            <!-- Submit Daily Output Button -->
            <button @click="showSubmitModal = true" class="px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-500/20 transition-all flex items-center gap-2">
                <i class="fa fa-plus-circle"></i> Log Daily Work
            </button>
        </div>
    </div>

    <!-- 4 Aggregate KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Submissions -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Submissions</span>
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-paper-plane"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900">{{ number_format($totalSubmissions) }}</div>
            <div class="text-[11px] font-semibold text-slate-500">Deliverables logged this month</div>
        </div>

        <!-- 2. Approved Deliverables -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Approved Deliverables</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-check-circle"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-600">{{ number_format($totalApproved) }}</div>
            <div class="text-[11px] font-semibold text-slate-500">
                {{ $totalSubmissions > 0 ? round(($totalApproved / $totalSubmissions) * 100) : 0 }}% approval rate
            </div>
        </div>

        <!-- 3. Pending SMM Review -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pending QA Review</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-clock"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-600 flex items-center gap-2">
                {{ number_format($totalPending) }}
                @if($totalPending > 0)
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                @endif
            </div>
            <div class="text-[11px] font-semibold text-slate-500">Awaiting SMM quality rating</div>
        </div>

        <!-- 4. Quality Rating -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Avg Quality Score</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-star"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900 flex items-baseline gap-1.5">
                <span>{{ $avgRating > 0 ? number_format($avgRating, 1) : '—' }}</span>
                <span class="text-xs font-semibold text-slate-400">/ 5.0</span>
            </div>
            <div class="flex items-center gap-1 text-amber-400 text-xs">
                @for($s = 1; $s <= 5; $s++)
                    <i class="fa fa-star {{ $avgRating >= $s ? 'text-amber-400' : ($avgRating >= ($s - 0.5) ? 'fa-star-half-alt text-amber-400' : 'text-slate-200') }}"></i>
                @endfor
                <span class="text-[11px] font-semibold text-slate-500 ml-1">Overall rating</span>
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Status Filter Pills -->
        <div class="flex flex-wrap items-center gap-1.5">
            <a href="{{ route('worklogs.index', array_merge(request()->except(['status', 'page']), ['status' => ''])) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ !request('status') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                All Logs ({{ $totalSubmissions }})
            </a>
            <a href="{{ route('worklogs.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ request('status') === 'pending' ? 'bg-white' : 'bg-amber-500' }}"></span>
                Pending Review ({{ $totalPending }})
            </a>
            <a href="{{ route('worklogs.index', array_merge(request()->except(['status', 'page']), ['status' => 'approved'])) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ request('status') === 'approved' ? 'bg-white' : 'bg-emerald-500' }}"></span>
                Approved ({{ $totalApproved }})
            </a>
            <a href="{{ route('worklogs.index', array_merge(request()->except(['status', 'page']), ['status' => 'revision'])) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request('status') === 'revision' ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ request('status') === 'revision' ? 'bg-white' : 'bg-rose-500' }}"></span>
                Revision Needed ({{ $totalRevision }})
            </a>
        </div>

        <!-- Dropdown Filters: Staff & Client -->
        <form action="{{ route('worklogs.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <input type="hidden" name="month" value="{{ $selectedMonth }}">
            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif

            @if($isReviewer)
            <div class="relative">
                <select name="user_id" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold rounded-xl px-3 py-2 pr-7 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                    <option value="">All Team Members</option>
                    @foreach($teamMembers as $member)
                        <option value="{{ $member->id }}" {{ request('user_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->name }} ({{ strtoupper($member->role) }})
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="relative">
                <select name="client_id" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold rounded-xl px-3 py-2 pr-7 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                    <option value="">All Clients</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if(request('user_id') || request('client_id') || request('status'))
            <a href="{{ route('worklogs.index', ['month' => $selectedMonth]) }}" class="px-2.5 py-2 text-xs font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl" title="Reset Filters">
                <i class="fa fa-undo"></i>
            </a>
            @endif
        </form>
    </div>

    <!-- Worklogs Table Feed -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Date & Staff</th>
                        <th class="py-3.5 px-4">Deliverable / Task</th>
                        <th class="py-3.5 px-4 text-center">Output / Quota</th>
                        <th class="py-3.5 px-4 text-center">QA Rating</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4">Review Feedback</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($worklogs as $log)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <!-- Date & Staff -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-indigo-700 text-white font-bold text-xs flex items-center justify-center shadow-sm">
                                    {{ strtoupper(substr($log->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900">{{ $log->user->name ?? 'Team Member' }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium">
                                        <span class="uppercase tracking-wider font-semibold text-slate-500">{{ $log->user->role ?? 'Staff' }}</span>
                                        • {{ $log->date->format('d M, Y') }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Deliverable / Task -->
                        <td class="py-3.5 px-4 max-w-xs sm:max-w-md">
                            <div class="font-bold text-slate-900 leading-snug">{{ $log->title }}</div>
                            <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                @if($log->client)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    <i class="fa fa-building text-[9px] text-slate-400"></i>
                                    {{ $log->client->name }}
                                </span>
                                @endif

                                @if($log->task)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-100" title="{{ $log->task->title }}">
                                    <i class="fa fa-tasks text-[9px] text-indigo-400"></i>
                                    {{ \Illuminate\Support\Str::limit($log->task->title, 25) }}
                                </span>
                                @endif
                            </div>
                            @if($log->description)
                            <div class="text-[11px] text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                {{ $log->description }}
                            </div>
                            @endif
                        </td>

                        <!-- Output / Quota -->
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <div class="inline-flex flex-col items-center">
                                <span class="font-black text-slate-900 text-sm">
                                    {{ $log->qty_done }} <span class="text-xs font-normal text-slate-400">/ {{ $log->qty_total }}</span>
                                </span>
                                @php
                                    $pct = $log->qty_total > 0 ? min(100, round(($log->qty_done / $log->qty_total) * 100)) : 100;
                                @endphp
                                <div class="w-16 h-1.5 bg-slate-100 rounded-full mt-1 overflow-hidden">
                                    <div class="h-full rounded-full {{ $pct >= 100 ? 'bg-emerald-500' : 'bg-indigo-500' }}" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-400 mt-0.5">{{ $pct }}%</span>
                            </div>
                        </td>

                        <!-- QA Rating -->
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            @if($log->quality_rating)
                            <div class="inline-flex flex-col items-center">
                                <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star {{ $log->quality_rating >= $i ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-[10px] font-extrabold text-slate-700 mt-0.5">{{ $log->quality_rating }}.0 / 5.0</span>
                            </div>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">
                                <i class="fa fa-minus"></i> Unrated
                            </span>
                            @endif
                        </td>

                        <!-- Status Badge -->
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            @if($log->status === 'approved')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="fa fa-check-circle"></i> Approved
                            </span>
                            @elseif($log->status === 'revision')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                <i class="fa fa-exclamation-circle"></i> Revision
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                Pending QA
                            </span>
                            @endif
                        </td>

                        <!-- Review Feedback -->
                        <td class="py-3.5 px-4 max-w-xs">
                            @if($log->smm_feedback)
                            <div class="text-[11px] text-slate-700 bg-slate-50 border border-slate-200/80 rounded-xl p-2.5 relative">
                                <div class="flex items-center justify-between text-[10px] text-slate-400 mb-1 font-semibold">
                                    <span>
                                        <i class="fa fa-user-check text-indigo-500 mr-1"></i>
                                        {{ $log->reviewedBy->name ?? 'SMM Lead' }}
                                    </span>
                                </div>
                                <p class="italic text-slate-600 line-clamp-2">"{{ $log->smm_feedback }}"</p>
                            </div>
                            @elseif($log->status === 'approved')
                            <div class="text-[10px] text-slate-400 italic">
                                Reviewed & Approved by {{ $log->reviewedBy->name ?? 'SMM' }}
                            </div>
                            @else
                            <div class="text-[10px] text-slate-400 italic">Awaiting QA feedback...</div>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                @if($isReviewer)
                                <button type="button"
                                        @click="openReview({
                                            id: {{ $log->id }},
                                            title: '{{ addslashes($log->title) }}',
                                            userName: '{{ addslashes($log->user->name ?? 'Team Member') }}',
                                            clientName: '{{ addslashes($log->client->name ?? 'General Deliverable') }}',
                                            rating: {{ $log->quality_rating ?? 5 }},
                                            status: '{{ $log->status === 'pending' ? 'approved' : $log->status }}',
                                            feedback: '{{ addslashes($log->smm_feedback ?? '') }}'
                                        })"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $log->status === 'pending' ? 'bg-amber-500 hover:bg-amber-600 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}"
                                        title="{{ $log->status === 'pending' ? 'Review & Score Deliverable' : 'Update QA Review' }}">
                                    <i class="fa fa-star text-[10px] mr-1"></i>
                                    {{ $log->status === 'pending' ? 'Rate QA' : 'Edit QA' }}
                                </button>
                                @endif

                                @if(($log->user_id === auth()->id() && $log->status !== 'approved') || auth()->user()->isOwner() || auth()->user()->isAdmin())
                                <form action="{{ route('worklogs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Delete this worklog entry?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete Log">
                                        <i class="fa fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="max-w-sm mx-auto space-y-3">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-lg">
                                    <i class="fa fa-clipboard-list"></i>
                                </div>
                                <div class="font-bold text-slate-700 text-sm">No worklogs found</div>
                                <p class="text-xs text-slate-400">
                                    No daily work outputs matching your filters for {{ date('F Y', strtotime($selectedMonth . '-01')) }}.
                                </p>
                                <button @click="showSubmitModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all inline-flex items-center gap-2">
                                    <i class="fa fa-plus"></i> Submit First Daily Log
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($worklogs->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $worklogs->links() }}
        </div>
        @endif
    </div>

    <!-- MODAL 1: Submit Daily Work Output -->
    <div x-show="showSubmitModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto"
         @keydown.escape.window="showSubmitModal = false">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative my-8"
             @click.away="showSubmitModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa fa-calendar-check"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Submit Daily Work Deliverable</h3>
                        <p class="text-xs text-slate-500 font-medium">Record outputs for SMM quality evaluation</p>
                    </div>
                </div>
                <button @click="showSubmitModal = false" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <form action="{{ route('worklogs.store') }}" method="POST" class="space-y-4 pt-4">
                @csrf

                <!-- Work Date -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Date of Work <span class="text-rose-500">*</span></label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Deliverable Title -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deliverable Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="e.g. 4 Carousel Posts for Aarong Puja Campaign" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Client & Task (Two Columns) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Client</label>
                        <select name="client_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                            <option value="">— Internal / General —</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Assigned Task</label>
                        <select name="task_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                            <option value="">— Unlinked / Ad-hoc —</option>
                            @foreach($myTasks as $task)
                                <option value="{{ $task->id }}">{{ \Illuminate\Support\Str::limit($task->title, 25) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Output Quantities (Done vs Total) -->
                <div class="grid grid-cols-2 gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Units Done <span class="text-rose-500">*</span></label>
                        <input type="number" name="qty_done" min="1" value="1" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-black text-indigo-600 focus:ring-2 focus:ring-indigo-500">
                        <span class="text-[10px] text-slate-400">Completed items</span>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Target Total <span class="text-rose-500">*</span></label>
                        <input type="number" name="qty_total" min="1" value="1" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-black text-slate-700 focus:ring-2 focus:ring-indigo-500">
                        <span class="text-[10px] text-slate-400">Batch quota total</span>
                    </div>
                </div>

                <!-- Deliverable Notes & Links -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deliverable Notes & Asset Links</label>
                    <textarea name="description" rows="3" placeholder="Figma links, Google Drive asset URLs, video render specs, or creative brief details..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <!-- Form Footer Actions -->
                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" @click="showSubmitModal = false" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all flex items-center gap-2">
                        <i class="fa fa-paper-plane"></i> Submit for QA Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: SMM Quality Review & Star Rating -->
    <div x-show="showReviewModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto"
         @keydown.escape.window="showReviewModal = false">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative my-8"
             @click.away="showReviewModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa fa-star"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">SMM Quality Review</h3>
                        <p class="text-xs text-slate-500 font-medium">Evaluate output against creative standards</p>
                    </div>
                </div>
                <button @click="showReviewModal = false" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <!-- Deliverable Info Card -->
            <div class="mt-4 p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-1">
                <div class="text-[10px] uppercase tracking-wider font-extrabold text-slate-400" x-text="reviewLog.clientName"></div>
                <div class="font-bold text-slate-900 text-sm" x-text="reviewLog.title"></div>
                <div class="text-[11px] text-slate-500 font-medium">
                    Submitted by <span class="font-bold text-slate-700" x-text="reviewLog.userName"></span>
                </div>
            </div>

            <form :action="reviewActionUrl" method="POST" class="space-y-4 pt-4">
                @csrf

                <!-- Star Rating Selector (1 to 5) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Quality Score (1 to 5 Stars) <span class="text-rose-500">*</span></label>
                    <input type="hidden" name="quality_rating" :value="reviewLog.rating">
                    <div class="flex items-center gap-2">
                        <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                            <button type="button" 
                                    @click="reviewLog.rating = star"
                                    class="w-10 h-10 rounded-xl flex items-center justify-center text-lg transition-transform hover:scale-110 focus:outline-none"
                                    :class="reviewLog.rating >= star ? 'bg-amber-100 text-amber-500 border border-amber-300' : 'bg-slate-100 text-slate-300 border border-slate-200'">
                                <i class="fa fa-star"></i>
                            </button>
                        </template>
                        <span class="text-xs font-black text-slate-700 ml-2" x-text="
                            reviewLog.rating == 1 ? '1 Star (Needs Major Work)' :
                            reviewLog.rating == 2 ? '2 Stars (Below Standard)' :
                            reviewLog.rating == 3 ? '3 Stars (Acceptable)' :
                            reviewLog.rating == 4 ? '4 Stars (High Quality)' :
                            '5 Stars (Exceptional Standard)'
                        "></span>
                    </div>
                </div>

                <!-- Review Status Decision -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">QA Evaluation Verdict <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2.5 p-3 rounded-2xl border cursor-pointer transition-all"
                               :class="reviewLog.status === 'approved' ? 'bg-emerald-50/70 border-emerald-500 text-emerald-900 ring-2 ring-emerald-500/20' : 'bg-white border-slate-200 hover:bg-slate-50 text-slate-700'">
                            <input type="radio" name="status" value="approved" x-model="reviewLog.status" class="text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <div class="text-xs font-bold flex items-center gap-1.5">
                                    <i class="fa fa-check-circle text-emerald-600"></i> Approve
                                </div>
                                <div class="text-[10px] text-slate-400">Meets brand guidelines</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-2.5 p-3 rounded-2xl border cursor-pointer transition-all"
                               :class="reviewLog.status === 'revision' ? 'bg-rose-50/70 border-rose-500 text-rose-900 ring-2 ring-rose-500/20' : 'bg-white border-slate-200 hover:bg-slate-50 text-slate-700'">
                            <input type="radio" name="status" value="revision" x-model="reviewLog.status" class="text-rose-600 focus:ring-rose-500">
                            <div>
                                <div class="text-xs font-bold flex items-center gap-1.5">
                                    <i class="fa fa-redo text-rose-600"></i> Revision
                                </div>
                                <div class="text-[10px] text-slate-400">Needs corrections/rework</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- SMM Feedback Remarks -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">SMM Feedback & Constructive Remarks</label>
                    <textarea name="smm_feedback" x-model="reviewLog.feedback" rows="3" placeholder="Provide specific feedback, color/typography adjustments, audio balance notes, or praise..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <!-- Form Footer Actions -->
                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" @click="showReviewModal = false" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-900 text-xs font-black rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
                        <i class="fa fa-save"></i> Save QA Evaluation
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
