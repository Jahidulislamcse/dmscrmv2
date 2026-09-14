<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin Dashboard') — DMS CRM</title>
    
    <!-- Google Fonts & Tailwind CSS CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#fffbe6',
                            100: '#fff3c4',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                        },
                        navy: {
                            800: '#0f1d33',
                            900: '#08111f',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 bg-navy-900 text-slate-300 flex-shrink-0 flex flex-col justify-between"
               :class="sidebarOpen ? 'block' : 'hidden md:flex'">
            <div>
                <!-- Brand Header -->
                @php $agencySettings = \App\Http\Controllers\SettingsController::getAgencySettings(); @endphp
                <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        @if(!empty($agencySettings['agency_logo']))
                        <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center p-1 overflow-hidden shadow-md">
                            <img src="{{ asset($agencySettings['agency_logo']) }}" alt="Logo" class="max-h-full max-w-full object-contain">
                        </div>
                        @else
                        <div class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center font-extrabold text-white text-lg shadow-md shadow-amber-500/20">
                            D
                        </div>
                        @endif
                        <div>
                            <span class="font-extrabold text-white text-lg tracking-tight truncate max-w-[130px] block">{{ $agencySettings['agency_name'] ?? 'DMS CRM' }}</span>
                            <span class="block text-[10px] text-slate-400 font-semibold tracking-wider uppercase">v2.0 MVC Pro</span>
                        </div>
                    </a>
                    <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                        <i class="fa fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Active User Card -->
                @auth
                <div class="px-6 py-4 border-b border-slate-800 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white text-xs shadow-inner" style="background-color: {{ auth()->user()->color ?? '#f59e0b' }}">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</h4>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500/10 text-brand-500 border border-brand-500/20">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </div>
                </div>
                @endauth

                <!-- Nav Menu Links -->
                <nav class="px-4 py-6 space-y-1">
                    <!-- OVERVIEW -->
                    <div class="px-3 pb-2 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">Overview</div>

                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-white shadow-md shadow-amber-500/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <i class="fa fa-chart-pie w-4 text-center"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- SALES & PROSPECTS -->
                    @if(auth()->user()->canAccess('crm') || auth()->user()->canAccess('meetings') || auth()->user()->canAccess('requisitions'))
                    <div class="px-3 pt-4 pb-2 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">Sales & Prospects</div>

                    @if(auth()->user()->canAccess('crm'))
                    <a href="{{ route('crm.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('crm.*') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-white shadow-md shadow-amber-500/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <i class="fa fa-filter-circle-dollar w-4 text-center"></i>
                        <span>CRM Pipeline</span>
                        @php $leadCount = \App\Models\Lead::count(); @endphp
                        @if($leadCount > 0)
                        <span class="ml-auto bg-slate-800 text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded-full border border-slate-700">
                            {{ $leadCount }}
                        </span>
                        @endif
                    </a>
                    @endif

                    @if(auth()->user()->canAccess('meetings'))
                    <a href="{{ route('meetings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('meetings.*') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-white shadow-md shadow-amber-500/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <i class="fa fa-calendar-check w-4 text-center"></i>
                        <span>Meetings Schedule</span>
                        @php $upcomingCount = \App\Models\Meeting::where('status', 'scheduled')->where('date', '>=', now()->toDateString())->count(); @endphp
                        @if($upcomingCount > 0)
                        <span class="ml-auto bg-blue-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            {{ $upcomingCount }}
                        </span>
                        @endif
                    </a>
                    @endif

                    @if(auth()->user()->canAccess('requisitions'))
                    <a href="{{ route('requisitions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('requisitions.*') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-white shadow-md shadow-amber-500/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <i class="fa fa-clipboard-check w-4 text-center"></i>
                        <span>Requisitions Hub</span>
                        @php $pendingCount = \App\Models\Requisition::where('status', 'pending')->count(); @endphp
                        @if($pendingCount > 0)
                        <span class="ml-auto bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">
                            {{ $pendingCount }}
                        </span>
                        @endif
                    </a>
                    @endif
                    @endif

                    <!-- CLIENT OPERATIONS -->
                    @if(auth()->user()->canAccess('clients'))
                    <div class="px-3 pt-4 pb-2 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">Client Operations</div>

                    <a href="{{ route('clients.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('clients.*') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-white shadow-md shadow-amber-500/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <i class="fa fa-users w-4 text-center"></i>
                        <span>Clients Management</span>
                    </a>
                    @endif

                    <!-- AGENCY MANAGEMENT -->
                    @if(auth()->user()->canAccess('team') || auth()->user()->canAccess('services'))
                    <div class="px-3 pt-4 pb-2 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">Agency Management</div>

                    @if(auth()->user()->canAccess('team'))
                    <a href="{{ route('team.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('team.*') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-white shadow-md shadow-amber-500/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <i class="fa fa-user-gear w-4 text-center"></i>
                        <span>Team & Roles</span>
                    </a>
                    @endif

                    @if(auth()->user()->canAccess('services'))
                    <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('services.*') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-white shadow-md shadow-amber-500/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <i class="fa fa-tags w-4 text-center"></i>
                        <span>Services Catalog</span>
                    </a>
                    @endif
                    @endif

                    <!-- FINANCIAL OVERSIGHT -->
                    @if(auth()->user()->isOwner())
                    <div class="px-3 pt-4 pb-2 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">Financial Oversight</div>

                    <a href="{{ route('invoices.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('invoices.*') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-white shadow-md shadow-amber-500/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <i class="fa fa-file-invoice-dollar w-4 text-center"></i>
                        <span>Invoices</span>
                    </a>

                    <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('expenses.*') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-white shadow-md shadow-amber-500/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <i class="fa fa-receipt w-4 text-center"></i>
                        <span>Expenses</span>
                    </a>

                    <!-- SYSTEM CONFIGURATION -->
                    <div class="px-3 pt-4 pb-2 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">System Control</div>

                    <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('settings.*') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-white shadow-md shadow-amber-500/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <i class="fa fa-sliders w-4 text-center"></i>
                        <span>Settings</span>
                    </a>
                    @endif
                </nav>
            </div>

            <!-- Sidebar Footer Logout -->
            <div class="p-4 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-xs font-bold text-rose-400 bg-rose-500/10 border border-rose-500/20 hover:bg-rose-500/20 transition-all">
                        <i class="fa fa-sign-out-alt"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-slate-500 hover:text-slate-800">
                        <i class="fa fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-base font-bold text-slate-900 tracking-tight">
                        @yield('header_title', 'Super Admin Overview')
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Environment Badge -->
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-ping"></span>
                        MariaDB Live Persistence
                    </span>

                    @auth
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-slate-100 transition-all">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-white text-xs" style="background-color: {{ auth()->user()->color ?? '#f59e0b' }}">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <span class="text-xs font-bold text-slate-700 hidden sm:inline">{{ auth()->user()->name }}</span>
                            <i class="fa fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>

                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-1 z-20">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-slate-500">{{ auth()->user()->email ?? auth()->user()->username }}</p>
                            </div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 flex items-center gap-2">
                                    <i class="fa fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </div>
            </header>

            <!-- Page Content Body -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 bg-slate-50">
                <!-- Session Flash Messages -->
                @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="fa fa-check-circle text-emerald-500 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fa fa-times"></i></button>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="fa fa-exclamation-triangle text-rose-500 text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700"><i class="fa fa-times"></i></button>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
