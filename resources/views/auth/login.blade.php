<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — DMS CRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: { brand: { 500: '#f59e0b', 600: '#d97706' } }
                }
            }
        }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(30, 41, 59, 0.5); border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(245, 158, 11, 0.4); border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(245, 158, 11, 0.7); }
    </style>
</head>
<body class="min-h-full font-sans antialiased text-slate-100 flex items-center justify-center p-4 sm:p-6 lg:p-8 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-800 via-slate-900 to-black">

    <div class="w-full max-w-5xl my-auto">
        <!-- Top Agency Branding -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-500 text-white font-extrabold text-2xl shadow-lg shadow-amber-500/30 mb-2">
                D
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">DMS <span class="text-amber-500">CRM</span></h1>
            <p class="text-xs font-semibold text-slate-400">Digital Marketing Agency Operations Suite v2.0</p>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

            <!-- LEFT COLUMN: AUTHENTICATION FORM (5 cols) -->
            <div class="lg:col-span-5 flex flex-col justify-between bg-slate-800/80 backdrop-blur-xl border border-slate-700/80 rounded-3xl p-6 sm:p-8 shadow-2xl">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-xl font-extrabold text-white">Welcome back</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                            v2.0 Secure
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mb-6">Sign in to access your role dashboard</p>

                    @if($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs font-semibold flex items-center gap-2">
                        <i class="fa fa-circle-exclamation text-rose-400"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                    @endif

                    <div id="loginFeedback" class="hidden mb-4 p-3 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs font-bold flex items-center gap-2">
                        <i class="fa fa-spinner fa-spin text-amber-400"></i>
                        <span id="feedbackText">Logging in...</span>
                    </div>

                    <form id="loginForm" action="{{ route('login.post') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Username</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 text-sm">
                                    <i class="fa fa-user"></i>
                                </span>
                                <input type="text" name="username" id="username" value="{{ old('username') }}" required placeholder="e.g. admin or rahim"
                                       class="w-full pl-10 pr-4 py-2.5 bg-slate-900/90 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all font-medium">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 text-sm">
                                    <i class="fa fa-lock"></i>
                                </span>
                                <input type="password" name="password" id="password" required placeholder="••••••••"
                                       class="w-full pl-10 pr-10 py-2.5 bg-slate-900/90 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all font-medium">
                                <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 transition-colors">
                                    <i id="toggleEyeIcon" class="fa fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center text-xs text-slate-400 font-medium cursor-pointer">
                                <input type="checkbox" name="remember" class="rounded border-slate-700 bg-slate-900 text-amber-500 focus:ring-amber-500 mr-2"> Remember me
                            </label>
                            <span class="text-[11px] text-slate-500 font-medium">Internal Agency System</span>
                        </div>

                        <button type="submit" id="submitBtn" class="w-full py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-amber-500/25 transition-all flex items-center justify-center gap-2">
                            <span>Sign In to Dashboard</span>
                            <i class="fa fa-arrow-right text-xs"></i>
                        </button>
                    </form>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-700/60 text-[11px] text-slate-500 text-center">
                    Click any shortcut card on the right for <strong class="text-slate-400 font-bold">1-click instant login</strong>.
                </div>
            </div>

            <!-- RIGHT COLUMN: ALL TIER SHORTCUT LOGIN CREDENTIALS (7 cols) -->
            <div class="lg:col-span-7 bg-slate-800/60 backdrop-blur-xl border border-slate-700/80 rounded-3xl p-6 sm:p-7 shadow-2xl flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-700/60 gap-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-sm border border-amber-500/30">
                                <i class="fa fa-bolt-lightning"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-white flex items-center gap-2">
                                    All Tier Shortcut Login Credentials
                                </h3>
                                <p class="text-[11px] text-slate-400">Select any role below to autofill and instantly sign in.</p>
                            </div>
                        </div>

                        <!-- Auto-Login Toggle -->
                        <div class="flex items-center gap-2 text-xs">
                            <label class="flex items-center gap-1.5 cursor-pointer bg-slate-900/60 px-3 py-1.5 rounded-xl border border-slate-700/60 text-slate-300 text-[11px] font-semibold hover:border-slate-600 transition-all">
                                <input type="checkbox" id="instantSubmit" checked class="rounded border-slate-700 bg-slate-900 text-amber-500 focus:ring-amber-500">
                                <span>Instant Auto-Login</span>
                            </label>
                        </div>
                    </div>

                    <!-- Shortcut Credentials Grid -->
                    @php
                        $tiers = [
                            [
                                'role'        => 'Owner / Super Admin',
                                'category'    => 'Management',
                                'name'        => 'Mehedi Hasan',
                                'username'    => 'admin',
                                'password'    => 'admin123',
                                'icon'        => 'fa-crown',
                                'badge'       => 'bg-amber-500/15 text-amber-300 border-amber-500/30',
                                'permissions' => 'Full access, Requisitions, Finance & Settings',
                            ],
                            [
                                'role'        => 'Agency Manager / Admin',
                                'category'    => 'Management',
                                'name'        => 'Farhan Manager',
                                'username'    => 'manager',
                                'password'    => 'manager123',
                                'icon'        => 'fa-shield-halved',
                                'badge'       => 'bg-indigo-500/15 text-indigo-300 border-indigo-500/30',
                                'permissions' => 'Operations oversight, Client 360, Invoices',
                            ],
                            [
                                'role'        => 'Sales Executive',
                                'category'    => 'Sales & Business',
                                'name'        => 'Rahim Uddin',
                                'username'    => 'rahim',
                                'password'    => 'rahim123',
                                'icon'        => 'fa-briefcase',
                                'badge'       => 'bg-blue-500/15 text-blue-300 border-blue-500/30',
                                'permissions' => 'Leads, Followups, Meetings (Stage-Locked)',
                            ],
                            [
                                'role'        => 'Social Media Manager',
                                'category'    => 'Content & SMM',
                                'name'        => 'Sara Begum',
                                'username'    => 'sara',
                                'password'    => 'sara123',
                                'icon'        => 'fa-hashtag',
                                'badge'       => 'bg-orange-500/15 text-orange-300 border-orange-500/30',
                                'permissions' => 'Editorial Calendar, 6-Stage Approval, Clients',
                            ],
                            [
                                'role'        => 'Graphic Designer',
                                'category'    => 'Creative Production',
                                'name'        => 'Rafi Ahmed',
                                'username'    => 'rafi',
                                'password'    => 'rafi123',
                                'icon'        => 'fa-palette',
                                'badge'       => 'bg-pink-500/15 text-pink-300 border-pink-500/30',
                                'permissions' => 'My Tasks, Content Calendar, Attachments',
                            ],
                            [
                                'role'        => 'Motion Designer',
                                'category'    => 'Creative Production',
                                'name'        => 'Nafi Islam',
                                'username'    => 'nafi',
                                'password'    => 'nafi123',
                                'icon'        => 'fa-film',
                                'badge'       => 'bg-teal-500/15 text-teal-300 border-teal-500/30',
                                'permissions' => 'Motion Deliverables, My Tasks, Revisions',
                            ],
                            [
                                'role'        => 'Video Editor',
                                'category'    => 'Creative Production',
                                'name'        => 'Tanvir Editor',
                                'username'    => 'tanvir',
                                'password'    => 'tanvir123',
                                'icon'        => 'fa-video',
                                'badge'       => 'bg-rose-500/15 text-rose-300 border-rose-500/30',
                                'permissions' => 'Video Cuts, My Tasks, Deliverables',
                            ],
                            [
                                'role'        => 'SEO & Web Developer',
                                'category'    => 'Technical & Web',
                                'name'        => 'Tariq Hassan',
                                'username'    => 'tariq',
                                'password'    => 'tariq123',
                                'icon'        => 'fa-chart-line',
                                'badge'       => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30',
                                'permissions' => 'SEO Tasks, Website Projects, Deliverables',
                            ],
                            [
                                'role'        => 'Media Buyer / Ads Manager',
                                'category'    => 'Performance Marketing',
                                'name'        => 'Sabbir Ads',
                                'username'    => 'sabbir',
                                'password'    => 'sabbir123',
                                'icon'        => 'fa-bullseye',
                                'badge'       => 'bg-sky-500/15 text-sky-300 border-sky-500/30',
                                'permissions' => 'Meta/Google Ad Campaigns, Tasks',
                            ],
                            [
                                'role'        => 'General Team Member',
                                'category'    => 'Operations',
                                'name'        => 'Joy Team',
                                'username'    => 'joy',
                                'password'    => 'joy123',
                                'icon'        => 'fa-user-check',
                                'badge'       => 'bg-slate-500/15 text-slate-300 border-slate-500/30',
                                'permissions' => 'My Assigned Tasks, Worklogs, Calendar',
                            ],
                        ];
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mt-4 max-h-[440px] overflow-y-auto pr-1 custom-scrollbar">
                        @foreach($tiers as $tier)
                        <div onclick="applyCredentials('{{ $tier['username'] }}', '{{ $tier['password'] }}', '{{ $tier['role'] }}')"
                             class="group p-3 rounded-2xl bg-slate-900/60 hover:bg-slate-900/90 border border-slate-700/60 hover:border-amber-500/50 shadow-sm transition-all cursor-pointer flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md border flex items-center gap-1.5 {{ $tier['badge'] }}">
                                        <i class="fa {{ $tier['icon'] }} text-[9px]"></i>
                                        {{ $tier['role'] }}
                                    </span>
                                    <span class="opacity-0 group-hover:opacity-100 text-[10px] text-amber-400 font-bold transition-opacity flex items-center gap-1">
                                        <span>Click to sign in</span>
                                        <i class="fa fa-arrow-right text-[8px]"></i>
                                    </span>
                                </div>
                                <div class="text-xs font-bold text-white group-hover:text-amber-400 transition-colors">
                                    {{ $tier['name'] }}
                                </div>
                                <div class="text-[10px] text-slate-400 line-clamp-1 mt-0.5">
                                    {{ $tier['permissions'] }}
                                </div>
                            </div>

                            <div class="mt-2.5 pt-2 border-t border-slate-800 flex items-center justify-between text-[11px] font-mono">
                                <span class="text-slate-400 font-medium">User: <strong class="text-slate-200">{{ $tier['username'] }}</strong></span>
                                <span class="text-slate-400 font-medium">Pass: <strong class="text-slate-200">{{ $tier['password'] }}</strong></span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer helper -->
                <div class="mt-4 pt-3 border-t border-slate-700/60 flex items-center justify-between text-[11px] text-slate-400">
                    <span class="flex items-center gap-1.5">
                        <i class="fa fa-circle-info text-amber-400"></i>
                        <span>10 active tier profiles configured for instant pair-testing</span>
                    </span>
                    <button type="button" onclick="fillOnlyFirst()" class="text-amber-400 hover:text-amber-300 font-bold text-[10px] uppercase tracking-wider">
                        Fill Admin Only
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
    function applyCredentials(username, password, roleName) {
        const uInput = document.getElementById('username');
        const pInput = document.getElementById('password');
        const instant = document.getElementById('instantSubmit').checked;
        const feedback = document.getElementById('loginFeedback');
        const feedbackText = document.getElementById('feedbackText');

        uInput.value = username;
        pInput.value = password;

        // Visual highlight effect
        uInput.classList.add('border-amber-400', 'ring-2', 'ring-amber-500/50');
        pInput.classList.add('border-amber-400', 'ring-2', 'ring-amber-500/50');
        setTimeout(() => {
            uInput.classList.remove('border-amber-400', 'ring-2', 'ring-amber-500/50');
            pInput.classList.remove('border-amber-400', 'ring-2', 'ring-amber-500/50');
        }, 1000);

        if (instant) {
            feedback.classList.remove('hidden');
            feedbackText.innerText = 'Signing in as ' + roleName + ' (' + username + ')...';
            document.getElementById('submitBtn').disabled = true;

            setTimeout(() => {
                document.getElementById('loginForm').submit();
            }, 250);
        }
    }

    function fillOnlyFirst() {
        document.getElementById('username').value = 'admin';
        document.getElementById('password').value = 'admin123';
    }

    function togglePasswordVisibility() {
        const pInput = document.getElementById('password');
        const icon = document.getElementById('toggleEyeIcon');
        if (pInput.type === 'password') {
            pInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            pInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye-gray', 'fa-eye');
        }
    }
    </script>
</body>
</html>
