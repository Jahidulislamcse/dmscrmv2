@extends('layouts.app')

@section('title', 'Follow-ups Management')
@section('header_title', 'Sales & Client Follow-ups Hub')

@section('content')
<div class="space-y-6" x-data="{
    openCreateModal: false,
    openCompleteModal: false,
    openEditModal: false,
    activeFollowup: {},
    outcomeText: '',
    nextFollowupDate: '',
    nextFollowupTitle: ''
}">

    <!-- Top Action Bar & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Sales & Client Follow-ups Hub</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Track, schedule, and execute follow-up communications across leads, clients, and meetings.</p>
        </div>

        <div class="flex items-center gap-3">
            <button @click="openCreateModal = true"
                    class="px-4 py-2 bg-gradient-to-r from-brand-600 to-amber-600 hover:from-brand-700 hover:to-amber-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                <i class="fa fa-plus"></i>
                <span>Schedule Follow-up</span>
            </button>
        </div>
    </div>

    <!-- Overview Metrics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold text-lg">
                <i class="fa fa-calendar-day"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Due Today</span>
                <h4 class="text-lg font-extrabold text-amber-600">{{ $todayCount }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl {{ $overdueCount > 0 ? 'bg-rose-500/10 text-rose-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center font-bold text-lg">
                <i class="fa fa-triangle-exclamation"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Overdue Alerts</span>
                <h4 class="text-lg font-extrabold {{ $overdueCount > 0 ? 'text-rose-600' : 'text-slate-700' }}">{{ $overdueCount }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center font-bold text-lg">
                <i class="fa fa-clock"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pending</span>
                <h4 class="text-lg font-extrabold text-blue-600">{{ $pendingTotal }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center font-bold text-lg">
                <i class="fa fa-circle-check"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Completed Log</span>
                <h4 class="text-lg font-extrabold text-emerald-600">{{ $completedTotal }}</h4>
            </div>
        </div>
    </div>

    <!-- Filter & Navigation Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm space-y-3">
        <form action="{{ route('followups.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <!-- Tabs -->
            <div class="bg-slate-100 p-1 rounded-xl flex items-center gap-1 overflow-x-auto">
                <a href="{{ route('followups.index', array_merge(request()->query(), ['tab' => 'today'])) }}"
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap {{ $tab === 'today' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <i class="fa fa-calendar-day text-amber-500"></i>
                    <span>Due Today ({{ $todayCount }})</span>
                </a>

                <a href="{{ route('followups.index', array_merge(request()->query(), ['tab' => 'overdue'])) }}"
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap {{ $tab === 'overdue' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <i class="fa fa-triangle-exclamation text-rose-500"></i>
                    <span>Overdue ({{ $overdueCount }})</span>
                </a>

                <a href="{{ route('followups.index', array_merge(request()->query(), ['tab' => 'pending'])) }}"
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap {{ $tab === 'pending' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <i class="fa fa-clock text-blue-500"></i>
                    <span>All Pending ({{ $pendingTotal }})</span>
                </a>

                <a href="{{ route('followups.index', array_merge(request()->query(), ['tab' => 'completed'])) }}"
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap {{ $tab === 'completed' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <i class="fa fa-circle-check text-emerald-500"></i>
                    <span>Completed ({{ $completedTotal }})</span>
                </a>
            </div>

            <!-- Filters -->
            <div class="flex items-center gap-2 flex-wrap">
                <input type="hidden" name="tab" value="{{ $tab }}">

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search follow-ups..."
                       class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500 w-40 sm:w-48">

                <select name="type" onchange="this.form.submit()" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                    <option value="">All Channels</option>
                    <option value="call" {{ request('type') == 'call' ? 'selected' : '' }}>Phone Call</option>
                    <option value="whatsapp" {{ request('type') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                    <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="meeting" {{ request('type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                    <option value="visit" {{ request('type') == 'visit' ? 'selected' : '' }}>Client Visit</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>

                <select name="priority" onchange="this.form.submit()" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                    <option value="">All Priorities</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>

                <button type="submit" class="px-3 py-1.5 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition-all">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Follow-ups Table List -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-5">Follow-up Task</th>
                        <th class="py-3.5 px-5">Related Entity</th>
                        <th class="py-3.5 px-5">Channel</th>
                        <th class="py-3.5 px-5">Priority</th>
                        <th class="py-3.5 px-5">Scheduled For</th>
                        <th class="py-3.5 px-5">Assigned To</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($followups as $f)
                    @php
                        $isOverdue = $f->isOverdue();
                        $isToday = $f->isToday();
                        $entityName = $f->lead->name ?? ($f->client->name ?? ($f->meeting->lead_name ?? 'General'));
                        $entitySub = $f->lead->phone ?? ($f->client->company ?? ($f->meeting->agenda ?? ''));
                        $entityPhone = $f->lead->phone ?? ($f->client->phone ?? '');
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-all {{ $isOverdue ? 'bg-rose-50/30' : ($isToday ? 'bg-amber-50/30' : '') }}">
                        <!-- Title & Description -->
                        <td class="py-3.5 px-5">
                            <div class="font-bold text-slate-900 text-sm flex items-center gap-1.5">
                                @if($f->status === 'completed')
                                    <i class="fa fa-circle-check text-emerald-500"></i>
                                @elseif($isOverdue)
                                    <i class="fa fa-circle-exclamation text-rose-500"></i>
                                @else
                                    <i class="fa fa-clock text-amber-500"></i>
                                @endif
                                <span>{{ $f->title }}</span>
                            </div>
                            @if($f->description)
                                <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5" title="{{ $f->description }}">{{ $f->description }}</div>
                            @endif
                            @if($f->status === 'completed' && $f->outcome)
                                <div class="mt-1 text-[11px] text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                    <span class="font-bold">Outcome:</span> {{ $f->outcome }}
                                </div>
                            @endif
                        </td>

                        <!-- Related Entity (Lead / Client / Meeting) -->
                        <td class="py-3.5 px-5">
                            @if($f->lead)
                                <a href="{{ route('crm.show', $f->lead->id) }}" class="font-bold text-brand-600 hover:underline flex items-center gap-1">
                                    <i class="fa fa-user-tie text-[10px]"></i> Lead: {{ $f->lead->name }}
                                </a>
                                <div class="text-[11px] text-slate-500 font-mono">{{ $f->lead->phone ?? $f->lead->email }}</div>
                            @elseif($f->client)
                                <a href="{{ route('clients.show', $f->client->id) }}" class="font-bold text-emerald-600 hover:underline flex items-center gap-1">
                                    <i class="fa fa-building text-[10px]"></i> Client: {{ $f->client->name }}
                                </a>
                                <div class="text-[11px] text-slate-500">{{ $f->client->company }}</div>
                            @elseif($f->meeting)
                                <a href="{{ route('meetings.show', $f->meeting->id) }}" class="font-bold text-purple-600 hover:underline flex items-center gap-1">
                                    <i class="fa fa-calendar-alt text-[10px]"></i> {{ $f->meeting->lead_name ?? 'Meeting' }}
                                </a>
                                <div class="text-[11px] text-slate-500 truncate max-w-xs">{{ $f->meeting->agenda }}</div>
                            @else
                                <span class="text-slate-400 font-semibold">— General —</span>
                            @endif
                        </td>

                        <!-- Type / Channel -->
                        <td class="py-3.5 px-5">
                            @if($f->type === 'call')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-300 inline-flex items-center gap-1">
                                    <i class="fa fa-phone text-blue-600"></i> Call
                                </span>
                            @elseif($f->type === 'whatsapp')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1">
                                    <i class="fa-brands fa-whatsapp text-emerald-600"></i> WhatsApp
                                </span>
                            @elseif($f->type === 'email')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300 inline-flex items-center gap-1">
                                    <i class="fa fa-envelope text-amber-600"></i> Email
                                </span>
                            @elseif($f->type === 'meeting')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-800 border border-purple-300 inline-flex items-center gap-1">
                                    <i class="fa fa-users text-purple-600"></i> Meeting
                                </span>
                            @elseif($f->type === 'visit')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-100 text-indigo-800 border border-indigo-300 inline-flex items-center gap-1">
                                    <i class="fa fa-location-dot text-indigo-600"></i> Visit
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-300">Other</span>
                            @endif
                        </td>

                        <!-- Priority -->
                        <td class="py-3.5 px-5">
                            @if($f->priority === 'urgent')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300 animate-pulse">URGENT</span>
                            @elseif($f->priority === 'high')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300">HIGH</span>
                            @elseif($f->priority === 'medium')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-300">MEDIUM</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-300">LOW</span>
                            @endif
                        </td>

                        <!-- Scheduled Date -->
                        <td class="py-3.5 px-5 font-mono">
                            <div class="{{ $isOverdue ? 'text-rose-600 font-bold' : ($isToday ? 'text-amber-600 font-bold' : 'text-slate-800') }}">
                                {{ $f->scheduled_at ? $f->scheduled_at->format('M d, Y h:i A') : 'N/A' }}
                            </div>
                            @if($isOverdue)
                                <span class="text-[10px] text-rose-500 font-bold">OVERDUE</span>
                            @elseif($isToday)
                                <span class="text-[10px] text-amber-600 font-bold">TODAY</span>
                            @endif
                        </td>

                        <!-- Assigned To -->
                        <td class="py-3.5 px-5 font-bold text-slate-800">
                            {{ $f->assignedTo->name ?? 'Unassigned' }}
                        </td>

                        <!-- Action Buttons -->
                        <td class="py-3.5 px-5 text-right space-x-1 whitespace-nowrap">
                            @if($f->status === 'pending')
                                <button type="button"
                                        @click="
                                            activeFollowup = @js($f);
                                            outcomeText = '';
                                            openCompleteModal = true;
                                        "
                                        class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 font-bold text-xs transition-all inline-flex items-center gap-1"
                                        title="Mark Complete">
                                    <i class="fa fa-check"></i> Complete
                                </button>
                            @endif

                            @if($entityPhone && ($f->type === 'whatsapp' || $f->type === 'call'))
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $entityPhone);
                                    if(strlen($cleanPhone) === 11 && str_starts_with($cleanPhone, '01')) $cleanPhone = '88' . $cleanPhone;
                                    $waUrl = "https://wa.me/{$cleanPhone}?text=" . urlencode("Assalamu Alaikum {$entityName}, this is regarding {$f->title}");
                                @endphp
                                <a href="{{ $waUrl }}" target="_blank"
                                   class="px-2 py-1 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-bold text-xs transition-all inline-flex items-center gap-1"
                                   title="Chat on WhatsApp">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            @endif

                            <button type="button"
                                    @click="
                                        activeFollowup = @js($f);
                                        openEditModal = true;
                                    "
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all">
                                <i class="fa fa-pen"></i>
                            </button>

                            <form action="{{ route('followups.destroy', $f->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this follow-up?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-rose-400 hover:text-rose-600 hover:bg-rose-50 transition-all">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <i class="fa fa-calendar-check text-3xl mb-2 text-slate-300 block"></i>
                            <span class="font-bold text-slate-600">No follow-ups found for this tab filter.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($followups->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $followups->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- SCHEDULE NEW FOLLOW-UP MODAL -->
    <div x-show="openCreateModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto" @click.outside="openCreateModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Schedule New Follow-up</h3>
                <button @click="openCreateModal = false" class="text-slate-400 hover:text-slate-700"><i class="fa fa-times"></i></button>
            </div>

            <form action="{{ route('followups.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Follow-up Task Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Call Rafiq regarding web design quotation"
                           class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Channel / Type *</label>
                        <select name="type" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                            <option value="call">Phone Call</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="email">Email</option>
                            <option value="meeting">Meeting</option>
                            <option value="visit">Client Visit</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Priority *</label>
                        <select name="priority" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                            <option value="medium">Medium</option>
                            <option value="urgent">URGENT</option>
                            <option value="high">High</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Scheduled Date & Time *</label>
                        <input type="datetime-local" name="scheduled_at" required value="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Assign To Staff</label>
                        <select name="assigned_to" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ $u->id == auth()->id() ? 'selected' : '' }}>{{ $u->name }} ({{ strtoupper($u->role) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Entity Link Options -->
                <div class="border-t border-slate-100 pt-3 space-y-3">
                    <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Link to Entity (Optional)</span>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Link to Lead</label>
                            <select name="lead_id" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                                <option value="">— Select Lead —</option>
                                @foreach($leads as $ld)
                                    <option value="{{ $ld->id }}">{{ $ld->name }} ({{ $ld->company ?? 'No Company' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Link to Client</label>
                            <select name="client_id" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                                <option value="">— Select Client —</option>
                                @foreach($clients as $cl)
                                    <option value="{{ $cl->id }}">{{ $cl->name }} ({{ $cl->company }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Task Description / Agenda Notes</label>
                    <textarea name="description" rows="3" placeholder="Additional details or context for the follow-up..."
                              class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="openCreateModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-brand-600 to-amber-600 text-white font-bold text-xs rounded-xl shadow-md">
                        Schedule Follow-up
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MARK COMPLETE MODAL -->
    <div x-show="openCompleteModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full p-6 space-y-4" @click.outside="openCompleteModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Complete Follow-up Task</h3>
                    <p class="text-xs text-slate-500" x-text="activeFollowup.title"></p>
                </div>
                <button @click="openCompleteModal = false" class="text-slate-400 hover:text-slate-700"><i class="fa fa-times"></i></button>
            </div>

            <form :action="`/followups/${activeFollowup.id}/complete`" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Outcome Notes / Remarks *</label>
                    <textarea name="outcome" x-model="outcomeText" rows="3" required placeholder="e.g. Client agreed to quotation. Requested contract draft by tomorrow."
                              class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500"></textarea>
                </div>

                <!-- Optional Next Follow-up -->
                <div class="border-t border-slate-100 pt-3 space-y-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Schedule Next Follow-up? (Optional)</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Next Date</label>
                            <input type="datetime-local" name="next_followup_date" x-model="nextFollowupDate"
                                   class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Next Task Title</label>
                            <input type="text" name="next_followup_title" x-model="nextFollowupTitle" placeholder="e.g. Send contract draft"
                                   class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                        </div>
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="openCompleteModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md">
                        Mark as Completed
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT FOLLOW-UP MODAL -->
    <div x-show="openEditModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto" @click.outside="openEditModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Edit Follow-up Task</h3>
                <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-700"><i class="fa fa-times"></i></button>
            </div>

            <form :action="`/followups/${activeFollowup.id}`" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Task Title *</label>
                    <input type="text" name="title" :value="activeFollowup.title" required
                           class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Channel *</label>
                        <select name="type" :value="activeFollowup.type" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                            <option value="call">Phone Call</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="email">Email</option>
                            <option value="meeting">Meeting</option>
                            <option value="visit">Client Visit</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Priority *</label>
                        <select name="priority" :value="activeFollowup.priority" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                            <option value="urgent">URGENT</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status *</label>
                        <select name="status" :value="activeFollowup.status" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Scheduled Date & Time *</label>
                    <input type="datetime-local" name="scheduled_at" :value="activeFollowup.scheduled_at ? activeFollowup.scheduled_at.replace(' ', 'T').slice(0, 16) : ''" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Description</label>
                    <textarea name="description" rows="3" :value="activeFollowup.description"
                              class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Outcome Notes</label>
                    <textarea name="outcome" rows="2" :value="activeFollowup.outcome"
                              class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl shadow-md">
                        Update Follow-up
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
