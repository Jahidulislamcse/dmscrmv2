@extends('layouts.app')

@section('title', 'My Assigned Tasks')
@section('header_title', 'My Personal Work & Deliverables')

@section('content')
<div class="space-y-6" x-data="{ viewMode: 'kanban', openDetailModal: false, activeTask: {} }">

    <!-- Top Action Bar & Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">My Assigned Tasks & Deliverables</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Track your personal workload, toggle checklist subtasks, upload deliverables, and log progress updates.</p>
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
        </div>
    </div>

    <!-- Personal Performance Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-base">
                <i class="fa fa-layer-group"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Assigned</span>
                <h4 class="text-base font-extrabold text-slate-900">{{ $totalCount }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold text-base">
                <i class="fa fa-spinner"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">In Progress</span>
                <h4 class="text-base font-extrabold text-amber-600">{{ $inProgressCount }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center font-bold text-base">
                <i class="fa fa-eye"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending Review</span>
                <h4 class="text-base font-extrabold text-purple-600">{{ $reviewCount }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center font-bold text-base">
                <i class="fa fa-circle-check"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Completed</span>
                <h4 class="text-base font-extrabold text-emerald-600">{{ $doneCount }} <span class="text-[10px] text-slate-400 font-normal">({{ $completionRate }}%)</span></h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-3.5 col-span-2 sm:col-span-1">
            <div class="w-10 h-10 rounded-xl {{ $overdueCount > 0 ? 'bg-rose-500/10 text-rose-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center font-bold text-base">
                <i class="fa fa-triangle-exclamation"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Overdue Tasks</span>
                <h4 class="text-base font-extrabold {{ $overdueCount > 0 ? 'text-rose-600' : 'text-slate-700' }}">{{ $overdueCount }}</h4>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('tasks.my-tasks') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fa fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search my assigned tasks..."
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                </div>
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

            @if(request()->anyFilled(['search', 'priority']))
            <a href="{{ route('tasks.my-tasks') }}" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200 transition-all">
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
                        <th class="py-3.5 px-4">Priority</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Deadline</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($allTasks as $task)
                    <tr class="hover:bg-slate-50/80 transition-all">
                        <td class="py-3.5 px-4 font-bold text-slate-900 cursor-pointer hover:text-amber-600"
                            @click="activeTask = @js($task); openDetailModal = true">
                            {{ $task->title }}
                            @if(!empty($task->checklist))
                                <span class="ml-2 text-[10px] text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded font-normal border border-amber-200">
                                    <i class="fa fa-tasks"></i> {{ $task->checklist_progress['done'] }}/{{ $task->checklist_progress['total'] }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            {{ $task->client->name ?? '—' }}
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
                            <button type="button" @click="activeTask = @js($task); openDetailModal = true" class="px-3 py-1.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-lg transition-all" title="View & Manage">
                                <i class="fa fa-eye mr-1"></i> Manage
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            No tasks assigned to you matching criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TASK DETAIL & PROGRESS MODAL -->
    <div x-show="openDetailModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-2xl w-full p-6 space-y-5 max-h-[90vh] overflow-y-auto" @click.outside="openDetailModal = false">
            
            <div class="flex items-start justify-between border-b border-slate-100 pb-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded bg-amber-100 text-amber-800" x-text="activeTask.priority ? activeTask.priority + ' priority' : 'Task'"></span>
                        <span class="text-xs font-bold text-slate-500" x-text="activeTask.client ? activeTask.client.name : 'Internal Task'"></span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900" x-text="activeTask.title"></h3>
                </div>
                <button @click="openDetailModal = false" class="text-slate-400 hover:text-slate-700 text-sm p-1"><i class="fa fa-times"></i></button>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80 space-y-2">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Instructions & Details</h4>
                <p class="text-xs text-slate-600 whitespace-pre-line" x-text="activeTask.notes || 'No detailed instructions provided.'"></p>
                <div class="flex items-center gap-4 text-xs text-slate-500 pt-2 border-t border-slate-200/60">
                    <div><strong>Assigned By:</strong> <span x-text="activeTask.assigned_by ? activeTask.assigned_by.name : (activeTask.assigned_by_user ? activeTask.assigned_by_user.name : 'Manager')"></span></div>
                    <div><strong>Deadline:</strong> <span x-text="activeTask.deadline ? activeTask.deadline.substring(0,10) : 'No Deadline'"></span></div>
                </div>
            </div>

            <!-- Subtask Checklist -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center justify-between">
                    <span><i class="fa fa-tasks text-amber-500 mr-1"></i> Checklist Subtasks</span>
                </h4>

                <template x-if="activeTask.checklist && activeTask.checklist.length > 0">
                    <div class="space-y-2 bg-white rounded-xl border border-slate-200 p-3">
                        <template x-for="(item, idx) in activeTask.checklist" :key="idx">
                            <div class="flex items-center justify-between text-xs py-1 px-2 hover:bg-slate-50 rounded">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" :checked="item.completed"
                                           @change="item.completed = !item.completed; $nextTick(() => { 
                                               fetch(`{{ url('/tasks') }}/${activeTask.id}/checklist`, {
                                                   method: 'POST',
                                                   headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                   body: JSON.stringify({ checklist: activeTask.checklist })
                                               });
                                           })"
                                           class="rounded text-amber-500 focus:ring-amber-400">
                                    <span :class="item.completed ? 'line-through text-slate-400' : 'text-slate-800 font-semibold'" x-text="item.title"></span>
                                </label>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="!activeTask.checklist || activeTask.checklist.length === 0">
                    <p class="text-xs text-slate-400 italic">No checklist subtasks added yet.</p>
                </template>
            </div>

            <!-- Attachments & Deliverables -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                    <i class="fa fa-paperclip text-amber-500 mr-1"></i> Attachments & Deliverables
                </h4>

                <template x-if="activeTask.attachments && activeTask.attachments.length > 0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <template x-for="(att, idx) in activeTask.attachments" :key="idx">
                            <div class="flex items-center justify-between bg-slate-50 p-2.5 rounded-xl border border-slate-200 text-xs">
                                <a :href="att.url" target="_blank" class="font-bold text-amber-600 hover:underline truncate max-w-[180px]" x-text="att.name"></a>
                                <form :action="`{{ url('/tasks') }}/${activeTask.id}/attachments/${idx}`" method="POST" onsubmit="return confirm('Remove attachment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs p-1"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </template>
                    </div>
                </template>

                <form :action="`{{ url('/tasks') }}/${activeTask.id}/attachments`" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 pt-1">
                    @csrf
                    <input type="file" name="attachment" required class="text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    <button type="submit" class="px-3 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-bold hover:bg-slate-800">Upload File</button>
                </form>
            </div>

            <!-- Employee Work Progress Log -->
            <div class="space-y-3 border-t border-slate-100 pt-4">
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                    <i class="fa fa-user-clock text-amber-500 mr-1"></i> Log My Work Progress
                </h4>

                <form :action="`{{ url('/tasks') }}/${activeTask.id}/progress`" method="POST" class="bg-amber-50/50 p-3 rounded-xl border border-amber-200 space-y-2">
                    @csrf
                    <div class="flex items-center gap-2">
                        <input type="text" name="note" required placeholder="Log progress note (e.g. Completed initial design mockups)..."
                               class="flex-1 px-3 py-1.5 bg-white border border-amber-200 rounded-lg text-xs font-medium focus:outline-none focus:border-amber-500">
                        <input type="number" name="done" placeholder="Done" class="w-16 px-2 py-1.5 bg-white border border-amber-200 rounded-lg text-xs font-medium">
                        <input type="number" name="total" placeholder="Total" class="w-16 px-2 py-1.5 bg-white border border-amber-200 rounded-lg text-xs font-medium">
                        <button type="submit" class="px-3 py-1.5 bg-amber-500 text-white font-bold text-xs rounded-lg hover:bg-amber-600 shadow-sm">Log Note</button>
                    </div>
                </form>

                <template x-if="activeTask.progresses && activeTask.progresses.length > 0">
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        <template x-for="prog in activeTask.progresses" :key="prog.id">
                            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/70 text-xs space-y-1">
                                <div class="flex items-center justify-between text-slate-500 font-semibold">
                                    <span class="font-bold text-slate-800" x-text="prog.user ? prog.user.name : 'Team Member'"></span>
                                    <span x-text="prog.created_at ? prog.created_at.substring(0,10) : ''"></span>
                                </div>
                                <p class="text-slate-700" x-text="prog.note"></p>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <div class="pt-2 flex justify-end border-t border-slate-100">
                <button type="button" @click="openDetailModal = false" class="px-4 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl">Close</button>
            </div>
        </div>
    </div>

</div>
@endsection
