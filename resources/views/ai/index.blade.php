@extends('layouts.app')

@section('title', 'AI Brief & Copy Assistant — DMS CRM')
@section('header_title', 'AI Brief & Copy Generator')

@section('content')
<div class="space-y-6" x-data="aiGenerator()">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-purple-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 border border-purple-900/40">
        <div class="space-y-2 max-w-2xl relative z-10">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-purple-500/20 text-purple-300 border border-purple-500/30">
                    <i class="fa fa-wand-magic-sparkles mr-1"></i> LLM Assistant & GCal Sync
                </span>
                <span class="text-xs text-slate-400 font-mono font-bold">Claude 3.5 Sonnet / Agency Engine</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black tracking-tight">AI Copy & Creative Brief Generator</h2>
            <p class="text-xs sm:text-sm text-slate-300 font-medium leading-relaxed">
                Generate high-converting ad copy, 15-second viral reel scripts, and structured designer briefs in English and authentic Unicode Bengali.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 relative z-10">
            <!-- Download .ICS Calendar Feed -->
            <a href="{{ route('calendar.export-ics') }}" class="px-4 py-2.5 bg-slate-800/80 hover:bg-slate-700/80 text-white font-bold text-xs rounded-xl border border-slate-700 shadow-sm transition-all flex items-center gap-2">
                <i class="fa fa-calendar-arrow-down text-purple-400"></i> Export Schedule (.ics)
            </a>
        </div>
    </div>

    <!-- Main Generator Grid: Input Form + Output Results -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- LEFT COLUMN: Generator Controls (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <i class="fa fa-sliders text-indigo-600"></i> Campaign Brief Settings
                    </h3>
                    <span class="text-[10px] uppercase tracking-wider font-extrabold text-slate-400">Prompt Config</span>
                </div>

                <form @submit.prevent="generateConcepts()" class="space-y-4">
                    <!-- 1. Client Selection -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Client Brand</label>
                        <select x-model="form.client_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-purple-500 cursor-pointer">
                            <option value="">— Generic / New Campaign —</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->company ?? 'Brand' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Target Platform & Post Format -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Platform</label>
                            <select x-model="form.platform" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                <option value="Facebook">Facebook</option>
                                <option value="Instagram">Instagram</option>
                                <option value="LinkedIn">LinkedIn</option>
                                <option value="TikTok">TikTok</option>
                                <option value="Google Ads">Google Ads</option>
                                <option value="YouTube Shorts">YouTube Shorts</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Format</label>
                            <select x-model="form.format" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                <option value="Image Feed Post">Single Image Ad</option>
                                <option value="Reel / TikTok Script">15s Reel Script</option>
                                <option value="Carousel Post">Carousel (10 Slides)</option>
                                <option value="Ad Copy & Headlines">High-ROAS Ad Copy</option>
                                <option value="Design Brief">Creative Design Brief</option>
                            </select>
                        </div>
                    </div>

                    <!-- 3. Campaign Topic & Core Offer -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Offer / Product / Angle <span class="text-rose-500">*</span>
                        </label>
                        <textarea x-model="form.topic" rows="3" required placeholder="e.g. Winter collection launch with flat 25% discount on outerwear, express 2-day delivery across Dhaka and Chittagong..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-purple-500"></textarea>
                    </div>

                    <!-- 4. Tone of Voice & Language -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tone</label>
                            <select x-model="form.tone" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                <option value="High Energy & Urgent">High Energy / Urgent</option>
                                <option value="Luxury & Sophisticated">Luxury & Elegant</option>
                                <option value="Casual & Relatable">Casual & Friendly</option>
                                <option value="Corporate & Trustworthy">Corporate / B2B</option>
                                <option value="Festive & Emotional">Festive & Emotional</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Language</label>
                            <select x-model="form.language" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                <option value="English">English</option>
                                <option value="Bengali Unicode">বাংলা (Bengali Unicode)</option>
                                <option value="Banglish">Banglish (Bilingual)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                :disabled="loading"
                                class="w-full py-3 bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-600 hover:from-purple-700 hover:to-indigo-700 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-purple-600/20 transition-all flex items-center justify-center gap-2 disabled:opacity-50">
                            <template x-if="!loading">
                                <span class="flex items-center gap-2">
                                    <i class="fa fa-wand-magic-sparkles"></i> Generate Creative Angles
                                </span>
                            </template>
                            <template x-if="loading">
                                <span class="flex items-center gap-2">
                                    <i class="fa fa-circle-notch animate-spin"></i> Generating AI Concepts...
                                </span>
                            </template>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RIGHT COLUMN: Generated Output Results (7 cols) -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Empty / Initial State -->
            <template x-if="!result && !loading">
                <div class="bg-white rounded-3xl p-12 border border-slate-200/80 shadow-sm text-center space-y-4">
                    <div class="w-16 h-16 rounded-3xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto text-2xl shadow-sm border border-purple-100">
                        <i class="fa fa-wand-magic-sparkles"></i>
                    </div>
                    <div class="max-w-md mx-auto space-y-1">
                        <h4 class="font-black text-slate-900 text-base">Ready to Generate Campaign Angles</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Select a client, pick your social channel, configure your target tone, and click "Generate Creative Angles" to produce 3 high-converting copy variations and designer briefs.
                        </p>
                    </div>
                </div>
            </template>

            <!-- Loading Skeleton -->
            <template x-if="loading">
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm space-y-6 animate-pulse">
                    <div class="h-5 bg-slate-200 rounded-lg w-1/3"></div>
                    <div class="space-y-3">
                        <div class="h-4 bg-slate-100 rounded w-full"></div>
                        <div class="h-4 bg-slate-100 rounded w-5/6"></div>
                        <div class="h-4 bg-slate-100 rounded w-4/6"></div>
                    </div>
                    <div class="h-28 bg-slate-50 rounded-2xl border border-slate-100"></div>
                </div>
            </template>

            <!-- Generated Concepts Output -->
            <template x-if="result">
                <div class="space-y-5">
                    <!-- Title Header -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-extrabold text-purple-600 uppercase tracking-wider block">Generated Campaign Output</span>
                            <h4 class="font-black text-slate-900 text-sm" x-text="result.headline"></h4>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">
                            <i class="fa fa-check text-emerald-600"></i> 3 Concepts Ready
                        </span>
                    </div>

                    <!-- Variations Cards Loop -->
                    <template x-for="(variation, idx) in result.variations" :key="idx">
                        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:border-purple-300 transition-all space-y-3.5">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-purple-50 text-purple-700 border border-purple-100" x-text="variation.title"></span>
                                <button type="button" 
                                        @click="copyText(variation.hook + '\n\n' + variation.body + '\n\n' + variation.cta, idx)"
                                        class="px-2.5 py-1 text-[11px] font-bold rounded-lg border transition-all flex items-center gap-1.5"
                                        :class="copiedIdx === idx ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'bg-slate-50 hover:bg-slate-100 border-slate-200 text-slate-700'">
                                    <i :class="copiedIdx === idx ? 'fa fa-check text-emerald-600' : 'fa fa-copy text-slate-400'"></i>
                                    <span x-text="copiedIdx === idx ? 'Copied!' : 'Copy Copy'"></span>
                                </button>
                            </div>

                            <!-- Hook -->
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Primary Hook</span>
                                <p class="font-black text-slate-900 text-xs sm:text-sm leading-snug" x-text="variation.hook"></p>
                            </div>

                            <!-- Body Caption -->
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Caption Body</span>
                                <p class="text-xs text-slate-700 font-medium whitespace-pre-line leading-relaxed" x-text="variation.body"></p>
                            </div>

                            <!-- CTA Button Pill -->
                            <div class="p-2.5 bg-purple-50/60 rounded-xl border border-purple-100/80 flex items-center gap-2">
                                <span class="text-[10px] font-extrabold text-purple-700 uppercase">CTA:</span>
                                <span class="text-xs font-black text-slate-900" x-text="variation.cta"></span>
                            </div>

                            <!-- Hashtags -->
                            <div class="flex flex-wrap items-center gap-1.5 pt-1">
                                <template x-for="tag in variation.hashtags" :key="tag">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 hover:text-purple-600 cursor-pointer" x-text="tag"></span>
                                </template>
                            </div>

                            <!-- Designer Visual Direction -->
                            <div class="pt-3 border-t border-slate-100 flex items-start gap-2 text-xs text-slate-600">
                                <i class="fa fa-palette text-amber-500 mt-0.5"></i>
                                <div>
                                    <strong class="text-slate-800">Visual Art Direction:</strong>
                                    <span class="italic text-slate-600 ml-1" x-text="variation.visual_direction"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Designer Brief Box -->
                    <template x-if="result.designer_brief">
                        <div class="bg-gradient-to-tr from-slate-900 to-indigo-950 text-white p-5 rounded-2xl shadow-lg border border-indigo-900/40 space-y-3">
                            <div class="flex items-center gap-2 text-xs font-black text-purple-300 uppercase tracking-wider">
                                <i class="fa fa-compass-drafting"></i> Production & Design Specs
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                                <div class="p-2.5 bg-slate-800/80 rounded-xl border border-slate-700">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Aspect Ratio</span>
                                    <span class="font-bold text-white text-xs mt-0.5 block" x-text="result.designer_brief.aspect_ratio"></span>
                                </div>
                                <div class="p-2.5 bg-slate-800/80 rounded-xl border border-slate-700 sm:col-span-2">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Key Art Elements</span>
                                    <span class="font-medium text-slate-200 text-xs mt-0.5 block" x-text="result.designer_brief.key_elements"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

        </div>
    </div>

    <!-- BOTTOM SECTION: Google Calendar Sync & Production Agenda -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i class="fa fa-calendar-days text-indigo-600"></i> Google Calendar & Production Schedule Sync
                </h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    Sync client strategy meetings and content release schedules directly with your Google Calendar or Outlook.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('calendar.export-ics') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-2">
                    <i class="fa fa-cloud-arrow-down"></i> Sync Full Schedule (.ics)
                </a>
            </div>
        </div>

        <!-- Upcoming Meetings & Content Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- 1. Upcoming Client Meetings -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa fa-handshake text-indigo-600"></i> Upcoming Strategy Meetings
                    </span>
                    <span class="text-[10px] font-bold text-slate-400">1-Click GCal Link</span>
                </div>

                <div class="space-y-2">
                    @forelse($upcomingMeetings as $meeting)
                    <div class="p-3 bg-white rounded-xl border border-slate-200/80 text-xs flex items-center justify-between gap-2 shadow-sm">
                        <div class="truncate">
                            <div class="font-bold text-slate-900 truncate">{{ $meeting->agenda ?: 'Client Meeting' }}</div>
                            <div class="text-[10px] text-slate-400 font-medium">
                                {{ $meeting->client->name ?? 'Client' }} • {{ $meeting->date->format('d M') }} at {{ $meeting->time ?? '10:00 AM' }}
                            </div>
                        </div>
                        @php
                            $gcalTitle = urlencode("DMS Strategy: " . ($meeting->client->name ?? 'Client') . " - " . ($meeting->agenda ?: 'Meeting'));
                            $dt = \Carbon\Carbon::parse($meeting->date . ' ' . ($meeting->time ?: '10:00:00'));
                            $dates = $dt->format('Ymd\THis') . '/' . $dt->copy()->addMinutes($meeting->duration ?: 60)->format('Ymd\THis');
                            $gcalUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$gcalTitle}&dates={$dates}&location=" . urlencode($meeting->location ?: 'DMS Conference Room');
                        @endphp
                        <a href="{{ $gcalUrl }}" target="_blank" rel="noopener" class="px-2.5 py-1 text-[10px] font-bold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg whitespace-nowrap transition-colors flex items-center gap-1">
                            <i class="fab fa-google text-[9px]"></i> Add to GCal
                        </a>
                    </div>
                    @empty
                    <div class="p-4 text-center text-slate-400 text-xs">No upcoming client meetings scheduled.</div>
                    @endforelse
                </div>
            </div>

            <!-- 2. Content Release Deliverables -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa fa-photo-film text-purple-600"></i> Scheduled Releases
                    </span>
                    <a href="{{ route('calendar.index') }}" class="text-[10px] font-bold text-indigo-600 hover:underline">
                        View Calendar Grid
                    </a>
                </div>

                <div class="space-y-2">
                    @forelse($upcomingPosts as $post)
                    <div class="p-3 bg-white rounded-xl border border-slate-200/80 text-xs flex items-center justify-between gap-2 shadow-sm">
                        <div class="truncate">
                            <div class="font-bold text-slate-900 truncate">{{ $post->title }}</div>
                            <div class="text-[10px] text-slate-400 font-medium">
                                {{ $post->client->name ?? 'Client' }} • <span class="capitalize text-purple-600 font-bold">{{ $post->platform }}</span> ({{ $post->post_type ?? 'Design' }})
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($post->date)->format('d M, Y') }}
                        </span>
                    </div>
                    @empty
                    <div class="p-4 text-center text-slate-400 text-xs">No scheduled content releases.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function aiGenerator() {
    return {
        form: {
            client_id: '',
            platform: 'Facebook',
            format: 'Image Feed Post',
            topic: '',
            tone: 'High Energy & Urgent',
            language: 'English'
        },
        loading: false,
        result: null,
        copiedIdx: null,

        async generateConcepts() {
            if (!this.form.topic.trim()) return;
            this.loading = true;
            this.result = null;

            try {
                const response = await fetch('{{ route("ai.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await response.json();
                if (data.success && data.data) {
                    this.result = data.data;
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred while generating concepts. Please try again.');
            } finally {
                this.loading = false;
            }
        },

        copyText(text, idx) {
            navigator.clipboard.writeText(text).then(() => {
                this.copiedIdx = idx;
                setTimeout(() => {
                    this.copiedIdx = null;
                }, 2500);
            });
        }
    }
}
</script>
@endsection
