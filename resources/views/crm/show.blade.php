@extends('layouts.app')

@section('title', 'Lead Details — ' . $lead->name)
@section('header_title', 'Prospect Lead Profile')

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="{ showConvertModal: false }">

    <!-- Top Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('crm.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ $lead->name }}</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-white shadow-sm" style="background-color: {{ $lead->stage->color ?? '#f59e0b' }}">
                        {{ $lead->stage->name ?? 'Stage' }}
                    </span>
                    @if($lead->converted_client_id)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                            <i class="fa fa-check-circle mr-1"></i> Converted to Client
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    @if($lead->company) Company: <span class="font-bold text-slate-700">{{ $lead->company }}</span> • @endif
                    Created {{ $lead->created_at->diffForHumans() }} by {{ $lead->createdBy->name ?? 'Admin' }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Proposal & Quotation PDF Link -->
            <a href="{{ route('crm.quotation', $lead->id) }}" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold text-xs shadow-md shadow-amber-500/20 flex items-center gap-2 transition-all">
                <i class="fa fa-file-invoice text-sm"></i>
                <span>Proposal & Quotation</span>
                @if($lead->quotation_status === 'approved')
                    <span class="w-2 h-2 rounded-full bg-emerald-300" title="Admin Approved"></span>
                @elseif($lead->quotation_status === 'pending_approval')
                    <span class="w-2 h-2 rounded-full bg-amber-300 animate-pulse" title="Pending Approval"></span>
                @endif
            </a>

            @if(!$lead->converted_client_id)
            <button @click="showConvertModal = true" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 flex items-center gap-2 transition-all">
                <i class="fa fa-clipboard-check"></i>
                <span>Convert to Requisition</span>
            </button>
            @endif

            <a href="{{ route('crm.edit', $lead->id) }}" class="px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition-all flex items-center gap-1.5">
                <i class="fa fa-edit text-amber-500"></i> Edit Lead
            </a>

            @if(auth()->user()->isAdmin())
            <form action="{{ route('crm.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3.5 py-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 font-bold text-xs hover:bg-rose-100 transition-all">
                    <i class="fa fa-trash-alt"></i>
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left Column: Lead Info & Requirements (7 cols) -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Card 1: Prospect Contact Details -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <i class="fa fa-id-card text-amber-500"></i> Contact Information
                    </h3>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 text-slate-600">
                        Source: {{ $lead->source }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Phone</span>
                        <span class="font-bold text-slate-800">{{ $lead->phone ?? 'Not provided' }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Email</span>
                        <span class="font-bold text-slate-800">{{ $lead->email ?? 'Not provided' }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Location / Address</span>
                        <span class="font-bold text-slate-800">{{ $lead->location ?? 'Not provided' }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Estimated Budget</span>
                        <span class="font-extrabold text-emerald-600 text-sm">৳{{ number_format($lead->budget, 2) }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Assigned Sales Rep</span>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-white text-[10px]" style="background-color: {{ $lead->assignedTo->color ?? '#f59e0b' }}">
                                {{ strtoupper(substr($lead->assignedTo->name ?? 'U', 0, 2)) }}
                            </div>
                            <span class="font-bold text-slate-800">{{ $lead->assignedTo->name ?? 'Unassigned' }}</span>
                        </div>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Next Follow-up Date</span>
                        <span class="font-bold text-amber-600 flex items-center gap-1 mt-1">
                            <i class="fa fa-calendar-alt text-[11px]"></i>
                            {{ $lead->next_followup ? $lead->next_followup->format('d M, Y') : 'None scheduled' }}
                        </span>
                    </div>
                </div>

                @if($lead->notes)
                <div class="pt-3 border-t border-slate-100">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Notes & Requirements Summary</span>
                    <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 font-medium whitespace-pre-line">{{ $lead->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Card 2: Requested Services & Requirements Breakdown -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <i class="fa fa-boxes-packing text-emerald-500"></i> Requested Services Breakdown
                    </h3>
                    <span class="text-xs font-extrabold text-slate-700">
                        Total Value: ৳{{ number_format($lead->requirements->sum(fn($r) => $r->qty * $r->unit_price), 2) }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-extrabold uppercase text-slate-400">
                                <th class="py-2.5 px-3">Service Name</th>
                                <th class="py-2.5 px-3 text-center">Qty</th>
                                <th class="py-2.5 px-3 text-right">Unit Price</th>
                                <th class="py-2.5 px-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($lead->requirements as $req)
                            <tr>
                                <td class="py-2.5 px-3 font-bold text-slate-800">{{ $req->service_name }}</td>
                                <td class="py-2.5 px-3 text-center font-semibold text-slate-600">{{ $req->qty }}</td>
                                <td class="py-2.5 px-3 text-right font-semibold text-slate-600">৳{{ number_format($req->unit_price, 2) }}</td>
                                <td class="py-2.5 px-3 text-right font-extrabold text-slate-900">৳{{ number_format($req->qty * $req->unit_price, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-slate-400 font-medium">No custom requirement items specified yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right Column: Stage Switcher & Timeline (5 cols) -->
        <div class="lg:col-span-5 space-y-6">

            <!-- Stage Advancement Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa fa-sliders text-blue-500"></i> Pipeline Stage
                </h3>

                @if(auth()->user()->isAdmin())
                <form action="{{ route('crm.update-stage', $lead->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <select name="stage_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                            @foreach($stages as $stage)
                                <option value="{{ $stage->id }}" {{ $lead->stage_id == $stage->id ? 'selected' : '' }}>
                                    {{ $stage->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2">
                        <i class="fa fa-sync-alt"></i> Update Pipeline Stage
                    </button>
                </form>
                @else
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-600">Current Stage:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold text-white shadow-sm" style="background-color: {{ $lead->stage->color ?? '#f59e0b' }}">
                            {{ $lead->stage->name ?? 'Stage' }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 font-medium flex items-center gap-1.5 pt-2 border-t border-slate-200/70">
                        <i class="fa fa-lock text-slate-400 text-xs"></i> <span>Sales representatives have <strong>view-only</strong> access to lead stages. Stage progression is managed by agency leadership.</span>
                    </p>
                </div>
                @endif
            </div>

            <!-- Activity & Timeline Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa fa-timeline text-purple-500"></i> Activity & Log History
                </h3>

                <!-- Add Note Form -->
                <form action="{{ route('crm.add-timeline', $lead->id) }}" method="POST" class="space-y-2">
                    @csrf
                    <textarea name="text" rows="2" placeholder="Log call notes, email reply, or status updates..." required
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500"></textarea>
                    <button type="submit" class="px-4 py-2 bg-amber-50 border border-amber-200 text-amber-700 hover:bg-amber-100 rounded-xl text-xs font-bold transition-all w-full">
                        <i class="fa fa-paper-plane mr-1"></i> Post Note to Timeline
                    </button>
                </form>

                <!-- Timeline Log Feed -->
                <div class="pt-3 border-t border-slate-100 space-y-4 max-h-[350px] overflow-y-auto pr-1">
                    @forelse($lead->timelines->sortByDesc('created_at') as $timeline)
                    <div class="flex items-start gap-3 text-xs">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-white text-[10px] flex-shrink-0 mt-0.5" style="background-color: {{ $timeline->user->color ?? '#94a3b8' }}">
                            {{ strtoupper(substr($timeline->user->name ?? 'U', 0, 2)) }}
                        </div>
                        <div class="flex-1 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-slate-900">{{ $timeline->user->name ?? 'System' }}</span>
                                <span class="text-[10px] text-slate-400 font-semibold">{{ $timeline->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-700 font-medium leading-relaxed">{{ $timeline->text }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-slate-400 text-xs py-4">No activity logged yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- Convert to Sales Requisition Modal -->
    <div x-show="showConvertModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full p-6 space-y-5" @click.outside="showConvertModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                    <i class="fa fa-clipboard-check text-emerald-500"></i> Convert Lead to Requisition
                </h3>
                <button @click="showConvertModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa fa-times text-lg"></i></button>
            </div>

            <p class="text-xs text-slate-600 font-medium">
                Converting this lead will automatically generate a <span class="font-bold text-slate-900">Sales Requisition</span> in the approval hub with all client details and requested service items.
            </p>

            <form action="{{ route('crm.convert', $lead->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Advance Amount Received (৳)</label>
                    <input type="number" step="0.01" min="0" name="advance_paid" value="0.00"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Billing Cycle</label>
                    <select name="billing_cycle" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        <option value="monthly">Monthly Recurring</option>
                        <option value="one-time">One Time Deal</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="showConvertModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 shadow-md shadow-emerald-500/20">
                        Confirm & Create Requisition
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
