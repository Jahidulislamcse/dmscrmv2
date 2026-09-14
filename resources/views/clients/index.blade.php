@extends('layouts.app')

@section('title', 'Client Management')
@section('header_title', 'Super Admin Client Management')

@section('content')
<div class="space-y-6">
    <!-- Action Header & Filters Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('clients.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client name, company, phone..."
                   class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500 w-64">
            
            <select name="status" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="onboarding" {{ request('status') == 'onboarding' ? 'selected' : '' }}>Onboarding</option>
                <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-xs font-bold rounded-xl hover:bg-slate-900 transition-all">
                <i class="fa fa-filter mr-1"></i> Filter
            </button>
            
            @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('clients.index') }}" class="px-3 py-2 text-xs font-bold text-slate-500 hover:text-slate-800">Clear</a>
            @endif
        </form>

        <a href="{{ route('clients.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
            <i class="fa fa-user-plus"></i> Add New Client
        </a>
    </div>

    <!-- Client Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Client Name / Company</th>
                        <th class="py-3.5 px-6">Contact Info</th>
                        <th class="py-3.5 px-6">Assigned Team</th>
                        <th class="py-3.5 px-6">Monthly Value</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($clients as $client)
                    <tr class="hover:bg-slate-50/60 transition-all">
                        <td class="py-4 px-6 font-semibold text-slate-900">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-900 text-amber-400 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                    {{ strtoupper(substr($client->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('clients.show', $client->id) }}" class="font-bold text-slate-900 hover:text-amber-600 transition-all block">
                                        {{ $client->name }}
                                    </a>
                                    <span class="text-[11px] text-slate-400 font-normal">{{ $client->company ?? 'No company' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600 font-medium">
                            <div>{{ $client->phone ?? '—' }}</div>
                            <div class="text-[11px] text-slate-400">{{ $client->email ?? '—' }}</div>
                        </td>
                        <td class="py-4 px-6 text-slate-600 font-medium">
                            <div class="text-xs font-semibold text-slate-800">SMM: {{ $client->assignedSmm->name ?? 'Unassigned' }}</div>
                            <div class="text-[11px] text-slate-400">Sales: {{ $client->assignedSales->name ?? 'Unassigned' }}</div>
                        </td>
                        <td class="py-4 px-6 font-mono font-bold text-slate-900">
                            ৳{{ number_format($client->total_monthly_value, 2) }}
                        </td>
                        <td class="py-4 px-6">
                            @if($client->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                            @elseif($client->status === 'onboarding')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Onboarding</span>
                            @elseif($client->status === 'paused')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Paused</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600">Inactive</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('clients.show', $client->id) }}" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition-all" title="View Details">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('clients.edit', $client->id) }}" class="p-1.5 text-amber-500 hover:text-amber-700 rounded-lg hover:bg-amber-50 transition-all" title="Edit Client">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-all" title="Delete Client">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa fa-users text-4xl mb-3 text-slate-300 block"></i>
                            <p class="text-sm font-semibold">No clients match your filter criteria.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clients->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $clients->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
