@extends('layouts.app')

@section('title', 'Team Workload Capacity & Day Planner — DMS CRM')
@section('header_title', 'Team Workload & Day Planner')

@section('content')
<div class="space-y-6" x-data="{
    activeTab: 'capacity',
    showAddModal: false
}">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 border border-indigo-900/40">
        <div class="space-y-2 max-w-2xl relative z-10">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                    <i class="fa fa-users-gear mr-1"></i> Bandwidth & Capacity
                </span>
                <span class="text-xs text-slate-400 font-mono font-bold">{{ date('l, d F Y') }}</span>
                @if($overloadedMembers > 0)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-ping"></span>
                    {{ $overloadedMembers }} Members Overloaded
                </span>
                @endif
            </div>
            <h2 class="text-2xl sm:text-3xl font-black tracking-tight">Team Workload Capacity & Day Planner</h2>
            <p class="text-xs sm:text-sm text-slate-300 font-medium leading-relaxed">
                Live visualization of creative bandwidth and task distribution across designers, video editors, and specialists, paired with your interactive daily agenda organizer.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 relative z-10">
            <!-- Tab Switcher -->
            <div class="bg-slate-800/80 p-1 rounded-2xl border border-slate-700/80 flex items-center gap-1">
                <button @click="activeTab = 'capacity'"
                        :class="activeTab === 'capacity' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-400 hover:text-white'"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                    <i class="fa fa-chart-pie"></i> Team Capacity
                </button>
                <button @click="activeTab = 'planner'"
                        :class="activeTab === 'planner' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-400 hover:text-white'"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                    <i class="fa fa-calendar-check"></i> My Day Planner
                    @if($plannerPendingCount > 0)
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    @endif
                </button>
            </div>

            <!-- Add to Planner Button -->
            <button @click="showAddModal = true" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-900 font-black text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
                <i class="fa fa-plus"></i> Add Work Item
            </button>
        </div>
    </div>

    <!-- 4 Aggregate KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Active Tasks -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Active Tasks in Flight</span>
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-layer-group"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900">{{ number_format($totalActiveTasks) }}</div>
            <div class="text-[11px] font-semibold text-slate-500">Across {{ $teamWorkload->count() }} active team members</div>
        </div>

        <!-- 2. Capacity Available -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Capacity Available</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-user-check"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-600">{{ number_format($availableMembers) }}</div>
            <div class="text-[11px] font-semibold text-slate-500">Ready for new task assignments</div>
        </div>

        <!-- 3. At Capacity / Overloaded -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">At Capacity / Busy</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-user-clock"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-rose-600 flex items-center gap-2">
                {{ number_format($overloadedMembers) }}
                @if($overloadedMembers > 0)
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                @endif
            </div>
            <div class="text-[11px] font-semibold text-slate-500">5+ active tasks or overdue queue</div>
        </div>

        <!-- 4. Overdue Task Backlog -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Overdue Tasks</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-triangle-exclamation"></i>
                </div>
            </div>
            <div class="text-2xl font-black {{ $totalOverdue > 0 ? 'text-amber-600' : 'text-slate-900' }}">
                {{ number_format($totalOverdue) }}
            </div>
            <div class="text-[11px] font-semibold text-slate-500">Passed deadline milestone</div>
        </div>
    </div>

    <!-- TAB 1: TEAM CAPACITY MATRIX -->
    <div x-show="activeTab === 'capacity'" class="space-y-6">
        <!-- Role Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-1.5">
                <a href="{{ route('workload.index', array_merge(request()->except(['role']), ['role' => ''])) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ !request('role') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All Roles ({{ $teamWorkload->count() }})
                </a>
                @foreach($roles as $roleName)
                <a href="{{ route('workload.index', array_merge(request()->except(['role']), ['role' => $roleName])) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all uppercase tracking-wider {{ request('role') === $roleName ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $roleName }}
                </a>
                @endforeach
            </div>

            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Available (&lt;2 tasks)</span>
                <span class="flex items-center gap-1 ml-2"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Optimal (2-4 tasks)</span>
                <span class="flex items-center gap-1 ml-2"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> At Capacity (5+ tasks)</span>
            </div>
        </div>

        <!-- Team Workload Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($teamWorkload as $memberData)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow p-5 flex flex-col justify-between space-y-4">
                <!-- User Info & Bandwidth Status -->
                <div>
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-500 to-indigo-700 text-white font-black text-sm flex items-center justify-center shadow-sm">
                                    {{ strtoupper(substr($memberData->user->name, 0, 2)) }}
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full border-2 border-white {{ $memberData->status === 'available' ? 'bg-emerald-500' : ($memberData->status === 'balanced' ? 'bg-indigo-500' : 'bg-rose-500') }}"></span>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 text-sm leading-snug">{{ $memberData->user->name }}</h4>
                                <div class="flex items-center gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">
                                    <span>{{ $memberData->user->role }}</span>
                                    <span>•</span>
                                    <span>{{ $memberData->user->department ?? 'Creative Ops' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Bandwidth Status Pill -->
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider whitespace-nowrap {{ $memberData->status === 'available' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($memberData->status === 'balanced' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                            {{ $memberData->statusLabel }}
                        </span>
                    </div>

                    <!-- Load Bar Meter -->
                    <div class="mt-4 space-y-1.5">
                        <div class="flex justify-between text-[11px] font-bold">
                            <span class="text-slate-500">Current Bandwidth Load</span>
                            <span class="{{ $memberData->status === 'available' ? 'text-emerald-600' : ($memberData->status === 'balanced' ? 'text-indigo-600' : 'text-rose-600') }}">{{ $memberData->loadPercent }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full transition-all {{ $memberData->status === 'available' ? 'bg-emerald-500' : ($memberData->status === 'balanced' ? 'bg-indigo-500' : 'bg-rose-500') }}"
                                 style="width: {{ $memberData->loadPercent }}%"></div>
                        </div>
                    </div>

                    <!-- Mini KPI Stats Strip -->
                    <div class="grid grid-cols-3 gap-2 mt-4 pt-3 border-t border-slate-100 text-center">
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="text-base font-black text-slate-900">{{ $memberData->activeCount }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Active</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="text-base font-black {{ $memberData->overdueCount > 0 ? 'text-rose-600' : 'text-slate-900' }}">{{ $memberData->overdueCount }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Overdue</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="text-base font-black text-emerald-600">{{ $memberData->completedCount }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Done (Mo)</div>
                        </div>
                    </div>

                    <!-- In-Flight Tasks List -->
                    <div class="mt-4 space-y-1.5">
                        <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Assigned Deliverables</div>
                        @if($memberData->activeTasks->count() > 0)
                        <div class="space-y-1.5 max-h-36 overflow-y-auto pr-1">
                            @foreach($memberData->activeTasks->take(3) as $task)
                            <div class="p-2 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-100 text-xs flex items-center justify-between gap-2 transition-colors">
                                <div class="truncate">
                                    <div class="font-bold text-slate-800 truncate" title="{{ $task->title }}">{{ $task->title }}</div>
                                    @if($task->client)
                                    <div class="text-[10px] text-slate-400">{{ $task->client->name }}</div>
                                    @endif
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    @if($task->deadline)
                                    @php
                                        $d = \Carbon\Carbon::parse($task->deadline);
                                        $isPast = $d->isPast() && !$d->isToday();
                                    @endphp
                                    <span class="text-[10px] font-bold {{ $isPast ? 'text-rose-600' : 'text-slate-400' }}">
                                        {{ $d->format('d M') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            @if($memberData->activeTasks->count() > 3)
                            <div class="text-[10px] text-center font-bold text-slate-400 italic">
                                + {{ $memberData->activeTasks->count() - 3 }} more deliverables
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100/60 text-center text-xs text-emerald-700 font-semibold">
                            <i class="fa fa-sparkles mr-1"></i> No active backlog. Ready for new briefs!
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Action -->
                <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('tasks.index', ['assigned_to' => $memberData->user->id]) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                        View Tasks <i class="fa fa-arrow-right text-[10px]"></i>
                    </a>
                    <a href="{{ route('tasks.index') }}" class="px-2.5 py-1 text-[11px] font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors">
                        + Assign Task
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center text-slate-400 font-bold">
                No team members match this filter.
            </div>
            @endforelse
        </div>
    </div>

    <!-- TAB 2: MY DAY PLANNER ("MY WORK ITEMS") -->
    <div x-show="activeTab === 'planner'" class="space-y-6">
        <!-- Day Planner Header & Progress -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                        <i class="fa fa-calendar-day text-indigo-600"></i> Today's Action Planner
                    </h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                        Prioritize your daily creative deliverables, quick wins, and internal meetings.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <div class="text-xs font-black text-slate-900">{{ $plannerDoneCount }} of {{ $plannerTotalCount }} Completed</div>
                        <div class="text-[10px] text-slate-400 font-bold">{{ $plannerCompletionPct }}% execution rate</div>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs border border-indigo-100">
                        {{ $plannerCompletionPct }}%
                    </div>
                </div>
            </div>

            <!-- Progress Gauge -->
            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-emerald-500 h-2.5 rounded-full transition-all"
                     style="width: {{ $plannerCompletionPct }}%"></div>
            </div>
        </div>

        <!-- 3 Kanban Planner Columns: Pending, In Progress, Done -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- 1. PENDING COLUMN -->
            <div class="bg-slate-50/75 rounded-2xl p-4 border border-slate-200 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <h4 class="font-black text-slate-900 text-xs uppercase tracking-wider">To Do Today</h4>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800">
                        {{ $myPlannerItems->where('status', 'pending')->count() }}
                    </span>
                </div>

                <div class="space-y-2.5">
                    @forelse($myPlannerItems->where('status', 'pending') as $item)
                    <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-sm space-y-2.5 hover:border-indigo-300 transition-all">
                        <div class="flex items-start justify-between gap-2">
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wider {{ $item->priority === 'high' ? 'bg-rose-100 text-rose-700' : ($item->priority === 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                {{ $item->priority }} priority
                            </span>
                            <span class="text-[10px] font-bold text-slate-400">{{ $item->category }}</span>
                        </div>

                        <div class="font-bold text-slate-900 text-xs leading-snug">{{ $item->title }}</div>

                        @if($item->notes)
                        <div class="text-[11px] text-slate-500 leading-relaxed">{{ $item->notes }}</div>
                        @endif

                        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                            <form action="{{ route('workload.items.update-status', $item->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="px-2 py-1 text-[10px] font-bold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors flex items-center gap-1">
                                    <i class="fa fa-play text-[8px]"></i> Start Work
                                </button>
                            </form>

                            <form action="{{ route('workload.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this to-do item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-rose-500 p-1 text-xs">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-slate-400 text-xs font-semibold">
                        No pending items. Click "+ Add Work Item" to plan your day.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- 2. IN PROGRESS COLUMN -->
            <div class="bg-indigo-50/40 rounded-2xl p-4 border border-indigo-100 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-indigo-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-pulse"></span>
                        <h4 class="font-black text-indigo-950 text-xs uppercase tracking-wider">In Progress</h4>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-100 text-indigo-800">
                        {{ $myPlannerItems->where('status', 'in_progress')->count() }}
                    </span>
                </div>

                <div class="space-y-2.5">
                    @forelse($myPlannerItems->where('status', 'in_progress') as $item)
                    <div class="bg-white p-3.5 rounded-xl border border-indigo-200 shadow-sm space-y-2.5">
                        <div class="flex items-start justify-between gap-2">
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wider {{ $item->priority === 'high' ? 'bg-rose-100 text-rose-700' : ($item->priority === 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                {{ $item->priority }} priority
                            </span>
                            <span class="text-[10px] font-bold text-slate-400">{{ $item->category }}</span>
                        </div>

                        <div class="font-bold text-slate-900 text-xs leading-snug">{{ $item->title }}</div>

                        @if($item->notes)
                        <div class="text-[11px] text-slate-500 leading-relaxed">{{ $item->notes }}</div>
                        @endif

                        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                            <form action="{{ route('workload.items.update-status', $item->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="done">
                                <button type="submit" class="px-2.5 py-1 text-[10px] font-bold bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors flex items-center gap-1 shadow-sm">
                                    <i class="fa fa-check text-[9px]"></i> Complete
                                </button>
                            </form>

                            <form action="{{ route('workload.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this to-do item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-rose-500 p-1 text-xs">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-slate-400 text-xs font-semibold">
                        No tasks in progress.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- 3. COMPLETED COLUMN -->
            <div class="bg-emerald-50/40 rounded-2xl p-4 border border-emerald-100 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-emerald-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                        <h4 class="font-black text-emerald-950 text-xs uppercase tracking-wider">Completed Today</h4>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                        {{ $myPlannerItems->where('status', 'done')->count() }}
                    </span>
                </div>

                <div class="space-y-2.5">
                    @forelse($myPlannerItems->where('status', 'done') as $item)
                    <div class="bg-white p-3.5 rounded-xl border border-emerald-200/80 shadow-sm space-y-2.5 opacity-90">
                        <div class="flex items-start justify-between gap-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-bold bg-emerald-100 text-emerald-800">
                                <i class="fa fa-check text-[8px]"></i> Done
                            </span>
                            <span class="text-[10px] font-bold text-slate-400">{{ $item->category }}</span>
                        </div>

                        <div class="font-bold text-slate-700 text-xs line-through leading-snug">{{ $item->title }}</div>

                        @if($item->completed_at)
                        <div class="text-[10px] text-emerald-600 font-semibold">
                            Completed at {{ $item->completed_at->format('h:i A') }}
                        </div>
                        @endif

                        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                            <form action="{{ route('workload.items.update-status', $item->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" class="px-2 py-1 text-[10px] font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors flex items-center gap-1">
                                    <i class="fa fa-undo text-[8px]"></i> Reopen
                                </button>
                            </form>

                            <form action="{{ route('workload.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this to-do item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-rose-500 p-1 text-xs">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-slate-400 text-xs font-semibold">
                        No deliverables completed yet today.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: Add Personal Work Item -->
    <div x-show="showAddModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto"
         @keydown.escape.window="showAddModal = false">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative my-8"
             @click.away="showAddModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa fa-plus-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Add to Day Planner</h3>
                        <p class="text-xs text-slate-500 font-medium">Schedule a task or quick deliverable</p>
                    </div>
                </div>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <form action="{{ route('workload.items.store') }}" method="POST" class="space-y-4 pt-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Action Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="e.g. Export 4K reels for Chaldal Ramadan promo" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Category</label>
                        <select name="category" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                            <option value="Creative">Creative / Design</option>
                            <option value="Video">Video / Motion</option>
                            <option value="Review">QA & Review</option>
                            <option value="Ad Tech">Ad Tech / Media</option>
                            <option value="Client Meeting">Client Meeting</option>
                            <option value="Sales Ops">Sales Ops</option>
                            <option value="Operations">Operations / Standup</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Priority <span class="text-rose-500">*</span></label>
                        <select name="priority" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                            <option value="high">High Priority</option>
                            <option value="medium" selected>Medium Priority</option>
                            <option value="low">Low Priority</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Target Date</label>
                    <input type="date" name="due_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deliverable Notes / URL</label>
                    <textarea name="notes" rows="2" placeholder="Figma frame, assets folder, or action brief..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all flex items-center gap-2">
                        <i class="fa fa-plus"></i> Save to Planner
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
