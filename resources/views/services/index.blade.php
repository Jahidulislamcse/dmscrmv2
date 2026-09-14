@extends('layouts.app')

@section('title', 'Services Catalog')
@section('header_title', 'Agency Services Catalog Management')

@section('content')
<div class="space-y-6" x-data="{ openAddModal: false, openEditModal: false, activeService: {} }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h2 class="text-base font-bold text-slate-900">Agency Master Offerings</h2>
            <p class="text-xs text-slate-500">Configure global services, base pricing rates, and unit types.</p>
        </div>

        <button @click="openAddModal = true" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
            <i class="fa fa-plus"></i> Add New Service
        </button>
    </div>

    <!-- Services Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Service Name</th>
                        <th class="py-3.5 px-6">Base Rate (৳)</th>
                        <th class="py-3.5 px-6">Unit Type</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($services as $svc)
                    <tr class="hover:bg-slate-50/60 transition-all">
                        <td class="py-4 px-6 font-bold text-slate-900 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 font-bold flex items-center justify-center">
                                <i class="fa fa-tag"></i>
                            </div>
                            <span>{{ $svc->name }}</span>
                        </td>
                        <td class="py-4 px-6 font-mono font-bold text-slate-900">
                            ৳{{ number_format($svc->base_price, 2) }}
                        </td>
                        <td class="py-4 px-6 text-slate-600 uppercase font-semibold">
                            per {{ $svc->unit }}
                        </td>
                        <td class="py-4 px-6">
                            @if($svc->active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500">Disabled</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="inline-flex items-center gap-2 justify-end">
                                <button @click="activeService = @js($svc); openEditModal = true" class="px-2.5 py-1.5 text-amber-600 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition-all text-xs font-bold flex items-center gap-1" title="Edit Service">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('services.destroy', $svc->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-all" title="Delete Service">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <p class="text-sm font-semibold">No services configured yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($services->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $services->links() }}
        </div>
        @endif
    </div>

    <!-- Add Service Modal -->
    <div x-show="openAddModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full p-6 space-y-4" @click.outside="openAddModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Add New Agency Service</h3>
                <button @click="openAddModal = false" class="text-slate-400 hover:text-slate-700"><i class="fa fa-times"></i></button>
            </div>

            <form action="{{ route('services.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Service Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Social Media Management"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Base Price (৳) *</label>
                        <input type="number" step="0.01" name="base_price" required placeholder="8000"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Unit Type *</label>
                        <select name="unit" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                            <option value="month">month</option>
                            <option value="post">post</option>
                            <option value="video">video</option>
                            <option value="project">project</option>
                            <option value="article">article</option>
                            <option value="session">session</option>
                        </select>
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-md">Add Service</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div x-show="openEditModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full p-6 space-y-4" @click.outside="openEditModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Edit Agency Service</h3>
                <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-700"><i class="fa fa-times"></i></button>
            </div>

            <form :action="`{{ url('/services') }}/${activeService.id}`" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Service Name *</label>
                    <input type="text" name="name" x-model="activeService.name" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Base Price (৳) *</label>
                        <input type="number" step="0.01" name="base_price" x-model="activeService.base_price" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Unit Type *</label>
                        <select name="unit" x-model="activeService.unit" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                            <option value="month">month</option>
                            <option value="post">post</option>
                            <option value="video">video</option>
                            <option value="project">project</option>
                            <option value="article">article</option>
                            <option value="session">session</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Service Status *</label>
                    <select name="active" x-model="activeService.active" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                        <option :value="1">Active</option>
                        <option :value="0">Disabled</option>
                    </select>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-md">Update Service</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
