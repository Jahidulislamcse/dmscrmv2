@extends('layouts.app')

@section('title', 'Proposal & Quotation — ' . $lead->name)
@section('header_title', 'Commercial Proposal & Quotation')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{ showEditModal: false, showRejectModal: false }">

    <!-- Top Action Bar (Hidden on Print) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
        <div class="flex items-center gap-2">
            <a href="{{ route('crm.show', $lead->id) }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all flex items-center gap-1.5 text-xs font-bold">
                <i class="fa fa-arrow-left"></i> Back to Lead Profile
            </a>
            <span class="text-xs text-slate-400">|</span>
            <span class="text-xs font-bold text-slate-600">Quotation Status:</span>
            @if($lead->quotation_status === 'approved')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                    <i class="fa fa-check-circle mr-1"></i> Approved by Admin
                </span>
            @elseif($lead->quotation_status === 'pending_approval')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300">
                    <i class="fa fa-clock mr-1"></i> Pending Admin Approval
                </span>
            @elseif($lead->quotation_status === 'rejected')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300">
                    <i class="fa fa-times-circle mr-1"></i> Revisions Required
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                    <i class="fa fa-file-pen mr-1"></i> Draft
                </span>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Edit Quotation Details Button -->
            <button @click="showEditModal = true" class="px-3.5 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5">
                <i class="fa fa-edit text-amber-500"></i> Edit Proposal
            </button>

            <!-- Sales Role Action: Request Approval -->
            @if(in_array($lead->quotation_status, ['draft', 'rejected', null]))
            <form action="{{ route('crm.quotation.request-approval', $lead->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
                    <i class="fa fa-paper-plane"></i> Request Admin Approval
                </button>
            </form>
            @endif

            <!-- Admin Role Actions: Approve / Reject -->
            @if(auth()->user()->isAdmin())
                @if($lead->quotation_status !== 'approved')
                <form action="{{ route('crm.quotation.approve', $lead->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
                        <i class="fa fa-check"></i> Approve Quotation
                    </button>
                </form>
                @endif

                @if($lead->quotation_status === 'pending_approval')
                <button @click="showRejectModal = true" class="px-3.5 py-2 bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 font-bold text-xs rounded-xl transition-all flex items-center gap-1.5">
                    <i class="fa fa-times"></i> Request Changes
                </button>
                @endif
            @endif

            <!-- Print / Download PDF -->
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                <i class="fa fa-print"></i> Print / Save PDF
            </button>
        </div>
    </div>

    <!-- Notification Banner if pending approval -->
    @if($lead->quotation_status === 'pending_approval')
    <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl flex items-center gap-3 text-amber-800 text-xs font-semibold print:hidden shadow-sm">
        <i class="fa fa-info-circle text-amber-600 text-base"></i>
        <div>
            <span><strong>Approval Request Pending:</strong> In accordance with agency policy, this quotation requires Admin review and approval before being finalized and sent to the client.</span>
        </div>
    </div>
    @elseif($lead->quotation_status === 'rejected')
    <div class="bg-rose-50 border border-rose-200 p-4 rounded-2xl flex items-center gap-3 text-rose-800 text-xs font-semibold print:hidden shadow-sm">
        <i class="fa fa-triangle-exclamation text-rose-600 text-base"></i>
        <div>
            <span><strong>Revisions Requested:</strong> Admin requested updates to this quotation. Please edit the line items or pricing and resubmit for approval.</span>
        </div>
    </div>
    @endif

    <!-- Formal Printable Proposal Document Card -->
    @php 
        $agencySettings = \App\Http\Controllers\SettingsController::getAgencySettings(); 
        $currency = $agencySettings['agency_currency'] ?? '৳';
        $propNumber = 'PROP-' . date('Y') . '-' . str_pad($lead->id, 4, '0', STR_PAD_LEFT);
    @endphp

    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-lg overflow-hidden print:shadow-none print:border-none print:p-0">
        <!-- Top Accent Strip -->
        <div class="h-2 bg-gradient-to-r from-amber-500 via-amber-600 to-slate-900 print:hidden"></div>

        <div class="p-8 md:p-10 space-y-8">
            <!-- Header Section: Logo & Document Title -->
            <div class="flex flex-col sm:flex-row items-start justify-between gap-6 pb-6 border-b border-slate-200/80">
                <!-- Agency Branding & Info -->
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        @if(!empty($agencySettings['agency_logo']))
                            <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 p-1 flex items-center justify-center overflow-hidden shadow-sm">
                                <img src="{{ asset(ltrim($agencySettings['agency_logo'], '/')) }}" alt="Agency Logo" class="max-h-full max-w-full object-contain">
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-xl bg-slate-900 text-amber-400 flex items-center justify-center text-lg font-extrabold shadow-md">
                                {{ strtoupper(substr($agencySettings['agency_name'] ?? 'D', 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $agencySettings['agency_name'] ?? 'DMS Creative Agency' }}</h1>
                            <p class="text-xs font-semibold text-slate-500">Service Proposal & Quotation</p>
                        </div>
                    </div>

                    <div class="text-xs text-slate-500 leading-relaxed space-y-0.5">
                        <p><i class="fa fa-location-dot w-4 text-slate-400"></i> {{ $agencySettings['agency_address'] ?? 'Dhaka, Bangladesh' }}</p>
                        @if(!empty($agencySettings['agency_email']))
                        <p><i class="fa fa-envelope w-4 text-slate-400"></i> {{ $agencySettings['agency_email'] }}</p>
                        @endif
                        @if(!empty($agencySettings['agency_phone']))
                        <p><i class="fa fa-phone w-4 text-slate-400"></i> {{ $agencySettings['agency_phone'] }}</p>
                        @endif
                    </div>
                </div>

                <!-- Quotation Meta & Status -->
                <div class="sm:text-right space-y-2">
                    <div class="inline-block">
                        <span class="text-xs font-black uppercase tracking-widest text-slate-400 block mb-1">PROPOSAL REF</span>
                        <h2 class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ $propNumber }}</h2>
                    </div>

                    <div class="pt-1">
                        @if($lead->quotation_status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 uppercase tracking-wider">
                                <i class="fa fa-circle-check mr-1.5"></i> Verified Proposal
                            </span>
                        @elseif($lead->quotation_status === 'pending_approval')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300 uppercase tracking-wider">
                                <i class="fa fa-clock mr-1.5"></i> Under Review
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-slate-100 text-slate-700 border border-slate-300 uppercase tracking-wider">
                                <i class="fa fa-file-pen mr-1.5"></i> Draft Quotation
                            </span>
                        @endif
                    </div>

                    <div class="text-xs text-slate-500 space-y-0.5 pt-2">
                        <p><strong>Date Prepared:</strong> {{ now()->format('F d, Y') }}</p>
                        <p><strong>Valid Until:</strong> {{ $lead->quotation_valid_until ? $lead->quotation_valid_until->format('F d, Y') : now()->addDays(15)->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Client & Prepared By Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 py-2">
                <!-- Proposal Prepared For -->
                <div class="space-y-2 bg-slate-50/70 p-4 rounded-xl border border-slate-100">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">PROPOSAL PREPARED FOR:</span>
                    <h3 class="text-base font-bold text-slate-900">{{ $lead->name }}</h3>
                    @if($lead->company)
                    <p class="text-xs font-bold text-amber-700 flex items-center gap-1.5">
                        <i class="fa fa-building text-[11px] text-amber-600"></i> {{ $lead->company }}
                    </p>
                    @endif
                    <div class="text-xs text-slate-600 space-y-0.5 pt-1">
                        @if($lead->phone)<p><i class="fa fa-phone w-4 text-slate-400"></i> {{ $lead->phone }}</p>@endif
                        @if($lead->email)<p><i class="fa fa-envelope w-4 text-slate-400"></i> {{ $lead->email }}</p>@endif
                        @if($lead->location)<p><i class="fa fa-location-dot w-4 text-slate-400"></i> {{ $lead->location }}</p>@endif
                    </div>
                </div>

                <!-- Account Representative & Approval Meta -->
                <div class="space-y-2 bg-slate-50/70 p-4 rounded-xl border border-slate-100">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">ACCOUNT & SALES CONTACT:</span>
                    <h3 class="text-base font-bold text-slate-900">{{ $lead->assignedTo->name ?? 'Sales Representative' }}</h3>
                    <p class="text-xs text-slate-500 font-medium">Sales & Client Success Consultant</p>
                    <div class="text-xs text-slate-600 space-y-1 pt-1">
                        <p><i class="fa fa-envelope w-4 text-slate-400"></i> {{ $lead->assignedTo->email ?? 'sales@agency.com' }}</p>
                        @if($lead->quotation_approved_by)
                        <p class="pt-1 border-t border-slate-200/80 text-[11px] text-emerald-700 font-semibold">
                            <i class="fa fa-shield-check text-emerald-600 mr-1"></i> Admin Approved by: {{ $lead->quotationApprovedBy->name }} ({{ $lead->quotation_approved_at ? $lead->quotation_approved_at->format('d M, Y') : '' }})
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Itemized Scope of Services Table -->
            <div class="space-y-4">
                <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                    <i class="fa fa-list-check text-amber-500"></i> Scope of Services & Deliverables
                </h4>

                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white text-[11px] font-bold uppercase tracking-wider">
                                <th class="py-3 px-4 w-12 text-center">#</th>
                                <th class="py-3 px-4">Service Description & Deliverables</th>
                                <th class="py-3 px-4 text-center w-24">Qty / Cycles</th>
                                <th class="py-3 px-4 text-right w-36">Rate ({{ $currency }})</th>
                                <th class="py-3 px-4 text-right w-36">Subtotal ({{ $currency }})</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($lead->requirements as $index => $req)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-4 font-bold text-slate-800">
                                    {{ $req->service_name }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-semibold text-slate-700">{{ $req->qty }}</td>
                                <td class="py-3.5 px-4 text-right font-semibold text-slate-700">{{ number_format($req->unit_price, 2) }}</td>
                                <td class="py-3.5 px-4 text-right font-extrabold text-slate-900 font-mono">{{ number_format($req->qty * $req->unit_price, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400">1</td>
                                <td class="py-3.5 px-4 font-bold text-slate-800">Complete Marketing & Creative Scope (As Discussed)</td>
                                <td class="py-3.5 px-4 text-center font-semibold text-slate-700">1</td>
                                <td class="py-3.5 px-4 text-right font-semibold text-slate-700">{{ number_format($lead->budget, 2) }}</td>
                                <td class="py-3.5 px-4 text-right font-extrabold text-slate-900 font-mono">{{ number_format($lead->budget, 2) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Financial Summary & Totals Block -->
            <div class="flex flex-col sm:flex-row justify-between items-start gap-6 pt-2">
                <!-- Proposal Notes & Terms -->
                <div class="flex-1 space-y-2 text-xs text-slate-500">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">PROPOSAL TERMS & CONDITIONS:</span>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 font-medium text-slate-600 leading-relaxed space-y-1">
                        @if($lead->quotation_notes)
                            <p class="whitespace-pre-line">{{ $lead->quotation_notes }}</p>
                        @else
                            <p>1. Pricing is valid for 15 days from the issue date stated above.</p>
                            <p>2. Payment Terms: 50% advance upon confirmation, 50% upon milestone deliverables.</p>
                            <p>3. Execution timeline commences upon receiving required client assets and branding guidelines.</p>
                            <p>4. Revisions: Includes standard 2 rounds of review per deliverable.</p>
                        @endif
                    </div>
                </div>

                <!-- Totals Table -->
                <div class="w-full sm:w-80 bg-slate-50 p-5 rounded-2xl border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span class="font-semibold">Subtotal Value:</span>
                        <span class="font-bold font-mono text-slate-900">{{ $currency }}{{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($discount > 0)
                    <div class="flex items-center justify-between text-xs text-emerald-600">
                        <span class="font-semibold">Special Discount:</span>
                        <span class="font-bold font-mono">-{{ $currency }}{{ number_format($discount, 2) }}</span>
                    </div>
                    @endif

                    @if($tax > 0)
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span class="font-semibold">VAT / Tax ({{ $lead->quotation_tax_percent }}%):</span>
                        <span class="font-bold font-mono text-slate-900">+{{ $currency }}{{ number_format($tax, 2) }}</span>
                    </div>
                    @endif

                    <div class="pt-3 border-t border-slate-200 flex items-center justify-between text-base">
                        <span class="font-black text-slate-900">Total Investment:</span>
                        <span class="font-black font-mono text-amber-600 text-lg">{{ $currency }}{{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Signatures Section -->
            <div class="pt-8 border-t border-slate-200 grid grid-cols-1 sm:grid-cols-3 gap-8 text-center text-xs text-slate-500">
                <div class="space-y-12">
                    <div class="h-10"></div>
                    <div class="border-t border-slate-300 pt-2">
                        <p class="font-bold text-slate-800">{{ $lead->assignedTo->name ?? 'Sales Representative' }}</p>
                        <p class="text-[10px] text-slate-400 uppercase">Prepared By (Sales Rep)</p>
                    </div>
                </div>

                <div class="space-y-12">
                    <div class="h-10 flex items-center justify-center">
                        @if($lead->quotation_status === 'approved')
                        <span class="text-xs font-extrabold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-300">
                            <i class="fa fa-certificate mr-1"></i> Authorized & Approved
                        </span>
                        @endif
                    </div>
                    <div class="border-t border-slate-300 pt-2">
                        <p class="font-bold text-slate-800">{{ $lead->quotationApprovedBy->name ?? 'Agency Director' }}</p>
                        <p class="text-[10px] text-slate-400 uppercase">Approved By (Agency Management)</p>
                    </div>
                </div>

                <div class="space-y-12">
                    <div class="h-10"></div>
                    <div class="border-t border-slate-300 pt-2">
                        <p class="font-bold text-slate-800">{{ $lead->name }}</p>
                        <p class="text-[10px] text-slate-400 uppercase">Accepted & Confirmed By (Client)</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Edit Proposal Modal -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-2xl w-full p-6 space-y-5 max-h-[90vh] overflow-y-auto" @click.outside="showEditModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                    <i class="fa fa-edit text-amber-500"></i> Edit Proposal & Quotation Details
                </h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa fa-times text-lg"></i></button>
            </div>

            <form action="{{ route('crm.quotation.update', $lead->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Valid Until Date</label>
                        <input type="date" name="quotation_valid_until" value="{{ $lead->quotation_valid_until ? $lead->quotation_valid_until->format('Y-m-d') : now()->addDays(15)->format('Y-m-d') }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Discount Amount (৳)</label>
                        <input type="number" step="0.01" min="0" name="quotation_discount" value="{{ $lead->quotation_discount ?? 0 }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">VAT / Tax (%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="quotation_tax_percent" value="{{ $lead->quotation_tax_percent ?? 0 }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Proposal Terms & Deliverable Notes</label>
                    <textarea name="quotation_notes" rows="4" placeholder="Specific terms, milestones, payment plan, or delivery scope..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">{{ $lead->quotation_notes }}</textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 shadow-md">
                        Save Quotation Updates
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Admin Reject Modal -->
    <div x-show="showRejectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full p-6 space-y-4" @click.outside="showRejectModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa fa-times-circle text-rose-500"></i> Request Quotation Revisions
                </h3>
                <button @click="showRejectModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa fa-times text-lg"></i></button>
            </div>

            <form action="{{ route('crm.quotation.reject', $lead->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Reason / Modification Feedback <span class="text-rose-500">*</span></label>
                    <textarea name="reason" rows="3" required placeholder="Specify why the quotation was rejected or what changes are needed..."
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-rose-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" @click="showRejectModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md">Send Rejection Feedback</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
