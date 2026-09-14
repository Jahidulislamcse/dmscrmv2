@extends('layouts.app')

@section('title', 'System & Agency Settings')
@section('header_title', 'Agency Configuration & Account Preferences')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'agency' }">
    <!-- Top Header Banner -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa fa-sliders text-brand-500"></i> Settings & Control Center
            </h2>
            <p class="text-xs text-slate-500 mt-1">Manage agency branding, user account security, CRM pipeline stages, and system backups.</p>
        </div>

        <!-- Tab Navigation Buttons -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-100/80 border border-slate-200 rounded-xl">
            <button @click="activeTab = 'agency'" :class="activeTab === 'agency' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'" class="px-3.5 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5">
                <i class="fa fa-building text-brand-500"></i> Agency Profile
            </button>
            <button @click="activeTab = 'account'" :class="activeTab === 'account' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'" class="px-3.5 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5">
                <i class="fa fa-user-gear text-purple-500"></i> My Account
            </button>
            <button @click="activeTab = 'pipeline'" :class="activeTab === 'pipeline' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'" class="px-3.5 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5">
                <i class="fa fa-filter-circle-dollar text-emerald-500"></i> CRM Pipeline
            </button>
            <button @click="activeTab = 'system'" :class="activeTab === 'system' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'" class="px-3.5 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5">
                <i class="fa fa-server text-blue-500"></i> System & Backup
            </button>
        </div>
    </div>

    <!-- TAB 1: AGENCY PROFILE -->
    <div x-show="activeTab === 'agency'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="text-sm font-bold text-slate-900">Agency Organization Profile</h3>
            <p class="text-xs text-slate-500">These details appear on generated invoices, client requisitions, and proposal documents.</p>
        </div>

        <form action="{{ route('settings.update-agency') }}" method="POST" class="space-y-5 max-w-3xl">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Agency Name *</label>
                    <input type="text" name="agency_name" value="{{ old('agency_name', $settings['agency_name']) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Contact Email *</label>
                    <input type="email" name="agency_email" value="{{ old('agency_email', $settings['agency_email']) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Contact Phone *</label>
                    <input type="text" name="agency_phone" value="{{ old('agency_phone', $settings['agency_phone']) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Currency Symbol *</label>
                    <input type="text" name="agency_currency" value="{{ old('agency_currency', $settings['agency_currency']) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tax / BIN ID</label>
                    <input type="text" name="tax_id" value="{{ old('tax_id', $settings['tax_id']) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Office Address *</label>
                <textarea name="agency_address" rows="3" required
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-500">{{ old('agency_address', $settings['agency_address']) }}</textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                    <i class="fa fa-save"></i> Save Agency Profile
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 2: MY ACCOUNT & SECURITY -->
    <div x-show="activeTab === 'account'" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-sm font-bold text-slate-900">Personal Information</h3>
                <p class="text-xs text-slate-500">Update your user account profile and display badge color.</p>
            </div>

            <form action="{{ route('settings.update-profile') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-purple-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Username *</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-purple-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-purple-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Avatar Badge Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" value="{{ old('color', $user->color ?? '#f59e0b') }}"
                               class="w-12 h-10 p-1 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer">
                        <span class="text-xs text-slate-500 font-mono">{{ $user->color ?? '#f59e0b' }}</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                        <i class="fa fa-user-check"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- Security & Password Change -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-sm font-bold text-slate-900">Security & Password</h3>
                <p class="text-xs text-slate-500">Ensure your account uses a strong, secure password.</p>
            </div>

            <form action="{{ route('settings.update-password') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Current Password *</label>
                    <input type="password" name="current_password" required placeholder="••••••••"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-rose-500">
                    @error('current_password')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">New Password *</label>
                    <input type="password" name="password" required placeholder="Minimum 6 characters"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-rose-500">
                    @error('password')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Confirm New Password *</label>
                    <input type="password" name="password_confirmation" required placeholder="Re-type new password"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-rose-500">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                        <i class="fa fa-lock"></i> Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 3: CRM PIPELINE STAGES -->
    <div x-show="activeTab === 'pipeline'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="text-sm font-bold text-slate-900">CRM Lead Pipeline Stages</h3>
            <p class="text-xs text-slate-500">Active deal stages used in lead pipeline tracking and conversion analytics.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @forelse($leadStages as $stage)
            <div class="p-4 rounded-xl border border-slate-200/80 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-3.5 h-3.5 rounded-full" style="background-color: {{ $stage->color }}"></span>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">{{ $stage->name }}</h4>
                        <span class="text-[10px] text-slate-500 font-semibold">Order: #{{ $stage->order }}</span>
                    </div>
                </div>

                @if($stage->is_won)
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">Won</span>
                @elseif($stage->is_lost)
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">Lost</span>
                @else
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700">Active</span>
                @endif
            </div>
            @empty
            <div class="col-span-full py-8 text-center text-slate-400">
                <p class="text-xs font-semibold">No lead stages configured.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- TAB 4: SYSTEM INFORMATION & BACKUP -->
    <div x-show="activeTab === 'system'" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- System Health & Environment -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-sm font-bold text-slate-900">System Environment & Runtime</h3>
                <p class="text-xs text-slate-500">Live operational environment diagnostic statistics.</p>
            </div>

            <div class="space-y-3 text-xs">
                <div class="flex items-center justify-between py-2 border-b border-slate-100">
                    <span class="font-semibold text-slate-600">PHP Version</span>
                    <span class="font-mono font-bold text-slate-900">{{ $systemInfo['php_version'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-slate-100">
                    <span class="font-semibold text-slate-600">Laravel Framework</span>
                    <span class="font-mono font-bold text-slate-900">v{{ $systemInfo['laravel_version'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-slate-100">
                    <span class="font-semibold text-slate-600">Server Software</span>
                    <span class="font-mono font-bold text-slate-900 truncate max-w-xs">{{ $systemInfo['server_software'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-slate-100">
                    <span class="font-semibold text-slate-600">Memory Limit</span>
                    <span class="font-mono font-bold text-slate-900">{{ $systemInfo['memory_limit'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="font-semibold text-slate-600">App Timezone</span>
                    <span class="font-mono font-bold text-slate-900">{{ $systemInfo['timezone'] }}</span>
                </div>
            </div>
        </div>

        <!-- Data Export & Backup -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-sm font-bold text-slate-900">Database Backup & Export</h3>
                <p class="text-xs text-slate-500">Export complete CRM data records (Clients, Leads, Invoices, Expenses) as JSON.</p>
            </div>

            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 space-y-2">
                <div class="flex items-center gap-2 font-bold text-xs">
                    <i class="fa fa-shield-halved text-amber-600"></i> JSON Backup Protection
                </div>
                <p class="text-[11px] text-amber-800">
                    You can download a complete backup snapshot of your CRM database records for safe archiving or migration to live servers.
                </p>
            </div>

            <div class="pt-2">
                <a href="{{ route('settings.export-backup') }}" class="w-full py-3 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                    <i class="fa fa-download"></i> Download Full CRM Backup (JSON)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
