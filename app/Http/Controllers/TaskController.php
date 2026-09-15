<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\{Task, TaskProgress, TaskApprovalComment, Notification, Client, User, Service};

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Task::with(['client', 'assignedTo', 'assignedBy', 'progresses.user', 'approvalComments.user', 'subtasks.assignedTo', 'service']);

        if ($user && !$user->isOwner()) {
            $query->where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('assigned_by', $user->id);
            });
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        $allTasks = $query->whereNull('parent_task_id')->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($allTasks);
        }

        // Group tasks by Kanban column status
        $kanbanTasks = [
            'pending' => $allTasks->where('status', 'pending'),
            'in_progress' => $allTasks->where('status', 'in_progress'),
            'done_pending_review' => $allTasks->where('status', 'done_pending_review'),
            'done' => $allTasks->where('status', 'done'),
        ];

        $clients = Client::orderBy('name')->get();
        $teamMembers = User::where('active', true)->orderBy('name')->get();
        $services = Service::where('active', true)->orderBy('name')->get();

        // Employee workload summary data
        $employeeStats = $teamMembers->map(function($member) use ($allTasks) {
            $userTasks = $allTasks->where('assigned_to', $member->id);
            return [
                'user' => $member,
                'total' => $userTasks->count(),
                'in_progress' => $userTasks->where('status', 'in_progress')->count(),
                'done' => $userTasks->where('status', 'done')->count(),
                'pending' => $userTasks->where('status', 'pending')->count(),
            ];
        });

        return view('tasks.index', compact('allTasks', 'kanbanTasks', 'clients', 'teamMembers', 'services', 'employeeStats'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:high,medium,low',
            'deadline' => 'nullable|date',
            'scheduled_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'service_id' => 'nullable|exists:services,id',
            'qty' => 'nullable|integer|min:1',
            'recurring_enabled' => 'nullable|boolean',
        ]);

        // Process attachments if uploaded
        $attachmentsList = [];
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $uploadDir = public_path('uploads/tasks');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move($uploadDir, $filename);
            $attachmentsList[] = [
                'name' => $file->getClientOriginalName(),
                'url' => asset('uploads/tasks/' . $filename),
                'path' => 'uploads/tasks/' . $filename,
                'uploaded_at' => now()->toDateTimeString(),
            ];
        }

        // Process checklist items if provided as array/lines
        $checklistItems = [];
        if ($request->filled('checklist_raw')) {
            $lines = array_filter(explode("\n", str_replace("\r", "", $request->checklist_raw)));
            foreach ($lines as $idx => $line) {
                $lineText = trim($line);
                if (!empty($lineText)) {
                    $checklistItems[] = [
                        'id' => $idx + 1,
                        'title' => $lineText,
                        'completed' => false,
                    ];
                }
            }
        }

        $task = Task::create(array_merge($validated, [
            'assigned_by' => $user->id,
            'status' => 'pending',
            'recurring_enabled' => $request->boolean('recurring_enabled', false),
            'checklist' => $checklistItems,
            'attachments' => $attachmentsList,
        ]));

        if ($request->wantsJson()) {
            return response()->json($task->load(['client', 'assignedTo', 'assignedBy']), 201);
        }

        return redirect()->route('tasks.index')->with('success', "Task '{$task->title}' created successfully!");
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:high,medium,low',
            'deadline' => 'nullable|date',
            'scheduled_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,done_pending_review,done',
        ]);

        $attachmentsList = $task->attachments ?? [];
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $uploadDir = public_path('uploads/tasks');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move($uploadDir, $filename);
            $attachmentsList[] = [
                'name' => $file->getClientOriginalName(),
                'url' => asset('uploads/tasks/' . $filename),
                'path' => 'uploads/tasks/' . $filename,
                'uploaded_at' => now()->toDateTimeString(),
            ];
        }

        $task->update(array_merge($validated, [
            'attachments' => $attachmentsList,
        ]));

        if ($request->wantsJson()) {
            return response()->json($task->load(['client', 'assignedTo']));
        }

        return redirect()->route('tasks.index')->with('success', "Task '{$task->title}' updated successfully!");
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,done_pending_review,done',
        ]);

        $oldStatus = $task->status;
        $task->status = $request->status;
        $task->save();

        if ($request->wantsJson()) {
            return response()->json($task->fresh(['assignedTo', 'progresses']));
        }

        return redirect()->route('tasks.index')->with('success', "Task status updated to " . ucfirst(str_replace('_', ' ', $request->status)) . "!");
    }

    public function updateChecklist(Request $request, Task $task)
    {
        $items = $request->input('checklist', []);
        $task->checklist = $items;
        $task->save();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Checklist updated', 'task' => $task]);
        }

        return redirect()->back()->with('success', 'Task checklist updated!');
    }

    public function uploadAttachment(Request $request, Task $task)
    {
        $request->validate([
            'attachment' => 'required|file|max:10240', // max 10MB
        ]);

        $attachmentsList = $task->attachments ?? [];
        $file = $request->file('attachment');
        $uploadDir = public_path('uploads/tasks');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $file->move($uploadDir, $filename);
        $attachmentsList[] = [
            'name' => $file->getClientOriginalName(),
            'url' => asset('uploads/tasks/' . $filename),
            'path' => 'uploads/tasks/' . $filename,
            'uploaded_at' => now()->toDateTimeString(),
        ];

        $task->attachments = $attachmentsList;
        $task->save();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Attachment uploaded', 'attachments' => $attachmentsList]);
        }

        return redirect()->back()->with('success', 'File attachment uploaded successfully!');
    }

    public function deleteAttachment(Request $request, Task $task, $index)
    {
        $attachmentsList = $task->attachments ?? [];
        if (isset($attachmentsList[$index])) {
            $filePath = public_path($attachmentsList[$index]['path']);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            array_splice($attachmentsList, $index, 1);
            $task->attachments = array_values($attachmentsList);
            $task->save();
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Attachment deleted']);
        }

        return redirect()->back()->with('success', 'Attachment deleted successfully!');
    }

    public function addProgress(Request $request, Task $task)
    {
        $request->validate([
            'note' => 'required|string',
            'done' => 'nullable|integer|min:0',
            'total' => 'nullable|integer|min:1',
        ]);

        TaskProgress::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'note' => $request->note,
            'done' => $request->done ?? 0,
            'total' => $request->total ?? 0,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Progress added']);
        }

        return redirect()->back()->with('success', 'Progress update logged successfully!');
    }

    public function myTasks(Request $request)
    {
        $user = Auth::user();
        $query = Task::with(['client', 'assignedTo', 'assignedBy', 'progresses.user', 'subtasks', 'service'])
            ->where('assigned_to', $user->id)
            ->whereNull('parent_task_id');

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        $allTasks = $query->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($allTasks);
        }

        $kanbanTasks = [
            'pending' => $allTasks->where('status', 'pending'),
            'in_progress' => $allTasks->where('status', 'in_progress'),
            'done_pending_review' => $allTasks->where('status', 'done_pending_review'),
            'done' => $allTasks->where('status', 'done'),
        ];

        // Personal Productivity Metrics
        $totalCount = $allTasks->count();
        $pendingCount = $kanbanTasks['pending']->count();
        $inProgressCount = $kanbanTasks['in_progress']->count();
        $reviewCount = $kanbanTasks['done_pending_review']->count();
        $doneCount = $kanbanTasks['done']->count();

        $overdueCount = $allTasks->filter(function($t) {
            return $t->deadline && $t->deadline->isPast() && $t->status !== 'done';
        })->count();

        $completionRate = $totalCount > 0 ? round(($doneCount / $totalCount) * 100) : 0;

        return view('tasks.my_tasks', compact(
            'allTasks',
            'kanbanTasks',
            'totalCount',
            'pendingCount',
            'inProgressCount',
            'reviewCount',
            'doneCount',
            'overdueCount',
            'completionRate'
        ));
    }

    public function destroy(Task $task)
    {
        $task->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Task deleted']);
        }

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}
