@extends('layouts.app')

@section('title', 'Content Ops & Editorial Calendar - Deep CRM')

@section('content')
<div class="space-y-6">

    <!-- TOP HEADER & SUMMARY METRICS -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-purple-600 to-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-purple-500/20">
                    <i class="fa fa-calendar-days"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        Content Management & Calendar
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-700 border border-purple-200">
                            REQ-CAL-01 & 02
                        </span>
                    </h1>
                    <p class="text-xs text-slate-500">Design, Reel, Video & Copy tracking across the strict 6-stage agency lifecycle.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- View Mode Switcher -->
            <div class="bg-white border border-slate-200 rounded-xl p-1 flex items-center shadow-sm">
                <a href="{{ request()->fullUrlWithQuery(['view' => 'calendar']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all {{ $viewMode === 'calendar' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <i class="fa fa-calendar-grid-5"></i>
                    <span>Calendar Grid</span>
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view' => 'pipeline']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all {{ $viewMode === 'pipeline' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <i class="fa fa-arrows-split-up-and-left"></i>
                    <span>6-Stage Pipeline</span>
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all {{ $viewMode === 'list' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <i class="fa fa-list"></i>
                    <span>List</span>
                </a>
            </div>

            <!-- New Content Button -->
            <button onclick="openCreateModal()" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-xs font-black shadow-md shadow-purple-500/20 flex items-center gap-2 transition-all">
                <i class="fa fa-plus"></i>
                <span>Schedule Content</span>
            </button>
        </div>
    </div>

    <!-- 6-STAGE LIFECYCLE SUMMARY BANNER -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">
        @php
            $stagesConfig = [
                'draft'           => ['title' => 'Draft', 'desc' => 'Ideation & Copy', 'icon' => 'fa-pen', 'border' => 'border-slate-300', 'bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'badge' => 'bg-slate-200 text-slate-800'],
                'internal_review' => ['title' => 'Internal Review', 'desc' => 'QA / Lead Check', 'icon' => 'fa-magnifying-glass', 'border' => 'border-indigo-300', 'bg' => 'bg-indigo-50/50', 'text' => 'text-indigo-700', 'badge' => 'bg-indigo-100 text-indigo-800'],
                'client_approval' => ['title' => 'Client Approval', 'desc' => 'Awaiting Sign-off', 'icon' => 'fa-paper-plane', 'border' => 'border-blue-300', 'bg' => 'bg-blue-50/50', 'text' => 'text-blue-700', 'badge' => 'bg-blue-100 text-blue-800'],
                'revision'        => ['title' => 'Revision Needed', 'desc' => 'Changes Requested', 'icon' => 'fa-rotate-right', 'border' => 'border-amber-300', 'bg' => 'bg-amber-50/50', 'text' => 'text-amber-700', 'badge' => 'bg-amber-100 text-amber-800'],
                'approved'        => ['title' => 'Approved', 'desc' => 'Ready to Publish', 'icon' => 'fa-circle-check', 'border' => 'border-emerald-300', 'bg' => 'bg-emerald-50/50', 'text' => 'text-emerald-700', 'badge' => 'bg-emerald-100 text-emerald-800'],
                'published'       => ['title' => 'Published', 'desc' => 'Live on Channels', 'icon' => 'fa-globe', 'border' => 'border-teal-300', 'bg' => 'bg-teal-50/50', 'text' => 'text-teal-700', 'badge' => 'bg-teal-100 text-teal-800'],
            ];
        @endphp

        @foreach($stagesConfig as $sKey => $sMeta)
        <a href="{{ request()->fullUrlWithQuery(['status' => $sKey]) }}"
           class="p-3 rounded-2xl border {{ $sMeta['border'] }} {{ $sMeta['bg'] }} hover:shadow-md transition-all flex flex-col justify-between group {{ request('status') === $sKey ? 'ring-2 ring-purple-500' : '' }}">
            <div class="flex items-center justify-between">
                <span class="w-6 h-6 rounded-lg bg-white shadow-xs flex items-center justify-center text-xs {{ $sMeta['text'] }}">
                    <i class="fa {{ $sMeta['icon'] }}"></i>
                </span>
                <span class="text-xs font-mono font-black px-2 py-0.5 rounded-full {{ $sMeta['badge'] }}">
                    {{ $stageCounts[$sKey] ?? 0 }}
                </span>
            </div>
            <div class="mt-2">
                <div class="text-xs font-black text-slate-800 truncate">{{ $sMeta['title'] }}</div>
                <div class="text-[10px] text-slate-500 truncate">{{ $sMeta['desc'] }}</div>
            </div>
        </a>
        @endforeach
    </div>

    <!-- FILTER BAR -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
        <form method="GET" action="{{ route('calendar.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="hidden" name="view" value="{{ $viewMode }}">

            <!-- Client Filter -->
            <div class="flex-1 min-w-[160px]">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Client</label>
                <select name="client_id" onchange="this.form.submit()" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Clients</option>
                    @foreach($clients as $c)
                    <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Deliverable Format / Post Type Filter -->
            <div class="w-40">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Deliverable Format</label>
                <select name="post_type" onchange="this.form.submit()" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Formats</option>
                    @foreach(\App\Models\ContentPost::FORMATS as $fKey => $fMeta)
                    <option value="{{ $fKey }}" {{ request('post_type') == $fKey ? 'selected' : '' }}>{{ $fMeta['label'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Platform Filter -->
            <div class="w-36">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Platform</label>
                <select name="platform" onchange="this.form.submit()" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Platforms</option>
                    @foreach(\App\Models\ContentPost::PLATFORMS as $pKey => $pMeta)
                    <option value="{{ $pKey }}" {{ request('platform') == $pKey ? 'selected' : '' }}>{{ $pMeta['label'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Stage Filter -->
            <div class="w-40">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Stage</label>
                <select name="status" onchange="this.form.submit()" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Stages</option>
                    @foreach(\App\Models\ContentPost::STAGES as $stKey => $stMeta)
                    <option value="{{ $stKey }}" {{ request('status') == $stKey ? 'selected' : '' }}>{{ $stMeta['label'] }}</option>
                    @endforeach
                </select>
            </div>

            @if($viewMode === 'calendar')
            <!-- Month & Year Navigation -->
            @php
                $currentDt = \Carbon\Carbon::createFromDate($year, $month, 1);
                $prevDt    = (clone $currentDt)->subMonth();
                $nextDt    = (clone $currentDt)->addMonth();
            @endphp
            <div class="flex items-center gap-1.5 self-end pb-0.5">
                <a href="{{ request()->fullUrlWithQuery(['month' => $prevDt->format('m'), 'year' => $prevDt->format('Y')]) }}"
                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-bold transition-all" title="Previous Month">
                    <i class="fa fa-chevron-left"></i>
                </a>
                <span class="px-3 py-1.5 bg-slate-900 text-white rounded-xl text-xs font-bold font-mono">
                    {{ $currentDt->format('F Y') }}
                </span>
                <a href="{{ request()->fullUrlWithQuery(['month' => $nextDt->format('m'), 'year' => $nextDt->format('Y')]) }}"
                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-bold transition-all" title="Next Month">
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
            @endif

            <!-- Clear Filter Button -->
            @if(request('client_id') || request('post_type') || request('platform') || request('status'))
            <div class="self-end pb-0.5">
                <a href="{{ route('calendar.index', ['view' => $viewMode]) }}" class="px-3 py-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold flex items-center gap-1">
                    <i class="fa fa-xmark"></i> Clear
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- ========================================== -->
    <!-- VIEW MODE 1: CALENDAR MONTH GRID          -->
    <!-- ========================================== -->
    @if($viewMode === 'calendar')
    @php
        $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $daysInMonth  = $startOfMonth->daysInMonth;
        $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.

        // Group posts by date string 'Y-m-d'
        $postsByDay = $posts->groupBy(function($item) {
            return $item->date ? $item->date->format('Y-m-d') : '';
        });
    @endphp

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <!-- Calendar Days Header -->
        <div class="grid grid-cols-7 border-b border-slate-200 text-center bg-slate-50/80 font-bold text-xs uppercase tracking-wider text-slate-500 py-3">
            <div>Sun</div>
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div>Sat</div>
        </div>

        <!-- Calendar Days Cells -->
        <div class="grid grid-cols-7 auto-rows-fr divide-x divide-y divide-slate-100">
            <!-- Blank cells before month starts -->
            @for($i = 0; $i < $startDayOfWeek; $i++)
            <div class="min-h-[120px] bg-slate-50/40 p-2 text-slate-300 text-xs font-mono"></div>
            @endfor

            <!-- Month days -->
            @for($day = 1; $day <= $daysInMonth; $day++)
            @php
                $currentDayString = \Carbon\Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
                $dayPosts = $postsByDay->get($currentDayString, collect());
                $isToday = $currentDayString === now()->format('Y-m-d');
            @endphp
            <div class="min-h-[130px] p-2 hover:bg-slate-50/70 transition-all flex flex-col justify-between group {{ $isToday ? 'bg-amber-50/30 ring-1 ring-amber-400/50 inset-0' : '' }}">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold font-mono px-1.5 py-0.5 rounded-lg {{ $isToday ? 'bg-amber-500 text-white font-extrabold' : 'text-slate-700' }}">
                            {{ $day }}
                        </span>
                        <button onclick="openCreateModal('{{ $currentDayString }}')" class="opacity-0 group-hover:opacity-100 text-[10px] text-purple-600 hover:text-purple-800 p-1 font-bold transition-all" title="Add item for this date">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>

                    <!-- Post Cards in Day Cell -->
                    <div class="space-y-1.5">
                        @foreach($dayPosts as $post)
                        @php
                            $fmt = $post->format_info;
                            $stg = $post->stage_info;
                            $plf = $post->platform_info;
                        @endphp
                        <div onclick="openEditModal({{ json_encode($post) }})"
                             class="p-1.5 rounded-xl border border-slate-200/90 bg-white hover:border-purple-300 hover:shadow-xs transition-all cursor-pointer">
                            <div class="flex items-center justify-between gap-1 mb-1">
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $fmt['badge'] }} flex items-center gap-1">
                                    <i class="fa {{ $fmt['icon'] }} text-[8px]"></i>
                                    {{ $fmt['label'] }}
                                </span>
                                <i class="{{ $plf['icon'] }} text-[11px]" style="color: {{ $plf['color'] }};" title="{{ $plf['label'] }}"></i>
                            </div>
                            <div class="text-[11px] font-bold text-slate-900 line-clamp-1 leading-snug">
                                {{ $post->title }}
                            </div>
                            <div class="flex items-center justify-between mt-1 text-[9px] text-slate-500 font-medium">
                                <span class="truncate max-w-[70px] text-slate-600 font-semibold">{{ $post->client->name ?? 'Client' }}</span>
                                <span class="px-1.5 py-0.2 rounded-full font-bold uppercase tracking-wider text-[8px] bg-{{ $stg['color'] }}-100 text-{{ $stg['color'] }}-800">
                                    {{ $stg['label'] }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                @if($dayPosts->count() > 0)
                <div class="mt-1 text-right text-[9px] font-bold text-slate-400">
                    {{ $dayPosts->count() }} {{ \Illuminate\Support\Str::plural('item', $dayPosts->count()) }}
                </div>
                @endif
            </div>
            @endfor

            <!-- Trailing blank cells to complete grid -->
            @php
                $totalCells = $startDayOfWeek + $daysInMonth;
                $remainderCells = (7 - ($totalCells % 7)) % 7;
            @endphp
            @for($i = 0; $i < $remainderCells; $i++)
            <div class="min-h-[120px] bg-slate-50/40 p-2 text-slate-300 text-xs font-mono"></div>
            @endfor
        </div>
    </div>
    @endif

    <!-- ========================================== -->
    <!-- VIEW MODE 2: 6-STAGE PIPELINE (KANBAN)    -->
    <!-- ========================================== -->
    @if($viewMode === 'pipeline')
    @php
        $pipelineStages = [
            'draft'           => ['label' => '1. Draft', 'desc' => 'Brief & Ideation', 'border' => 'border-slate-300', 'bg' => 'bg-slate-100/80'],
            'internal_review' => ['label' => '2. Internal Review', 'desc' => 'Creative Lead QA', 'border' => 'border-indigo-300', 'bg' => 'bg-indigo-50/60'],
            'client_approval' => ['label' => '3. Client Approval', 'desc' => 'Sent to Client', 'border' => 'border-blue-300', 'bg' => 'bg-blue-50/60'],
            'revision'        => ['label' => '4. Revision Needed', 'desc' => 'Fixes in Progress', 'border' => 'border-amber-300', 'bg' => 'bg-amber-50/60'],
            'approved'        => ['label' => '5. Approved', 'desc' => 'Ready to Publish', 'border' => 'border-emerald-300', 'bg' => 'bg-emerald-50/60'],
            'published'       => ['label' => '6. Published', 'desc' => 'Live on Socials', 'border' => 'border-teal-300', 'bg' => 'bg-teal-50/60'],
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3.5 items-start">
        @foreach($pipelineStages as $stgKey => $stgMeta)
        @php
            $stagePosts = $posts->where('status', $stgKey);
        @endphp
        <div class="bg-white rounded-2xl border {{ $stgMeta['border'] }} p-3 shadow-xs flex flex-col max-h-[820px]">
            <!-- Column Header -->
            <div class="p-2.5 rounded-xl {{ $stgMeta['bg'] }} mb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black text-slate-900">{{ $stgMeta['label'] }}</h3>
                    <p class="text-[10px] text-slate-500 font-medium">{{ $stgMeta['desc'] }}</p>
                </div>
                <span class="w-6 h-6 rounded-full bg-white text-slate-900 font-mono font-bold text-xs flex items-center justify-center shadow-xs">
                    {{ $stagePosts->count() }}
                </span>
            </div>

            <!-- Posts List in Column -->
            <div class="space-y-2.5 overflow-y-auto pr-1 flex-1">
                @forelse($stagePosts as $post)
                @php
                    $fmt = $post->format_info;
                    $plf = $post->platform_info;
                @endphp
                <div class="p-3 rounded-xl border border-slate-200/90 bg-white hover:border-purple-300 hover:shadow-md transition-all space-y-2">
                    <!-- Format & Platform -->
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-md {{ $fmt['badge'] }} flex items-center gap-1">
                            <i class="fa {{ $fmt['icon'] }}"></i>
                            {{ $fmt['label'] }}
                        </span>
                        <div class="flex items-center gap-1.5 text-xs">
                            <i class="{{ $plf['icon'] }}" style="color: {{ $plf['color'] }};" title="{{ $plf['label'] }}"></i>
                            <span class="text-[10px] font-bold text-slate-400 font-mono">{{ $post->publish_time ?: 'All-day' }}</span>
                        </div>
                    </div>

                    <!-- Title & Client -->
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-900 leading-snug cursor-pointer hover:text-purple-600 transition-colors" onclick="openEditModal({{ json_encode($post) }})">
                            {{ $post->title }}
                        </h4>
                        <div class="text-[10px] font-semibold text-slate-500 mt-0.5 flex items-center gap-1">
                            <i class="fa fa-building text-[9px] text-slate-400"></i>
                            <span>{{ $post->client->name ?? 'Unassigned Client' }}</span>
                        </div>
                    </div>

                    <!-- Date & Assignee -->
                    <div class="pt-1 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-500">
                        <div class="flex items-center gap-1 font-mono">
                            <i class="fa fa-calendar-day text-[9px]"></i>
                            <span>{{ $post->date ? $post->date->format('M d, Y') : 'No Date' }}</span>
                        </div>
                        <div class="flex items-center gap-1 font-semibold text-slate-700 truncate max-w-[80px]" title="Assigned to {{ $post->assignedTo->name ?? 'Unassigned' }}">
                            <i class="fa fa-user text-[9px] text-slate-400"></i>
                            <span class="truncate">{{ $post->assignedTo->name ?? 'Unassigned' }}</span>
                        </div>
                    </div>

                    <!-- Links & Feedback indicators -->
                    @if($post->creative_url || $post->client_feedback || $post->caption)
                    <div class="flex flex-wrap gap-1 text-[9px]">
                        @if($post->creative_url)
                        <a href="{{ $post->creative_url }}" target="_blank" class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold flex items-center gap-1">
                            <i class="fa fa-arrow-up-right-from-square text-[8px]"></i> Asset Link
                        </a>
                        @endif
                        @if($post->client_feedback)
                        <span class="px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 font-bold flex items-center gap-1" title="{{ $post->client_feedback }}">
                            <i class="fa fa-comment-dots text-[8px]"></i> Feedback
                        </span>
                        @endif
                    </div>
                    @endif

                    <!-- Stage Transition Action Buttons -->
                    <div class="pt-1.5 border-t border-slate-100 flex items-center justify-between gap-1">
                        <button onclick="openStageModal({{ $post->id }}, '{{ $post->status }}', '{{ addslashes($post->title) }}')" class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold flex items-center gap-1 transition-all">
                            <i class="fa fa-arrow-right-arrow-left text-[9px]"></i> Move Stage
                        </button>
                        <button onclick="openEditModal({{ json_encode($post) }})" class="p-1 text-slate-400 hover:text-slate-700 transition-colors" title="Edit Full Details">
                            <i class="fa fa-pen-to-square text-xs"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-slate-400 text-xs font-medium">
                    No items in this stage
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- ========================================== -->
    <!-- VIEW MODE 3: LIST TABLE VIEW               -->
    <!-- ========================================== -->
    @if($viewMode === 'list')
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 font-bold uppercase tracking-wider text-slate-500 text-[10px]">
                        <th class="py-3.5 px-4">Deliverable Title</th>
                        <th class="py-3.5 px-4">Client</th>
                        <th class="py-3.5 px-3">Format</th>
                        <th class="py-3.5 px-3">Platform</th>
                        <th class="py-3.5 px-4">Target Date</th>
                        <th class="py-3.5 px-4">Assignee</th>
                        <th class="py-3.5 px-4">Stage</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($posts as $post)
                    @php
                        $fmt = $post->format_info;
                        $stg = $post->stage_info;
                        $plf = $post->platform_info;
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-all">
                        <td class="py-3 px-4 font-bold text-slate-900">
                            <div class="flex items-center gap-2">
                                <span>{{ $post->title }}</span>
                                @if($post->creative_url)
                                <a href="{{ $post->creative_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-800" title="Open Asset Link">
                                    <i class="fa fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4 text-slate-700 font-semibold">{{ $post->client->name ?? '—' }}</td>
                        <td class="py-3 px-3">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $fmt['badge'] }}">
                                <i class="fa {{ $fmt['icon'] }} mr-0.5"></i> {{ $fmt['label'] }}
                            </span>
                        </td>
                        <td class="py-3 px-3">
                            <div class="flex items-center gap-1.5 font-semibold text-slate-700">
                                <i class="{{ $plf['icon'] }}" style="color: {{ $plf['color'] }};"></i>
                                <span>{{ $plf['label'] }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-600">
                            {{ $post->date ? $post->date->format('Y-m-d') : '—' }}
                            @if($post->publish_time)
                            <span class="text-slate-400">({{ $post->publish_time }})</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-slate-700">{{ $post->assignedTo->name ?? 'Unassigned' }}</td>
                        <td class="py-3 px-4">
                            <button onclick="openStageModal({{ $post->id }}, '{{ $post->status }}', '{{ addslashes($post->title) }}')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-{{ $stg['color'] }}-100 text-{{ $stg['color'] }}-800 hover:shadow-xs transition-all">
                                <i class="fa {{ $stg['icon'] }}"></i> {{ $stg['label'] }}
                            </button>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal({{ json_encode($post) }})" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100">
                                    <i class="fa fa-pen-to-square"></i>
                                </button>
                                <form method="POST" action="{{ route('calendar.destroy', $post) }}" onsubmit="return confirm('Delete this content item?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400">No content items found matching current filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

<!-- ======================================================== -->
<!-- MODAL: CREATE CONTENT ITEM                                -->
<!-- ======================================================== -->
<div id="createModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm">
                    <i class="fa fa-plus"></i>
                </div>
                <h3 class="text-base font-black text-slate-900">Schedule Content Deliverable</h3>
            </div>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
        </div>

        <form method="POST" action="{{ route('calendar.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Deliverable Title / Campaign Headline *</label>
                <input type="text" name="title" required placeholder="e.g. Summer Promo Carousel Post #3" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Client *</label>
                    <select name="client_id" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Select Client...</option>
                        @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Assign Creative Staff</label>
                    <select name="assigned_to" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Unassigned</option>
                        @foreach($teamMembers as $m)
                        <option value="{{ $m->id }}">{{ $m->name }} ({{ ucfirst($m->role) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Deliverable Format *</label>
                    <select name="post_type" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach(\App\Models\ContentPost::FORMATS as $fKey => $fMeta)
                        <option value="{{ $fKey }}">{{ $fMeta['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Social Channel / Platform *</label>
                    <select name="platform" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach(\App\Models\ContentPost::PLATFORMS as $pKey => $pMeta)
                        <option value="{{ $pKey }}">{{ $pMeta['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Publish Date *</label>
                    <input type="date" name="date" id="createDate" required value="{{ now()->format('Y-m-d') }}" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Publish Time (Optional)</label>
                    <input type="time" name="publish_time" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Initial Stage *</label>
                    <select name="status" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach(\App\Models\ContentPost::STAGES as $stKey => $stMeta)
                        <option value="{{ $stKey }}" {{ $stKey === 'draft' ? 'selected' : '' }}>{{ $stMeta['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Creative Asset Link (Figma, Canva, Google Drive)</label>
                <input type="url" name="creative_url" placeholder="https://www.canva.com/design/... or Google Drive URL" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Ad Copy / Caption / Hashtags</label>
                <textarea name="caption" rows="3" placeholder="Write post caption, hook and hashtags..." class="w-full text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Creative Brief / Designer Instructions</label>
                <textarea name="brief" rows="2" placeholder="Visual style, font, brand colors, reference images..." class="w-full text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-xs font-black shadow-md shadow-purple-500/20 transition-all">
                    Schedule Deliverable
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: EDIT CONTENT ITEM                                  -->
<!-- ======================================================== -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                    <i class="fa fa-pen-to-square"></i>
                </div>
                <h3 class="text-base font-black text-slate-900">Edit Deliverable</h3>
            </div>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
        </div>

        <form id="editForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Deliverable Title / Campaign Headline *</label>
                <input type="text" name="title" id="editTitle" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Client *</label>
                    <select name="client_id" id="editClientId" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                        @foreach($clients as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Assign Creative Staff</label>
                    <select name="assigned_to" id="editAssignedTo" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                        <option value="">Unassigned</option>
                        @foreach($teamMembers as $m)
                        <option value="{{ $m->id }}">{{ $m->name }} ({{ ucfirst($m->role) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Deliverable Format *</label>
                    <select name="post_type" id="editPostType" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                        @foreach(\App\Models\ContentPost::FORMATS as $fKey => $fMeta)
                        <option value="{{ $fKey }}">{{ $fMeta['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Platform *</label>
                    <select name="platform" id="editPlatform" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                        @foreach(\App\Models\ContentPost::PLATFORMS as $pKey => $pMeta)
                        <option value="{{ $pKey }}">{{ $pMeta['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Publish Date *</label>
                    <input type="date" name="date" id="editDate" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Publish Time</label>
                    <input type="time" name="publish_time" id="editPublishTime" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Stage (6-Stage Flow) *</label>
                    <select name="status" id="editStatus" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
                        @foreach(\App\Models\ContentPost::STAGES as $stKey => $stMeta)
                        <option value="{{ $stKey }}">{{ $stMeta['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Asset Link (Canva, Figma, Google Drive)</label>
                <input type="url" name="creative_url" id="editCreativeUrl" placeholder="https://..." class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800">
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Post Caption / Copy</label>
                <textarea name="caption" id="editCaption" rows="3" class="w-full text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Internal Feedback (QA / Lead)</label>
                    <textarea name="internal_feedback" id="editInternalFeedback" rows="2" placeholder="Internal revision notes..." class="w-full text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800"></textarea>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Client Feedback & Revisions</label>
                    <textarea name="client_feedback" id="editClientFeedback" rows="2" placeholder="Client requested changes..." class="w-full text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black shadow-md shadow-indigo-500/20 transition-all">
                    Update Deliverable
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: QUICK STAGE UPDATE MODAL                           -->
<!-- ======================================================== -->
<div id="stageModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-black text-slate-900">Update Lifecycle Stage</h3>
                <p id="stageModalTitle" class="text-xs text-slate-500 font-semibold truncate max-w-[280px]"></p>
            </div>
            <button onclick="closeStageModal()" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
        </div>

        <form id="stageForm" method="POST" action="" class="space-y-4">
            @csrf

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-2">Select Target Stage</label>
                <div class="space-y-2">
                    @foreach(\App\Models\ContentPost::STAGES as $stKey => $stMeta)
                    <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-all">
                        <div class="flex items-center gap-2.5">
                            <input type="radio" name="status" value="{{ $stKey }}" class="text-purple-600 focus:ring-purple-500">
                            <span class="text-xs font-bold text-slate-800">{{ $stMeta['label'] }}</span>
                        </div>
                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full bg-{{ $stMeta['color'] }}-100 text-{{ $stMeta['color'] }}-800">
                            Stage
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Feedback / Outcome Notes (Optional)</label>
                <textarea name="client_feedback" rows="2" placeholder="e.g. Approved with slight color adjustment..." class="w-full text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeStageModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-black shadow-md shadow-purple-500/20 transition-all">
                    Confirm Transition
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal(date = '') {
    if (date) {
        document.getElementById('createDate').value = date;
    }
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openEditModal(post) {
    document.getElementById('editForm').action = '/calendar/' + post.id;
    document.getElementById('editTitle').value = post.title || '';
    document.getElementById('editClientId').value = post.client_id || '';
    document.getElementById('editAssignedTo').value = post.assigned_to || '';
    document.getElementById('editPostType').value = post.post_type || 'design';
    document.getElementById('editPlatform').value = post.platform || 'facebook';
    document.getElementById('editDate').value = post.date ? post.date.substring(0, 10) : '';
    document.getElementById('editPublishTime').value = post.publish_time || '';
    document.getElementById('editStatus').value = post.status || 'draft';
    document.getElementById('editCreativeUrl').value = post.creative_url || '';
    document.getElementById('editCaption').value = post.caption || '';
    document.getElementById('editInternalFeedback').value = post.internal_feedback || '';
    document.getElementById('editClientFeedback').value = post.client_feedback || '';
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function openStageModal(postId, currentStage, postTitle) {
    document.getElementById('stageForm').action = '/calendar/' + postId + '/stage';
    document.getElementById('stageModalTitle').innerText = postTitle;
    const radios = document.querySelectorAll('#stageForm input[name="status"]');
    radios.forEach(r => {
        r.checked = (r.value === currentStage);
    });
    document.getElementById('stageModal').classList.remove('hidden');
}

function closeStageModal() {
    document.getElementById('stageModal').classList.add('hidden');
}
</script>
@endsection
