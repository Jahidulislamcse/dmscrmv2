@extends('layouts.app')

@section('title', 'Employee Info & KPI Productivity Hub')
@section('header_title', 'Employee Info & KPI Performance Management')

@section('content')
<div class="space-y-6" x-data="{
    attendanceModal: false,
    salaryModal: false,
    selectedUserId: '',
    selectedUserName: '',
    selectedBaseSalary: 0
}">

    <!-- Top Breadcrumb & Page Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('team.index') }}" class="hover:text-amber-600 transition-colors">Team</a>
                <span>/</span>
                <span class="text-slate-800">Employee KPI & Productivity</span>
            </div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <span>Employee Info & KPI Hub</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-amber-500/10 text-amber-600 border border-amber-500/30">
                    REQ-EMP-01
                </span>
            </h2>
            <p class="text-xs text-slate-500 font-medium">Track staff absences, salary & bonus allocations, SMM/creative deliverables completed vs overdue, and role productivity ratings.</p>
        </div>

        <div class="flex items-center gap-2">
            <button @click="attendanceModal = true" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                <i class="fa fa-calendar-check text-emerald-400"></i>
                <span>Log Attendance</span>
            </button>
            <button @click="salaryModal = true" class="px-3.5 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-amber-500/20 flex items-center gap-2">
                <i class="fa fa-money-bill-wave"></i>
                <span>Record Salary & Bonus</span>
            </button>
            <a href="{{ route('team.index') }}" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition-all">
                <i class="fa fa-users text-slate-400 mr-1.5"></i> Team Directory
            </a>
        </div>
    </div>

    <!-- Agency Aggregate KPI Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Active Employees</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ count($teamKpis) }}</div>
            <span class="text-[10px] text-slate-500 font-medium mt-0.5 block">Staff on payroll</span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider block">Tasks Completed</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $totalTasksCompleted }}</div>
            <span class="text-[10px] text-slate-500 font-medium mt-0.5 block">{{ $avgCompletionRate }}% Completion Rate</span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold text-rose-600 uppercase tracking-wider block">Overdue Tasks</span>
            <div class="text-2xl font-black {{ $totalTasksOverdue > 0 ? 'text-rose-600' : 'text-slate-900' }} mt-1">{{ $totalTasksOverdue }}</div>
            <span class="text-[10px] {{ $totalTasksOverdue > 0 ? 'text-rose-500 font-bold' : 'text-slate-500 font-medium' }} mt-0.5 block">Requires attention</span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-wider block">Month Attendance</span>
            <div class="text-2xl font-black text-indigo-700 mt-1">{{ $totalPresent }} <span class="text-xs font-semibold text-slate-400">/ {{ $totalPresent + $totalAbsent }}d</span></div>
            <span class="text-[10px] text-slate-500 font-medium mt-0.5 block">{{ $totalAbsent }} recorded absences</span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider block">Total Bonuses</span>
            <div class="text-2xl font-black text-amber-600 mt-1">৳{{ number_format($totalBonus) }}</div>
            <span class="text-[10px] text-slate-500 font-medium mt-0.5 block">KPI performance reward</span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider block">Total Payroll</span>
            <div class="text-2xl font-black text-slate-900 mt-1">৳{{ number_format($totalSalary) }}</div>
            <span class="text-[10px] text-slate-500 font-medium mt-0.5 block">{{ date('F Y', strtotime($month . '-01')) }}</span>
        </div>
    </div>

    <!-- Filters & Month Selection Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('team.kpi') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Month:</label>
                <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()"
                       class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div class="w-44">
                <select name="role" onchange="this.form.submit()" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                    <option value="">All Roles</option>
                    @foreach($roles as $key => $label)
                    <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            @if(request()->anyFilled(['role']) || $month !== now()->format('Y-m'))
            <a href="{{ route('team.kpi') }}" class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200 transition-all">
                Reset Filters
            </a>
            @endif
        </form>

        <div class="text-xs font-bold text-slate-400">
            Evaluating performance for <span class="text-slate-800">{{ date('F Y', strtotime($month . '-01')) }}</span>
        </div>
    </div>

    <!-- Main Employee KPI Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-extrabold uppercase tracking-wider text-[10px]">
                        <th class="py-3.5 px-4">Employee & Designation</th>
                        <th class="py-3.5 px-4">Role Tier</th>
                        <th class="py-3.5 px-4 text-center">Tasks (Done / Overdue)</th>
                        <th class="py-3.5 px-4 text-center">Workload & Output</th>
                        <th class="py-3.5 px-4 text-center">Attendance ({{ date('M', strtotime($month . '-01')) }})</th>
                        <th class="py-3.5 px-4 text-right">Base Salary & Bonus</th>
                        <th class="py-3.5 px-4 text-center">KPI Score & Grade</th>
                        <th class="py-3.5 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($teamKpis as $item)
                    @php
                        $user = $item['user'];
                        $kpi = $item['summary'];
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <!-- Employee Info -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-extrabold text-white text-xs shadow-sm flex-shrink-0" style="background-color: {{ $user->color ?? '#64748b' }}">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('team.kpi.show', $user->id) }}?month={{ $month }}" class="font-bold text-slate-900 hover:text-amber-600 transition-colors block">
                                        {{ $user->name }}
                                    </a>
                                    <div class="text-[11px] text-slate-500 font-medium">
                                        {{ $user->designation ?: 'Staff Member' }}
                                        @if($user->department)
                                        <span class="text-slate-300">·</span> {{ $user->department }}
                                        @endif
                                    </div>
                                    @if($user->phone)
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $user->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Role Tier -->
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 uppercase tracking-wider">
                                {{ $roles[$user->role] ?? ucfirst($user->role) }}
                            </span>
                        </td>

                        <!-- Task Performance -->
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5 font-bold">
                                <span class="text-emerald-600">{{ $kpi['tasks_completed'] }} Done</span>
                                <span class="text-slate-300">/</span>
                                <span class="text-slate-600">{{ $kpi['tasks_total'] }} Total</span>
                            </div>
                            <div class="w-24 bg-slate-100 rounded-full h-1.5 mx-auto mt-1.5 overflow-hidden">
                                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $kpi['completion_rate'] }}%"></div>
                            </div>
                            <div class="flex items-center justify-center gap-2 mt-1 text-[10px]">
                                <span class="font-bold text-emerald-600">{{ $kpi['completion_rate'] }}%</span>
                                @if($kpi['tasks_overdue'] > 0)
                                <span class="px-1.5 py-0.2 rounded font-extrabold bg-rose-100 text-rose-700">
                                    {{ $kpi['tasks_overdue'] }} Overdue
                                </span>
                                @endif
                            </div>
                        </td>

                        <!-- Workload & Output -->
                        <td class="py-3.5 px-4 text-center">
                            <div class="font-bold text-slate-800">{{ $kpi['workload_hours'] }}h <span class="text-slate-400 font-normal">workload</span></div>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                @if($kpi['worklogs_count'] > 0)
                                <span class="text-amber-600 font-bold">★ {{ $kpi['avg_rating'] }}</span>
                                <span class="text-slate-400">({{ $kpi['worklogs_count'] }} logs)</span>
                                @else
                                <span class="text-slate-400">No worklogs</span>
                                @endif
                            </div>
                            @if($kpi['posts_total'] > 0)
                            <div class="text-[10px] text-indigo-600 font-bold mt-0.5">
                                🎨 {{ $kpi['posts_completed'] }}/{{ $kpi['posts_total'] }} posts ready
                            </div>
                            @endif
                        </td>

                        <!-- Attendance -->
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5 text-xs font-bold">
                                <span class="text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200" title="Present Days">
                                    P: {{ $kpi['present_days'] }}
                                </span>
                                <span class="text-rose-700 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200" title="Absent Days">
                                    A: {{ $kpi['absent_days'] }}
                                </span>
                                @if($kpi['late_days'] > 0)
                                <span class="text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200" title="Late Days">
                                    L: {{ $kpi['late_days'] }}
                                </span>
                                @endif
                            </div>
                            <span class="text-[10px] text-slate-400 block mt-1">
                                {{ $kpi['tracked_days'] }} days logged
                            </span>
                        </td>

                        <!-- Salary & Bonus -->
                        <td class="py-3.5 px-4 text-right">
                            <div class="font-extrabold text-slate-900 font-mono">
                                ৳{{ number_format($kpi['net_salary']) }}
                            </div>
                            <div class="text-[10px] text-slate-500">
                                Base: ৳{{ number_format($kpi['base_salary']) }}
                            </div>
                            @if($kpi['bonus'] > 0)
                            <div class="text-[10px] font-bold text-amber-600">
                                + ৳{{ number_format($kpi['bonus']) }} bonus
                            </div>
                            @endif
                            <span class="inline-block mt-0.5 px-1.5 py-0.2 rounded text-[9px] font-bold {{ $kpi['salary_status'] === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($kpi['salary_status']) }}
                            </span>
                        </td>

                        <!-- KPI Score & Grade -->
                        <td class="py-3.5 px-4 text-center">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-black border {{ $kpi['kpi_badge'] }}">
                                <span>{{ $kpi['kpi_grade'] }}</span>
                                <span class="font-bold text-[10px]">({{ $kpi['kpi_score'] }}%)</span>
                            </div>
                            <div class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wider">
                                {{ $kpi['kpi_label'] }}
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('team.kpi.show', $user->id) }}?month={{ $month }}"
                                   class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-lg text-xs font-bold transition-all flex items-center gap-1"
                                   title="View Employee 360 Profile">
                                    <i class="fa fa-chart-pie"></i> Profile
                                </a>
                                <button type="button"
                                        @click="attendanceModal = true; selectedUserId = '{{ $user->id }}'; selectedUserName = '{{ addslashes($user->name) }}'"
                                        class="p-1.5 hover:bg-slate-100 text-slate-600 rounded-lg transition-colors"
                                        title="Log Attendance">
                                    <i class="fa fa-calendar-check text-xs"></i>
                                </button>
                                <button type="button"
                                        @click="salaryModal = true; selectedUserId = '{{ $user->id }}'; selectedUserName = '{{ addslashes($user->name) }}'; selectedBaseSalary = '{{ $kpi['base_salary'] }}'"
                                        class="p-1.5 hover:bg-slate-100 text-slate-600 rounded-lg transition-colors"
                                        title="Record Salary / Bonus">
                                    <i class="fa fa-dollar-sign text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400">
                            No team members found for this filter criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL 1: LOG ATTENDANCE -->
    <div x-show="attendanceModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="attendanceModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fa fa-calendar-check text-emerald-500"></i>
                    <span>Log Daily Attendance</span>
                </h3>
                <button @click="attendanceModal = false" class="text-slate-400 hover:text-slate-600 text-sm">
                    <i class="fa fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('team.attendance.store') }}" method="POST" class="space-y-4 text-xs font-semibold">
                @csrf
                <div>
                    <label class="block text-slate-600 mb-1">Select Employee</label>
                    <select name="user_id" x-model="selectedUserId" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-bold">
                        <option value="">-- Choose Member --</option>
                        @foreach($teamKpis as $item)
                        <option value="{{ $item['user']->id }}">{{ $item['user']->name }} ({{ $item['user']->role }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-600 mb-1">Date</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-bold">
                    </div>
                    <div>
                        <label class="block text-slate-600 mb-1">Status</label>
                        <select name="status" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-bold">
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late Arrival</option>
                            <option value="half_day">Half Day</option>
                            <option value="leave">Approved Leave</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-600 mb-1">Check In Time (Optional)</label>
                        <input type="time" name="check_in" value="09:30" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-medium">
                    </div>
                    <div>
                        <label class="block text-slate-600 mb-1">Check Out Time</label>
                        <input type="time" name="check_out" value="18:30" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-slate-600 mb-1">Notes / Reason (Optional)</label>
                    <input type="text" name="notes" placeholder="e.g. Traffic delay or doctor appointment" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-medium">
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="attendanceModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition-colors">
                        Save Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: RECORD SALARY & BONUS -->
    <div x-show="salaryModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="salaryModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fa fa-money-bill-wave text-amber-500"></i>
                    <span>Record Salary & Bonus Allocation</span>
                </h3>
                <button @click="salaryModal = false" class="text-slate-400 hover:text-slate-600 text-sm">
                    <i class="fa fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('team.salary.store') }}" method="POST" class="space-y-4 text-xs font-semibold">
                @csrf
                <div>
                    <label class="block text-slate-600 mb-1">Select Employee</label>
                    <select name="user_id" x-model="selectedUserId" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-bold">
                        <option value="">-- Choose Member --</option>
                        @foreach($teamKpis as $item)
                        <option value="{{ $item['user']->id }}">{{ $item['user']->name }} ({{ $item['user']->role }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-600 mb-1">Payroll Month</label>
                        <input type="month" name="month" value="{{ $month }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-bold">
                    </div>
                    <div>
                        <label class="block text-slate-600 mb-1">Base Salary (BDT)</label>
                        <input type="number" step="0.01" name="base_salary" x-model="selectedBaseSalary" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-600 mb-1">Bonus Allocation (BDT)</label>
                        <input type="number" step="0.01" name="bonus" value="0.00" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-bold text-amber-600">
                    </div>
                    <div>
                        <label class="block text-slate-600 mb-1">Deductions (BDT)</label>
                        <input type="number" step="0.01" name="deductions" value="0.00" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-bold text-rose-600">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-600 mb-1">Payment Status</label>
                        <select name="status" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-bold">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-600 mb-1">Payment Method</label>
                        <select name="payment_method" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-semibold">
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="bkash">bKash Payroll</option>
                            <option value="cash">Cash Settlement</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-600 mb-1">Notes / Incentive Rationale</label>
                    <input type="text" name="notes" placeholder="e.g. Q4 festive SMM performance bonus" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-500 font-medium">
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="salaryModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-xl font-bold hover:from-amber-600 hover:to-amber-700 transition-all shadow-md">
                        Save Payroll Record
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
