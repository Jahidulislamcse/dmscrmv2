@extends('layouts.app')

@section('title', 'Sales Targets & Quotas — DMS CRM')
@section('header_title', 'Sales Targets & Revenue Quotas')

@section('content')
<div class="space-y-6" x-data="{ 
    showCreateModal: false, 
    showDealModal: false, 
    activeTargetId: null, 
    activeTargetLabel: '',
    targetItems: [{ service_name: '', qty: 1, unit_price: 0 }] 
}">

    <!-- Header Banner & Month Filter -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2 max-w-2xl">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
                    <i class="fa fa-crosshairs mr-1"></i> Performance Quotas
                </span>
                <span class="text-xs text-slate-400 font-mono font-bold">{{ date('F Y', strtotime($selectedMonth . '-01')) }}</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black tracking-tight">Sales Targets & Achievement Tracking</h2>
            <p class="text-xs sm:text-sm text-slate-300 font-medium leading-relaxed">
                Monthly revenue quotas, service volume deliverables, and deal attribution progress gauges per sales executive.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Month Picker -->
            <form action="{{ route('targets.index') }}" method="GET" class="flex items-center gap-2 bg-slate-800/80 p-2 rounded-2xl border border-slate-700/80">
                <i class="fa fa-calendar text-amber-400 text-xs pl-2"></i>
                <select name="month" onchange="this.form.submit()" class="bg-slate-900 text-white border border-slate-700 rounded-xl px-3 py-1.5 text-xs font-bold focus:ring-2 focus:ring-amber-500 cursor-pointer">
                    @foreach($monthOptions as $mKey => $mLabel)
                        <option value="{{ $mKey }}" {{ $selectedMonth === $mKey ? 'selected' : '' }}>
                            {{ $mLabel }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if(!auth()->user()->isSales())
            <button @click="showCreateModal = true" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-900 font-black text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
                <i class="fa fa-plus"></i> Set Sales Target
            </button>
            @endif
        </div>
    </div>

    <!-- 4 Aggregate KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Goal -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Sales Quota</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-bullseye"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900">
                ৳{{ number_format($totalGoal, 0) }}
            </div>
            <div class="text-[11px] text-slate-500 font-medium">
                {{ $targets->count() }} Sales Quotas Active
            </div>
        </div>

        <!-- 2. Revenue Achieved -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Deals Achieved</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-sack-dollar"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-600">
                ৳{{ number_format($totalAchieved, 0) }}
            </div>
            <div class="text-[11px] text-emerald-700 font-bold">
                {{ $totalDealsCount }} Attributed Closed Deals
            </div>
        </div>

        <!-- 3. Overall Progress Gauge -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Quota Achievement</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-chart-line"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900">
                {{ $overallProgress }}%
            </div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden mt-1">
                <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $overallProgress }}%"></div>
            </div>
        </div>

        <!-- 4. Remaining Balance -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Remaining to Goal</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xs font-bold">
                    <i class="fa fa-hourglass-half"></i>
                </div>
            </div>
            @php $gap = max(0, $totalGoal - $totalAchieved); @endphp
            <div class="text-2xl font-black {{ $gap > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                ৳{{ number_format($gap, 0) }}
            </div>
            <div class="text-[11px] {{ $gap == 0 && $totalGoal > 0 ? 'text-emerald-600 font-bold' : 'text-slate-500' }}">
                {{ $gap == 0 && $totalGoal > 0 ? 'Target Achieved! 100%' : 'To reach agency milestone' }}
            </div>
        </div>
    </div>

    <!-- Targets Dossier Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($targets as $target)
        @php
            $goal = $target->total;
            $achieved = $target->achieved;
            $percent = $goal > 0 ? min(100, round(($achieved / $goal) * 100, 1)) : 0;
            $barColor = $percent >= 100 ? 'bg-emerald-500' : ($percent >= 60 ? 'bg-blue-600' : ($percent >= 30 ? 'bg-amber-500' : 'bg-slate-500'));
            $badgeBg = $percent >= 100 ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : ($percent >= 60 ? 'bg-blue-100 text-blue-800 border-blue-300' : 'bg-amber-100 text-amber-800 border-amber-300');
        @endphp
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
            <!-- Target Card Header -->
            <div class="p-6 border-b border-slate-100 space-y-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-amber-600 text-white font-extrabold text-lg flex items-center justify-center shadow-md shadow-amber-500/20">
                            {{ strtoupper(substr($target->user->name ?? 'Sales', 0, 2)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-extrabold text-slate-900">{{ $target->user->name ?? 'Sales Executive' }}</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $target->user->isSales() ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">
                                    {{ $target->user->role }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5 font-medium">{{ $target->label }}</p>
                        </div>
                    </div>

                    <span class="px-3 py-1 rounded-full text-xs font-black border flex items-center gap-1 {{ $badgeBg }}">
                        <i class="fa fa-chart-pie"></i> {{ $percent }}%
                    </span>
                </div>

                <!-- Progress Bar & Amount Comparison -->
                <div class="space-y-1.5 bg-slate-50 p-3.5 rounded-2xl border border-slate-200/60">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-600">Progress: <strong class="text-emerald-700">৳{{ number_format($achieved, 0) }}</strong></span>
                        <span class="text-slate-400 font-normal">Quota: <strong class="text-slate-900 font-bold">৳{{ number_format($goal, 0) }}</strong></span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                        <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Target Service Items Breakdown Table -->
            <div class="p-6 space-y-4 flex-1">
                <div>
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5 mb-2">
                        <i class="fa fa-tags text-amber-500"></i> Service Volume Quotas
                    </h4>
                    <div class="overflow-x-auto border border-slate-200/80 rounded-xl">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider border-b border-slate-200/80">
                                    <th class="py-2 px-3">Service Name</th>
                                    <th class="py-2 px-3 text-center">Goal Qty</th>
                                    <th class="py-2 px-3 text-right">Unit Price</th>
                                    <th class="py-2 px-3 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                @forelse($target->items as $item)
                                <tr>
                                    <td class="py-2 px-3 font-semibold text-slate-800">{{ $item->service_name }}</td>
                                    <td class="py-2 px-3 text-center font-bold text-slate-900">{{ $item->qty }}</td>
                                    <td class="py-2 px-3 text-right text-slate-600">৳{{ number_format($item->unit_price, 0) }}</td>
                                    <td class="py-2 px-3 text-right font-extrabold text-slate-900">৳{{ number_format($item->qty * $item->unit_price, 0) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-3 text-center text-slate-400 italic">No specific service quotas configured.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Attributed Closed Deals -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                            <i class="fa fa-handshake text-emerald-600"></i> Attributed Closed Deals ({{ $target->deals->count() }})
                        </h4>
                    </div>

                    <div class="space-y-1.5">
                        @forelse($target->deals as $deal)
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-200/60 text-xs">
                            <div class="space-y-0.5">
                                <span class="font-bold text-slate-900 block">{{ $deal->service_name }}</span>
                                <span class="text-[10px] text-slate-500">
                                    {{ $deal->client->name ?? 'Direct Deal' }} · {{ $deal->date ? $deal->date->format('d M Y') : 'This Month' }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="font-black text-emerald-600 block">৳{{ number_format($deal->amount, 0) }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">Qty: {{ $deal->qty }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="p-3 bg-slate-50 rounded-xl text-center text-slate-400 text-xs italic">
                            No deals attributed to this quota yet.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Card Action Footer -->
            <div class="p-4 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between gap-2">
                <button @click="showDealModal = true; activeTargetId = {{ $target->id }}; activeTargetLabel = '{{ addslashes($target->label) }}'" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                    <i class="fa fa-plus"></i> Record Closed Deal
                </button>

                @if(!auth()->user()->isSales())
                <form action="{{ route('targets.destroy', $target->id) }}" method="POST" onsubmit="return confirm('Delete this target and its items?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-all" title="Delete Target">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-2 bg-white rounded-3xl border border-slate-200/80 p-12 text-center text-slate-400 space-y-3">
            <div class="w-16 h-16 rounded-3xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl mx-auto shadow-sm">
                <i class="fa fa-crosshairs"></i>
            </div>
            <h3 class="text-base font-extrabold text-slate-800">No Sales Targets Configured for {{ date('F Y', strtotime($selectedMonth . '-01')) }}</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">
                Set monthly revenue goals and service volume targets per sales representative to track deal closure and performance commissions.
            </p>
            @if(!auth()->user()->isSales())
            <button @click="showCreateModal = true" class="mt-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                Create First Target
            </button>
            @endif
        </div>
        @endforelse
    </div>

    <!-- MODAL 1: Create New Target -->
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="showCreateModal = false" class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
            <div class="p-6 bg-slate-900 text-white flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black">Assign Sales Quota & Target</h3>
                    <p class="text-xs text-slate-400">Establish revenue milestones and service quotas for sales staff</p>
                </div>
                <button @click="showCreateModal = false" class="text-slate-400 hover:text-white text-lg"><i class="fa fa-times"></i></button>
            </div>

            <form action="{{ route('targets.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Sales Executive *</label>
                        <select name="user_id" required class="w-full text-xs font-semibold rounded-xl border border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-500">
                            @foreach($salesReps as $rep)
                                <option value="{{ $rep->id }}">{{ $rep->name }} ({{ ucfirst($rep->role) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Target Month *</label>
                        <input type="month" name="month" value="{{ $selectedMonth }}" required class="w-full text-xs font-semibold rounded-xl border border-slate-200 p-2 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Target Campaign / Label *</label>
                    <input type="text" name="label" placeholder="e.g. Q4 Agency Enterprise Quota" required class="w-full text-xs font-semibold rounded-xl border border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-500">
                </div>

                <!-- Service Line Items -->
                <div class="space-y-2 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Service Quota Line Items</label>
                        <button type="button" @click="targetItems.push({ service_name: '', qty: 1, unit_price: 0 })" class="text-xs text-amber-600 font-bold hover:underline">
                            + Add Service
                        </button>
                    </div>

                    <template x-for="(item, index) in targetItems" :key="index">
                        <div class="grid grid-cols-12 gap-2 items-center bg-slate-50 p-2 rounded-xl border border-slate-200">
                            <div class="col-span-5">
                                <input type="text" :name="'items['+index+'][service_name]'" x-model="item.service_name" placeholder="Service name" required class="w-full text-xs rounded-lg border border-slate-200 p-1.5 bg-white">
                            </div>
                            <div class="col-span-3">
                                <input type="number" :name="'items['+index+'][qty]'" x-model="item.qty" min="1" placeholder="Qty" required class="w-full text-xs rounded-lg border border-slate-200 p-1.5 bg-white text-center">
                            </div>
                            <div class="col-span-3">
                                <input type="number" :name="'items['+index+'][unit_price]'" x-model="item.unit_price" min="0" placeholder="Price" required class="w-full text-xs rounded-lg border border-slate-200 p-1.5 bg-white text-right">
                            </div>
                            <div class="col-span-1 text-center">
                                <button type="button" @click="if(targetItems.length > 1) targetItems.splice(index, 1)" class="text-rose-500 hover:text-rose-700 text-xs">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md">Assign Target</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: Record Closed Deal Attribution -->
    <div x-show="showDealModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="showDealModal = false" class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200">
            <div class="p-6 bg-emerald-950 text-white flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black">Record Closed Deal Attribution</h3>
                    <p class="text-xs text-emerald-300" x-text="activeTargetLabel"></p>
                </div>
                <button @click="showDealModal = false" class="text-slate-400 hover:text-white text-lg"><i class="fa fa-times"></i></button>
            </div>

            <form :action="'{{ url('targets') }}/' + activeTargetId + '/deals'" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Associated Client (Optional)</label>
                    <select name="client_id" class="w-full text-xs font-semibold rounded-xl border border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- No Direct Client / Walk-in --</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->company ?? 'Brand' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Service / Package Closed *</label>
                    <input type="text" name="service_name" placeholder="e.g. 360 Social Media Management Retainer" required class="w-full text-xs font-semibold rounded-xl border border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Quantity *</label>
                        <input type="number" name="qty" value="1" min="1" required class="w-full text-xs font-semibold rounded-xl border border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Deal Amount (৳) *</label>
                        <input type="number" name="amount" min="0" placeholder="e.g. 50000" required class="w-full text-xs font-semibold rounded-xl border border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deal Closing Date *</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full text-xs font-semibold rounded-xl border border-slate-200 p-2.5 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="showDealModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md">Record Deal</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
