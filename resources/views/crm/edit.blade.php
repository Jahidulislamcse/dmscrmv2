@extends('layouts.app')

@section('title', 'Edit Prospect Lead — ' . $lead->name)
@section('header_title', 'Edit Lead Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Edit Prospect Lead: {{ $lead->name }}</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Update contact information, pipeline status, or assigned sales rep</p>
        </div>
        <a href="{{ route('crm.show', $lead->id) }}" class="px-3.5 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-all flex items-center gap-2">
            <i class="fa fa-arrow-left"></i> Cancel & Return
        </a>
    </div>

    <!-- Form Card -->
    <form action="{{ route('crm.update', $lead->id) }}" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8 space-y-8">
        @csrf
        @method('PUT')

        <!-- Contact & Company Info -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold text-xs">
                    <i class="fa fa-user"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-sm">Lead & Prospect Contact Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Contact Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name', $lead->name) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Company / Organization</label>
                    <input type="text" name="company" value="{{ old('company', $lead->company) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $lead->email) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Location / Address</label>
                    <input type="text" name="location" value="{{ old('location', $lead->location) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                </div>
            </div>
        </div>

        <!-- Pipeline Assignment & Settings -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center font-bold text-xs">
                    <i class="fa fa-filter"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-sm">Pipeline & Assignment Settings</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if(auth()->user()->isAdmin())
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pipeline Stage <span class="text-rose-500">*</span></label>
                    <select name="stage_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        @foreach($stages as $stage)
                            <option value="{{ $stage->id }}" {{ old('stage_id', $lead->stage_id) == $stage->id ? 'selected' : '' }}>
                                {{ $stage->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @else
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pipeline Stage</label>
                    <input type="hidden" name="stage_id" value="{{ $lead->stage_id }}">
                    <div class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 flex items-center justify-between cursor-not-allowed" title="Sales representatives have view-only access to stages">
                        <span>{{ $lead->stage->name ?? 'Stage' }}</span>
                        <i class="fa fa-lock text-slate-400"></i>
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Assigned Sales Representative <span class="text-rose-500">*</span></label>
                    <select name="assigned_to" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        @foreach($salesReps as $rep)
                            <option value="{{ $rep->id }}" {{ old('assigned_to', $lead->assigned_to) == $rep->id ? 'selected' : '' }}>
                                {{ $rep->name }} ({{ ucfirst($rep->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Lead Source <span class="text-rose-500">*</span></label>
                    <select name="source" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        <option value="Facebook" {{ old('source', $lead->source) == 'Facebook' ? 'selected' : '' }}>Facebook Ads / Page</option>
                        <option value="Website" {{ old('source', $lead->source) == 'Website' ? 'selected' : '' }}>Website Form</option>
                        <option value="Referral" {{ old('source', $lead->source) == 'Referral' ? 'selected' : '' }}>Client Referral</option>
                        <option value="WhatsApp" {{ old('source', $lead->source) == 'WhatsApp' ? 'selected' : '' }}>WhatsApp Inquiry</option>
                        <option value="Cold Call" {{ old('source', $lead->source) == 'Cold Call' ? 'selected' : '' }}>Cold Call / Outreach</option>
                        <option value="Google Ads" {{ old('source', $lead->source) == 'Google Ads' ? 'selected' : '' }}>Google Search Ads</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Estimated Budget (৳)</label>
                    <input type="number" step="0.01" min="0" name="budget" value="{{ old('budget', $lead->budget) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Next Follow-up Date</label>
                    <input type="date" name="next_followup" value="{{ old('next_followup', $lead->next_followup ? $lead->next_followup->format('Y-m-d') : '') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Discussion & Activity Notes</label>
            <textarea name="notes" rows="3"
                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">{{ old('notes', $lead->notes) }}</textarea>
        </div>

        <!-- Submit Buttons -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('crm.show', $lead->id) }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition-all">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition-all">
                <i class="fa fa-save mr-1.5"></i> Update Lead Profile
            </button>
        </div>
    </form>
</div>
@endsection
