@extends('layouts.app')

@section('title', 'Team & Role Feature Control Management')
@section('header_title', 'Team Directory & Role Feature Controls')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'members' }">

    <!-- Header & Navigation Tabs -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Team Management & Feature Access Controls</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Manage agency staff accounts and configure role-based feature permissions across the CRM.</p>
        </div>

        <div class="flex items-center gap-2 bg-slate-200/80 p-1 rounded-xl border border-slate-300/60 shadow-inner">
            <button @click="activeTab = 'members'"
                    :class="activeTab === 'members' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                    class="px-4 py-2 rounded-lg text-xs flex items-center gap-2 transition-all">
                <i class="fa fa-users text-amber-500"></i>
                <span>Team Directory</span>
            </button>
            <button @click="activeTab = 'permissions'"
                    :class="activeTab === 'permissions' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                    class="px-4 py-2 rounded-lg text-xs flex items-center gap-2 transition-all">
                <i class="fa fa-shield-halved text-amber-500"></i>
                <span>Role Feature Controls</span>
            </button>
        </div>
    </div>

    <!-- TAB 1: TEAM DIRECTORY LIST -->
    <div x-show="activeTab === 'members'" class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <form action="{{ route('team.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative w-64">
                    <i class="fa fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search team member name or username..."
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                </div>

                <div class="w-48">
                    <select name="role" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                        <option value="">All Roles</option>
                        @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition-all">
                    Filter
                </button>

                @if(request()->anyFilled(['search', 'role']))
                <a href="{{ route('team.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200 transition-all">
                    Reset
                </a>
                @endif
            </form>

            <a href="{{ route('team.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold text-xs rounded-xl shadow-md shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                <i class="fa fa-user-plus"></i> Register Team Member
            </a>
        </div>

        <!-- Team Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($users as $user)
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition-all">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-extrabold text-white text-sm shadow-sm" style="background-color: {{ $user->color ?? '#64748b' }}">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            {{ $user->name }}
                            @if(!$user->active)
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-100 text-rose-700">Inactive</span>
                            @endif
                        </h4>
                        <p class="text-xs text-slate-400 font-mono">@ {{ $user->username }}</p>
                        <span class="inline-flex items-center mt-1.5 px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 uppercase tracking-wider">
                            {{ $roles[$user->role] ?? ucfirst($user->role) }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('team.edit', $user->id) }}" class="px-3 py-1.5 text-xs font-bold text-amber-600 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition-all flex items-center gap-1" title="Edit Member">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        @if($users->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-sm">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- TAB 2: ROLE FEATURE CONTROL MATRIX -->
    <div x-show="activeTab === 'permissions'" x-cloak class="space-y-6">
        <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
            <i class="fa fa-shield-halved text-amber-600 text-lg mt-0.5"></i>
            <div class="text-xs text-amber-900">
                <h4 class="font-bold text-amber-950 mb-0.5">Role Feature Access Control Matrix</h4>
                <p class="leading-relaxed font-medium">Check or uncheck features to dynamically control what sections each team role can access. System updates take effect immediately upon saving.</p>
            </div>
        </div>

        <form action="{{ route('team.update-permissions') }}" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white text-[11px] font-extrabold uppercase tracking-wider">
                            <th class="py-4 px-5 min-w-[220px] sticky left-0 z-20 bg-slate-900 border-r border-slate-800 shadow-[4px_0_8px_-2px_rgba(0,0,0,0.3)]">System Role</th>
                            @foreach($features as $fKey => $fLabel)
                                <th class="py-4 px-3 text-center whitespace-nowrap min-w-[120px]" title="{{ $fLabel }}">
                                    {{ $fLabel }}
                                </th>
                            @endforeach
                            <th class="py-4 px-4 text-center whitespace-nowrap">Quick Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium">
                        @foreach($roles as $rKey => $rLabel)
                        @php
                            $isOwner = ($rKey === 'owner');
                            $rolePerms = $permissions[$rKey] ?? ['dashboard'];
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-all {{ $isOwner ? 'bg-amber-50/30' : '' }}">
                            <td class="py-4 px-5 sticky left-0 z-10 border-r border-slate-200/80 shadow-[4px_0_8px_-2px_rgba(0,0,0,0.05)] {{ $isOwner ? 'bg-amber-50' : 'bg-white' }}">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <span>{{ $rLabel }}</span>
                                    @if($isOwner)
                                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-amber-500 text-white">Full Access</span>
                                    @endif
                                </div>
                                <span class="text-[10px] font-mono text-slate-400">role: {{ $rKey }}</span>
                            </td>

                            @foreach($features as $fKey => $fLabel)
                            @php
                                $hasAccess = $isOwner || in_array($fKey, $rolePerms);
                            @endphp
                            <td class="py-4 px-3 text-center">
                                @if($isOwner)
                                    <i class="fa fa-check-circle text-emerald-500 text-base" title="Super Admin always has access"></i>
                                @else
                                    <input type="checkbox" name="permissions[{{ $rKey }}][]" value="{{ $fKey }}"
                                           {{ $hasAccess ? 'checked' : '' }}
                                           class="role-chk-{{ $rKey }} rounded text-amber-500 border-slate-300 focus:ring-amber-400 w-4 h-4 cursor-pointer">
                                @endif
                            </td>
                            @endforeach

                            <td class="py-4 px-4 text-center">
                                @if(!$isOwner)
                                    <div class="inline-flex items-center gap-1">
                                        <button type="button" onclick="toggleRoleCheckboxes('{{ $rKey }}', true)" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded text-[10px]">Select All</button>
                                        <button type="button" onclick="toggleRoleCheckboxes('{{ $rKey }}', false)" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold rounded text-[10px]">Clear</button>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-400 font-semibold">Locked</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-5 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <p class="text-xs text-slate-500 font-medium"><i class="fa fa-circle-info mr-1 text-amber-500"></i> The Dashboard module is enabled by default for all roles.</p>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold text-xs rounded-xl shadow-md shadow-amber-500/20 transition-all flex items-center gap-2">
                    <i class="fa fa-save"></i> Save Feature Controls
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function toggleRoleCheckboxes(role, check) {
        const checkboxes = document.querySelectorAll('.role-chk-' + role);
        checkboxes.forEach(cb => {
            if (cb.value !== 'dashboard' || check) {
                cb.checked = check;
            }
        });
    }
</script>
@endsection
