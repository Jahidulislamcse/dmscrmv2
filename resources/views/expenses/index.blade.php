@extends('layouts.app')

@section('title', 'Expenses Log')
@section('header_title', 'Agency Operational Expenses Oversight')

@section('content')
<div class="space-y-6" x-data="{ openAddModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h2 class="text-base font-bold text-slate-900">Agency Expenses</h2>
            <p class="text-xs text-slate-500">Total Expenses Logged: <strong class="text-purple-600 font-mono">৳{{ number_format($totalExpenses, 2) }}</strong></p>
        </div>

        <button @click="openAddModal = true" class="px-4 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-purple-600/20 transition-all flex items-center justify-center gap-2">
            <i class="fa fa-plus"></i> Log Agency Expense
        </button>
    </div>

    <!-- Expenses Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Title / Description</th>
                        <th class="py-3.5 px-6">Category</th>
                        <th class="py-3.5 px-6">Amount (৳)</th>
                        <th class="py-3.5 px-6">Payment Method</th>
                        <th class="py-3.5 px-6">Date</th>
                        <th class="py-3.5 px-6">Logged By</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($expenses as $exp)
                    <tr class="hover:bg-slate-50/60 transition-all">
                        <td class="py-4 px-6 font-bold text-slate-900">
                            {{ $exp->title }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold text-slate-700 border border-slate-200" style="border-left-color: {{ $exp->category->color ?? '#64748b' }}; border-left-width: 4px;">
                                {{ $exp->category->name ?? 'Miscellaneous' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 font-mono font-bold text-purple-600">
                            ৳{{ number_format($exp->amount, 2) }}
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            {{ $exp->payment_method }}
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            {{ $exp->date->format('M d, Y') }}
                        </td>
                        <td class="py-4 px-6 text-slate-500 font-medium">
                            {{ $exp->createdBy->name ?? 'Admin' }}
                        </td>
                        <td class="py-4 px-6 text-right">
                            <form action="{{ route('expenses.destroy', $exp) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this expense log?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-all" title="Delete Expense">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <i class="fa fa-receipt text-4xl mb-3 text-slate-300 block"></i>
                            <p class="text-sm font-semibold">No agency expenses logged yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>

    <!-- Modal to Log Expense -->
    <div x-show="openAddModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full p-6 space-y-4" @click.outside="openAddModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Log Agency Expense</h3>
                <button @click="openAddModal = false" class="text-slate-400 hover:text-slate-700"><i class="fa fa-times"></i></button>
            </div>

            <form action="{{ route('expenses.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Expense Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Office Rent for May"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Amount (৳) *</label>
                        <input type="number" step="0.01" name="amount" required placeholder="15000"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Category *</label>
                        <select name="category_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Payment Method *</label>
                        <input type="text" name="payment_method" value="Bank Transfer" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Expense Date *</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-amber-500">
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md">Log Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
