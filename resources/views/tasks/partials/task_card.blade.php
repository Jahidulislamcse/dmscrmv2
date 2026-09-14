<div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md transition-all space-y-3 relative group">
    <!-- Header: Priority & Client Tag -->
    <div class="flex items-center justify-between">
        @if($task->priority === 'high')
            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                <i class="fa fa-fire mr-1"></i> High Priority
            </span>
        @elseif($task->priority === 'medium')
            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                Medium Priority
            </span>
        @else
            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                Low Priority
            </span>
        @endif

        @if($task->client)
            <span class="text-[10px] font-bold text-slate-500 truncate max-w-[120px]" title="{{ $task->client->name }}">
                <i class="fa fa-building text-[9px] text-slate-400"></i> {{ $task->client->name }}
            </span>
        @endif
    </div>

    <!-- Title & Notes -->
    <div>
        <h4 class="font-bold text-slate-900 text-xs leading-snug cursor-pointer hover:text-amber-600 transition-colors"
            @click="activeTask = @js($task); openDetailModal = true">
            {{ $task->title }}
        </h4>
        @if($task->notes)
            <p class="text-[11px] text-slate-500 font-medium line-clamp-2 mt-1">{{ $task->notes }}</p>
        @endif
    </div>

    <!-- Subtask Checklist Indicator & Progress Bar -->
    @php $prog = $task->checklist_progress; @endphp
    @if($prog['total'] > 0)
        <div class="space-y-1">
            <div class="flex items-center justify-between text-[10px] font-bold text-slate-600">
                <span class="flex items-center gap-1"><i class="fa fa-tasks text-amber-500 text-[9px]"></i> Checklist</span>
                <span>{{ $prog['done'] }}/{{ $prog['total'] }} ({{ $prog['percent'] }}%)</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-amber-500 h-1.5 rounded-full transition-all duration-300" style="width: {{ $prog['percent'] }}%"></div>
            </div>
        </div>
    @endif

    <!-- Attachments Snippet -->
    @if(!empty($task->attachments) && count($task->attachments) > 0)
        <div class="flex items-center gap-1.5 flex-wrap">
            @foreach($task->attachments as $att)
                <a href="{{ $att['url'] }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded border border-slate-200 truncate max-w-[150px]" title="{{ $att['name'] }}">
                    <i class="fa fa-paperclip text-[9px] text-slate-400"></i>
                    <span class="truncate">{{ $att['name'] }}</span>
                </a>
            @endforeach
        </div>
    @endif

    <!-- Latest Employee Progress Note -->
    @if($task->progresses->count() > 0)
        @php $latestProgress = $task->progresses->last(); @endphp
        <div class="p-2 bg-amber-50/50 rounded-lg border border-amber-100 text-[10px] space-y-1">
            <div class="flex items-center justify-between text-slate-500 font-semibold">
                <span class="font-bold text-slate-800"><i class="fa fa-user-clock text-amber-500 mr-1"></i> {{ $latestProgress->user->name ?? 'Team Member' }}</span>
                <span>{{ $latestProgress->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-slate-700 font-medium line-clamp-1">{{ $latestProgress->note }}</p>
        </div>
    @endif

    <!-- Footer: Assignee, Deadline & Quick Status Select -->
    <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2">
        <div class="flex items-center gap-1.5" title="Assigned to {{ $task->assignedTo->name ?? 'Unassigned' }}">
            <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-white text-[9px] shadow-sm" style="background-color: {{ $task->assignedTo->color ?? '#f59e0b' }}">
                {{ strtoupper(substr($task->assignedTo->name ?? 'U', 0, 2)) }}
            </div>
            <span class="text-[10px] font-bold text-slate-700 truncate max-w-[90px]">{{ $task->assignedTo->name ?? 'Unassigned' }}</span>
        </div>

        @if($task->deadline)
            <span class="text-[10px] font-bold {{ $task->deadline->isPast() && $task->status !== 'done' ? 'text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200' : 'text-slate-500' }}">
                <i class="fa fa-calendar-alt text-[9px]"></i> {{ $task->deadline->format('M d') }}
            </span>
        @endif
    </div>

    <!-- Move Stage Form & Action Buttons -->
    <div class="pt-2 flex items-center justify-between gap-1.5 border-t border-slate-100">
        <form action="{{ route('tasks.update-status', $task->id) }}" method="POST" class="flex-1">
            @csrf
            <select name="status" onchange="this.form.submit()"
                    class="w-full text-[10px] font-bold py-1 px-1.5 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 cursor-pointer focus:outline-none focus:ring-1 focus:ring-amber-500">
                <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>📌 To Do</option>
                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>⚡ In Progress</option>
                <option value="done_pending_review" {{ $task->status === 'done_pending_review' ? 'selected' : '' }}>👀 Review</option>
                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>✅ Completed</option>
            </select>
        </form>

        <button type="button" @click="activeTask = @js($task); openDetailModal = true" class="px-2 py-1 text-[10px] font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all" title="View & Manage Task">
            <i class="fa fa-eye"></i>
        </button>

        <button type="button" @click="activeTask = @js($task); openEditModal = true" class="px-2 py-1 text-[10px] font-bold text-amber-600 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition-all" title="Edit Task">
            <i class="fa fa-edit"></i>
        </button>

        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="p-1 text-slate-400 hover:text-red-600 rounded hover:bg-red-50 transition-all" title="Delete Task">
                <i class="fa fa-trash-alt text-xs"></i>
            </button>
        </form>
    </div>
</div>
