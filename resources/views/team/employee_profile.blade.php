@extends('layouts.app')

@section('title', $user->name . ' — Employee Info & KPI 360')
@section('header_title', 'Employee Profile & KPI Performance')

@section('content')
<div class="space-y-6" x-data="{
    activeTab: 'tasks',
    editModal: false,
    attendanceModal: false,
    salaryModal: false
}">

    <!-- Top Breadcrumb & Return Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('team.index') }}" class="hover:text-amber-600 transition-colors">Team</a>
                <span>/</span>
                <a href="{{ route('team.kpi') }}" class="hover:text-amber-600 transition-colors">KPI Hub</a>
                <span>/</span>
                <span class="text-slate-800">{{ $user->name }}</span>
            </div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <span>{{ $user->name }}</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black border {{ $kpi['kpi_badge'] }}">
                    Grade {{ $kpi['kpi_grade'] }} · {{ $kpi['kpi_score'] }}%
                </span>
            </h2>
            <p class="text-xs text-slate-500 font-medium">Employee 360 Performance Dossier · Month of {{ date('F Y', strtotime($month . '-01')) }}</p>
        </div>

        <div class="flex items-center gap-2">
            <button @click="editModal = true" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                <i class="fa fa-user-pen text-amber-500"></i> Edit Profile Info
            </button>
            <a href="{{ route('team.kpi') }}?month={{ $month }}" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                <i class="fa fa-arrow-left"></i> Back to KPI Hub
            </a>
        </div>
    </div>

    <!-- Employee Primary Dossier Card & Key KPI Gauges -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

        <!-- LEFT: Employee Info Box (5 cols) -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center font-black text-white text-xl shadow-md flex-shrink-0" style="background-color: {{ $user->color ?? '#64748b' }}">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900">{{ $user->name }}</h3>
                        <p class="text-xs font-bold text-slate-500">{{ $user->designation ?: ucfirst($user->role) . ' Specialist' }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                {{ ucfirst($user->role) }}
                            </span>
                            @if($user->department)
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                {{ $user->department }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-3 mt-6 pt-6 border-t border-slate-100 text-xs">
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Username</span>
                        <span class="font-mono font-bold text-slate-800">@ {{ $user->username }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Work Email</span>
                        <span class="font-medium text-slate-800">{{ $user->email ?: 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Direct Phone</span>
                        <span class="font-bold text-slate-800 font-mono">{{ $user->phone ?: '+880 1700-000000' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Joining Date</span>
                        <span class="font-bold text-slate-800">{{ $user->joining_date ? $user->joining_date->format('d M Y') : '1 Jan 2026' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Base Salary</span>
                        <span class="font-bold text-slate-900 font-mono">৳{{ number_format($kpi['base_salary']) }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Emergency Contact</span>
                        <span class="font-medium text-slate-800">{{ $user->emergency_contact ?: 'HR Record' }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-500">
                <span>Account Status: <span class="text-emerald-600 font-bold">Active & Enrolled</span></span>
                <button @click="editModal = true" class="text-amber-600 hover:text-amber-700 font-bold flex items-center gap-1">
                    <i class="fa fa-pen-to-square"></i> Modify Details
                </button>
            </div>
        </div>

        <!-- RIGHT: KPI Scorecard & Gauges (7 cols) -->
        <div class="lg:col-span-7 grid grid-cols-2 sm:grid-cols-3 gap-4">
            <!-- Card 1: Score & Grade -->
            <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Overall KPI Index</span>
                    <div class="text-3xl font-black text-slate-900 mt-2">{{ $kpi['kpi_score'] }}%</div>
                </div>
                <div class="mt-4">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-black border {{ $kpi['kpi_badge'] }}">
                        {{ $kpi['kpi_grade'] }} · {{ $kpi['kpi_label'] }}
                    </span>
                </div>
            </div>

            <!-- Card 2: Tasks Completion Rate -->
            <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider">Task Completion</span>
                    <div class="text-3xl font-black text-emerald-600 mt-2">{{ $kpi['completion_rate'] }}%</div>
                </div>
                <div class="mt-4 text-xs font-bold text-slate-500">
                    {{ $kpi['tasks_completed'] }} done / {{ $kpi['tasks_total'] }} assigned
                    @if($kpi['tasks_overdue'] > 0)
                    <div class="text-rose-600 font-extrabold text-[11px] mt-0.5">⚠️ {{ $kpi['tasks_overdue'] }} Overdue</div>
                    @endif
                </div>
            </div>

            <!-- Card 3: Quality Rating -->
            <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider">Review Quality</span>
                    <div class="text-3xl font-black text-amber-600 mt-2">★ {{ $kpi['avg_rating'] }}</div>
                </div>
                <div class="mt-4 text-xs font-bold text-slate-500">
                    From {{ $kpi['worklogs_count'] }} QA worklogs
                </div>
            </div>

            <!-- Card 4: Monthly Attendance -->
            <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-wider">Attendance ({{ date('M', strtotime($month . '-01')) }})</span>
                    <div class="text-2xl font-black text-indigo-700 mt-2">{{ $kpi['present_days'] }} Present</div>
                </div>
                <div class="mt-4 text-[11px] font-bold text-slate-500 flex items-center gap-2">
                    <span class="text-rose-600">{{ $kpi['absent_days'] }} Absent</span>
                    <span>·</span>
                    <span class="text-amber-600">{{ $kpi['late_days'] }} Late</span>
                </div>
            </div>

            <!-- Card 5: Workload Capacity -->
            <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Active Workload</span>
                    <div class="text-2xl font-black text-slate-900 mt-2">{{ $kpi['workload_hours'] }}h</div>
                </div>
                <div class="mt-4 text-xs font-bold text-slate-500">
                    {{ $kpi['tasks_pending'] }} pending deliverables
                </div>
            </div>

            <!-- Card 6: Month Net Compensation -->
            <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Payroll Compensation</span>
                    <div class="text-2xl font-black text-slate-900 mt-2 font-mono">৳{{ number_format($kpi['net_salary']) }}</div>
                </div>
                <div class="mt-4 text-xs font-bold text-slate-500">
                    @if($kpi['bonus'] > 0)
                    <span class="text-amber-600 font-extrabold">+ ৳{{ number_format($kpi['bonus']) }} Bonus</span>
                    @else
                    <span>Regular Base</span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Navigation Tabs for Detailed Logs -->
    <div class="flex items-center gap-2 bg-slate-200/80 p-1 rounded-2xl border border-slate-300/60 shadow-inner max-w-2xl">
        <button @click="activeTab = 'tasks'"
                :class="activeTab === 'tasks' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="flex-1 py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all">
            <i class="fa fa-list-check text-emerald-500"></i>
            <span>Assigned Tasks ({{ count($recentTasks) }})</span>
        </button>
        <button @click="activeTab = 'worklogs'"
                :class="activeTab === 'worklogs' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="flex-1 py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all">
            <i class="fa fa-clipboard-check text-amber-500"></i>
            <span>QA Worklogs ({{ count($recentWorklogs) }})</span>
        </button>
        @if(in_array($user->role, ['smm', 'designer', 'motion', 'video']))
        <button @click="activeTab = 'content'"
                :class="activeTab === 'content' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="flex-1 py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all">
            <i class="fa fa-calendar-days text-indigo-500"></i>
            <span>Content Posts ({{ count($recentPosts) }})</span>
        </button>
        @endif
        <button @click="activeTab = 'attendance'"
                :class="activeTab === 'attendance' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="flex-1 py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all">
            <i class="fa fa-calendar-check text-rose-500"></i>
            <span>Attendance Log ({{ count($attendances) }})</span>
        </button>
        <button @click="activeTab = 'salary'"
                :class="activeTab === 'salary' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="flex-1 py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all">
            <i class="fa fa-money-bill-1-wave text-amber-500"></i>
            <span>Payroll History</span>
        </button>
    </div>

    <!-- TAB 1: ASSIGNED TASKS -->
    <div x-show="activeTab === 'tasks'" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-extrabold text-sm text-slate-900">Assigned Deliverables & Task Board</h4>
            <span class="text-xs text-slate-400 font-medium">Tracking deadline punctuality & checklists</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-extrabold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Task Title</th>
                        <th class="py-3 px-4">Client</th>
                        <th class="py-3 px-4 text-center">Priority</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Deadline</th>
                        <th class="py-3 px-4 text-center">Checklist</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recentTasks as $task)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3 px-4 font-bold text-slate-800">{{ $task->title }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ $task->client ? $task->client->name : 'Internal Agency' }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-0.5 rounded font-extrabold text-[10px] uppercase {{ $task->priority === 'high' ? 'bg-rose-100 text-rose-700' : ($task->priority === 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                {{ $task->priority }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-0.5 rounded font-bold text-[10px] {{ $task->status === 'done' ? 'bg-emerald-100 text-emerald-700' : ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600') }}">
                                {{ str_replace('_', ' ', ucfirst($task->status)) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if($task->deadline)
                            <span class="{{ $task->deadline < now() && $task->status !== 'done' ? 'text-rose-600 font-black' : 'text-slate-600' }}">
                                {{ $task->deadline->format('d M Y') }}
                                @if($task->deadline < now() && $task->status !== 'done')
                                <span class="text-[9px] bg-rose-100 text-rose-700 px-1 rounded font-bold">OVERDUE</span>
                                @endif
                            </span>
                            @else
                            <span class="text-slate-400">No deadline</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if(is_array($task->checklist) && count($task->checklist) > 0)
                            @php
                                $doneItems = collect($task->checklist)->where('done', true)->count();
                                $totalItems = count($task->checklist);
                            @endphp
                            <span class="font-bold text-slate-700">{{ $doneItems }}/{{ $totalItems }}</span>
                            @else
                            <span class="text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-slate-400">No assigned tasks found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 2: WORKLOGS -->
    <div x-show="activeTab === 'worklogs'" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-extrabold text-sm text-slate-900">Daily Worklogs & Supervisor Quality Ratings</h4>
            <span class="text-xs text-slate-400 font-medium">1 to 5 star QA ratings and SMM review feedback</span>
        </div>
        <div class="divide-y divide-slate-100 text-xs">
            @forelse($recentWorklogs as $log)
            <div class="p-4 hover:bg-slate-50 transition-colors flex items-start justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-900">{{ $log->title }}</span>
                        <span class="text-[10px] text-slate-400 font-mono">{{ $log->date->format('d M Y') }}</span>
                    </div>
                    @if($log->description)
                    <p class="text-slate-600 text-[11px] leading-relaxed">{{ $log->description }}</p>
                    @endif
                    @if($log->smm_feedback)
                    <div class="p-2 rounded-xl bg-amber-50 border border-amber-200/60 text-amber-800 text-[11px] font-semibold mt-1">
                        <i class="fa fa-comment-dots text-amber-500 mr-1"></i> {{ $log->smm_feedback }}
                    </div>
                    @endif
                </div>

                <div class="text-right flex-shrink-0">
                    @if($log->quality_rating)
                    <div class="text-amber-500 font-black text-sm">
                        @for($i=1; $i<=$log->quality_rating; $i++) ★ @endfor
                    </div>
                    <span class="text-[10px] font-bold text-slate-400">{{ $log->quality_rating }} / 5.0 Rating</span>
                    @else
                    <span class="text-[10px] font-bold text-slate-400">Unrated</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-slate-400">No worklogs submitted yet.</div>
            @endforelse
        </div>
    </div>

    <!-- TAB 3: CONTENT POSTS -->
    <div x-show="activeTab === 'content'" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-extrabold text-sm text-slate-900">Content Calendar Posts (6-Stage Workflow)</h4>
            <span class="text-xs text-slate-400 font-medium">Design / Reel / Video / Copy deliverables</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-extrabold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Post Title</th>
                        <th class="py-3 px-4">Client</th>
                        <th class="py-3 px-4 text-center">Format</th>
                        <th class="py-3 px-4 text-center">Platform</th>
                        <th class="py-3 px-4 text-center">Workflow Stage</th>
                        <th class="py-3 px-4">Publish Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recentPosts as $post)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3 px-4 font-bold text-slate-800">{{ $post->title }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ $post->client ? $post->client->name : 'N/A' }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-0.5 rounded font-extrabold text-[10px] uppercase bg-slate-100 text-slate-700">
                                {{ $post->post_type }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center font-bold text-slate-700">{{ ucfirst($post->platform) }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] {{ $post->status === 'published' ? 'bg-teal-100 text-teal-800' : ($post->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ str_replace('_', ' ', ucfirst($post->status)) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-600 font-mono">{{ $post->date->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-slate-400">No content calendar posts assigned.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 4: ATTENDANCE LOG -->
    <div x-show="activeTab === 'attendance'" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-extrabold text-sm text-slate-900">Attendance Log for {{ date('F Y', strtotime($month . '-01')) }}</h4>
            <button @click="attendanceModal = true" class="px-3 py-1.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-colors">
                + Log Entry
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-extrabold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Check-In</th>
                        <th class="py-3 px-4 text-center">Check-Out</th>
                        <th class="py-3 px-4">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($attendances as $att)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3 px-4 font-bold text-slate-800">{{ $att->date->format('l, d M Y') }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-0.5 rounded font-extrabold text-[10px] uppercase {{ $att->status === 'present' ? 'bg-emerald-100 text-emerald-800' : ($att->status === 'absent' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ $att->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center font-mono">{{ $att->check_in ?: '09:30 AM' }}</td>
                        <td class="py-3 px-4 text-center font-mono">{{ $att->check_out ?: '06:30 PM' }}</td>
                        <td class="py-3 px-4 text-slate-500">{{ $att->notes ?: '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-400">No attendance entries recorded for this month.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 5: SALARY & BONUS HISTORY -->
    <div x-show="activeTab === 'salary'" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-extrabold text-sm text-slate-900">Salary & Performance Bonus History</h4>
            <button @click="salaryModal = true" class="px-3 py-1.5 bg-amber-500 text-white rounded-xl text-xs font-bold hover:bg-amber-600 transition-colors shadow-sm">
                + Record Monthly Payroll
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-extrabold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Payroll Month</th>
                        <th class="py-3 px-4 text-right">Base Salary</th>
                        <th class="py-3 px-4 text-right">Bonus</th>
                        <th class="py-3 px-4 text-right">Deductions</th>
                        <th class="py-3 px-4 text-right">Net Compensation</th>
                        <th class="py-3 px-4 text-center">Payment Status</th>
                        <th class="py-3 px-4">Payment Method & Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($salaryHistory as $sal)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3 px-4 font-bold text-slate-800">{{ date('F Y', strtotime($sal->month . '-01')) }}</td>
                        <td class="py-3 px-4 text-right font-mono">৳{{ number_format($sal->base_salary) }}</td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-amber-600">+৳{{ number_format($sal->bonus) }}</td>
                        <td class="py-3 px-4 text-right font-mono text-rose-600">-৳{{ number_format($sal->deductions) }}</td>
                        <td class="py-3 px-4 text-right font-mono font-black text-slate-900">৳{{ number_format($sal->net_salary) }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-0.5 rounded font-extrabold text-[10px] {{ $sal->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($sal->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            {{ $sal->payment_method ?: 'Bank Transfer' }}
                            @if($sal->paid_at) · {{ $sal->paid_at->format('d M Y') }} @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-slate-400">No payroll history recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL: EDIT EMPLOYEE PROFILE -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="editModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fa fa-user-pen text-amber-500"></i>
                    <span>Edit Employee Profile & Compensation</span>
                </h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600 text-sm">
                    <i class="fa fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('team.employee-info.update', $user->id) }}" method="POST" class="space-y-4 text-xs font-semibold">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-600 mb-1">Official Designation</label>
                        <input type="text" name="designation" value="{{ $user->designation }}" placeholder="e.g. Senior Graphic Designer" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-bold">
                    </div>
                    <div>
                        <label class="block text-slate-600 mb-1">Department</label>
                        <input type="text" name="department" value="{{ $user->department }}" placeholder="e.g. Creative Production" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-600 mb-1">Direct Phone</label>
                        <input type="text" name="phone" value="{{ $user->phone }}" placeholder="+880 1711-000000" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-bold">
                    </div>
                    <div>
                        <label class="block text-slate-600 mb-1">Joining Date</label>
                        <input type="date" name="joining_date" value="{{ $user->joining_date ? $user->joining_date->toDateString() : date('Y-m-d') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-600 mb-1">Base Monthly Salary (BDT)</label>
                        <input type="number" step="0.01" name="base_salary" value="{{ $user->base_salary }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-black text-slate-900">
                    </div>
                    <div>
                        <label class="block text-slate-600 mb-1">Emergency Contact</label>
                        <input type="text" name="emergency_contact" value="{{ $user->emergency_contact }}" placeholder="Name & Phone" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-medium">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="editModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition-colors">
                        Save Profile Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
