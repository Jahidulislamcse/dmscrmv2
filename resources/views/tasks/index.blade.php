@extends('layouts.app')

@section('title', 'Agency Task Board')
@section('header_title', 'Agency Tasks & Operations Board')

@section('content')
<div class="space-y-6" x-data="{ viewMode: 'kanban', openAddModal: false, openDetailModal: false, activeTask: null }">

    <!-- Top Action Bar & Metrics Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Agency Task & Operations Board</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Manage deliverables, assign team workloads, and track task progress.</p>
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

            <button @click="openAddModal = true" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-brand-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold text-xs shadow-md shadow-amber-500/20 flex items-center gap-2 transition-all">
                <i class="fa fa-plus-circle text-sm"></i>
                <span>Create New Task</span>
            </button>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-lg">
                <i class="fa fa-clock"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">To Do / Pending</span>
                <h4 class="text-base font-extrabold text-slate-900">{{ $kanbanTasks['pending']->count() }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold text-lg">
                <i class="fa fa-spinner"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">In Progress</span>
                <h4 class="text-base font-extrabold text-amber-600">{{ $kanbanTasks['in_progress']->count() }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center font-bold text-lg">
                <i class="fa fa-eye"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending Review</span>
                <h4 class="text-base font-extrabold text-purple-600">{{ $kanbanTasks['done_pending_review']->count() }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center font-bold text-lg">
                <i class="fa fa-circle-check"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Completed</span>
                <h4 class="text-base font-extrabold text-emerald-600">{{ $kanbanTasks['done']->count() }}</h4>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('tasks.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fa fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks by title..."
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                </div>
            </div>

            <div class="w-44">
                <select name="assigned_to" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                    <option value="">All Assignees</option>
                    @foreach($teamMembers as $m)
                        <option value="{{ $m->id }}" {{ request('assigned_to') == $m->id ? 'selected' : '' }}>
                            {{ $m->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-40">
                <select name="priority" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                    <option value="">All Priorities</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High Priority</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium Priority</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low Priority</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all">
                <i class="fa fa-filter mr-1"></i> Filter
            </button>

            @if(request()->anyFilled(['search', 'assigned_to', 'priority']))
            <a href="{{ route('tasks.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200 transition-all">
                <i class="fa fa-rotate-left mr-1"></i> Reset
            </a>
            @endif
        </form>
    </div>

    <!-- KANBAN BOARD VIEW -->
    <div x-show="viewMode === 'kanban'" class="overflow-x-auto pb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 min-w-[1000px]">
            
            <!-- Column 1: Pending / To Do -->
            <div class="bg-slate-100/80 rounded-2xl border border-slate-200/90 p-3.5 space-y-3 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-500"></span>
                        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">To Do / Pending</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-white text-slate-700 border border-slate-200">
                        {{ $kanbanTasks['pending']->count() }}
                    </span>
                </div>

                <div class="space-y-3 flex-1 overflow-y-auto">
                    @forelse($kanbanTasks['pending'] as $task)
                        @include('tasks.partials.task_card', ['task' => $task])
                    @empty
                        <div class="py-12 text-center bg-white/50 rounded-xl border border-dashed border-slate-200 text-slate-400">
                            <i class="fa fa-inbox text-2xl mb-1 block"></i>
                            <span class="text-xs font-semibold">No pending tasks</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 2: In Progress -->
            <div class="bg-amber-50/50 rounded-2xl border border-amber-200/80 p-3.5 space-y-3 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between pb-2 border-b border-amber-200/80">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">In Progress</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-white text-amber-700 border border-amber-200">
                        {{ $kanbanTasks['in_progress']->count() }}
                    </span>
                </div>

                <div class="space-y-3 flex-1 overflow-y-auto">
                    @forelse($kanbanTasks['in_progress'] as $task)
                        @include('tasks.partials.task_card', ['task' => $task])
                    @empty
                        <div class="py-12 text-center bg-white/50 rounded-xl border border-dashed border-amber-200/60 text-slate-400">
                            <i class="fa fa-spinner text-2xl mb-1 block"></i>
                            <span class="text-xs font-semibold">No tasks in progress</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 3: Pending Review -->
            <div class="bg-purple-50/50 rounded-2xl border border-purple-200/80 p-3.5 space-y-3 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between pb-2 border-b border-purple-200/80">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Pending Review</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-white text-purple-700 border border-purple-200">
                        {{ $kanbanTasks['done_pending_review']->count() }}
                    </span>
                </div>

                <div class="space-y-3 flex-1 overflow-y-auto">
                    @forelse($kanbanTasks['done_pending_review'] as $task)
                        @include('tasks.partials.task_card', ['task' => $task])
                    @empty
                        <div class="py-12 text-center bg-white/50 rounded-xl border border-dashed border-purple-200/60 text-slate-400">
                            <i class="fa fa-eye text-2xl mb-1 block"></i>
                            <span class="text-xs font-semibold">No tasks under review</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 4: Completed -->
            <div class="bg-emerald-50/50 rounded-2xl border border-emerald-200/80 p-3.5 space-y-3 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between pb-2 border-b border-emerald-200/80">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Done & Approved</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-white text-emerald-700 border border-emerald-200">
                        {{ $kanbanTasks['done']->count() }}
                    </span>
                </div>

                <div class="space-y-3 flex-1 overflow-y-auto">
                    @forelse($kanbanTasks['done'] as $task)
                        @include('tasks.partials.task_card', ['task' => $task])
                    @empty
                        <div class="py-12 text-center bg-white/50 rounded-xl border border-dashed border-emerald-200/60 text-slate-400">
                            <i class="fa fa-circle-check text-2xl mb-1 block"></i>
                            <span class="text-xs font-semibold">No completed tasks</span>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- TABLE LIST VIEW -->
    <div x-show="viewMode === 'table'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" x-cloak>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">Task Title</th>
                        <th class="py-3.5 px-4">Client</th>
                        <th class="py-3.5 px-4">Assignee</th>
                        <th class="py-3.5 px-4">Priority</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Deadline</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($allTasks as $task)
                    <tr class="hover:bg-slate-50/80 transition-all">
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            {{ $task->title }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            {{ $task->client->name ?? '—' }}
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-white text-[10px]" style="background-color: {{ $task->assignedTo->color ?? '#f59e0b' }}">
                                    {{ strtoupper(substr($task->assignedTo->name ?? 'U', 0, 2)) }}
                                </div>
                                <span class="font-bold text-slate-800">{{ $task->assignedTo->name ?? 'Unassigned' }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($task->priority === 'high')
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">High</span>
                            @elseif($task->priority === 'medium')
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">Medium</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">Low</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="text-[11px] font-bold py-1 px-2 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 cursor-pointer">
                                    <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="done_pending_review" {{ $task->status === 'done_pending_review' ? 'selected' : '' }}>In Review</option>
                                    <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </form>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-600">
                            {{ $task->deadline ? $task->deadline->format('M d, Y') : '—' }}
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-all" title="Delete Task">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            No tasks found matching criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Task Modal -->
    <div x-show="openAddModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4" @click.outside="openAddModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Create & Assign New Task</h3>
                <button @click="openAddModal = false" class="text-slate-400 hover:text-slate-700"><i class="fa fa-times"></i></button>
            </div>

            <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Task Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Design 10 Social Media Graphics"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Assign To Team Member *</label>
                        <select name="assigned_to" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-500">
                            @foreach($teamMembers as $m)
                                <option value="{{ $m->id }}" {{ auth()->id() == $m->id ? 'selected' : '' }}>
                                    {{ $m->name }} ({{ ucfirst($m->role) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Priority *</label>
                        <select name="priority" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-500">
                            <option value="medium">Medium Priority</option>
                            <option value="high">High Priority</option>
                            <option value="low">Low Priority</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Related Client</label>
                        <select name="client_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-500">
                            <option value="">— Select Client (Optional) —</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->company }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deadline Date</label>
                        <input type="date" name="deadline" value="{{ date('Y-m-d', strtotime('+3 days')) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Task Details & Instructions</label>
                    <textarea name="notes" rows="3" placeholder="Specify brand requirements, dimensions, or deliverables..."
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-500"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs rounded-xl shadow-md">Create & Assign Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
