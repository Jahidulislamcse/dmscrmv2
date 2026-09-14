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
        <h4 class="font-bold text-slate-900 text-xs leading-snug">{{ $task->title }}</h4>
        @if($task->notes)
            <p class="text-[11px] text-slate-500 font-medium line-clamp-2 mt-1">{{ $task->notes }}</p>
        @endif
    </div>

    <!-- Progress Updates Snippet (if available) -->
    @if($task->progresses->count() > 0)
        @php $latestProgress = $task->progresses->last(); @endphp
        <div class="p-2 bg-slate-50 rounded-lg border border-slate-100 text-[10px] space-y-1">
            <div class="flex items-center justify-between text-slate-500 font-semibold">
                <span><i class="fa fa-comment-dots text-amber-500 mr-1"></i> {{ $latestProgress->user->name ?? 'User' }}</span>
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
            <span class="text-[10px] font-bold text-slate-700 truncate max-w-[80px]">{{ $task->assignedTo->name ?? 'Unassigned' }}</span>
        </div>

        @if($task->deadline)
            <span class="text-[10px] font-bold {{ $task->deadline->isPast() && $task->status !== 'done' ? 'text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded' : 'text-slate-500' }}">
                <i class="fa fa-calendar-alt text-[9px]"></i> {{ $task->deadline->format('M d') }}
            </span>
        @endif
    </div>

    <!-- Move Stage Form & Actions -->
    <div class="pt-2 flex items-center justify-between gap-2 border-t border-slate-100">
        <form action="{{ route('tasks.update-status', $task->id) }}" method="POST" class="flex-1">
            @csrf
            <select name="status" onchange="this.form.submit()"
                    class="w-full text-[10px] font-bold py-1 px-1.5 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 cursor-pointer focus:outline-none">
                <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>📌 To Do / Pending</option>
                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>⚡ In Progress</option>
                <option value="done_pending_review" {{ $task->status === 'done_pending_review' ? 'selected' : '' }}>👀 Pending Review</option>
                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>✅ Completed</option>
            </select>
        </form>

        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="p-1 text-slate-400 hover:text-red-600 rounded hover:bg-red-50 transition-all" title="Delete Task">
                <i class="fa fa-trash-alt text-xs"></i>
            </button>
        </form>
    </div>
</div>
