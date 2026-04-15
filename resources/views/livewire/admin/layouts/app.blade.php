<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'MotorCare Admin') }}</title>

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS / App / Styling -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body { font-family: 'Outfit', sans-serif; }
        
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 antialiased selection:bg-indigo-500 selection:text-white relative overflow-x-hidden">

    <!-- Background Decor (same vibe as user) -->
    <div class="fixed top-0 -right-40 w-[30rem] h-[30rem] bg-indigo-300 rounded-full mix-blend-multiply filter blur-[120px] opacity-20 animate-blob pointer-events-none z-0"></div>
    <div class="fixed bottom-0 -left-20 w-[30rem] h-[30rem] bg-blue-300 rounded-full mix-blend-multiply filter blur-[120px] opacity-20 animate-blob animation-delay-2000 pointer-events-none z-0"></div>

    <!-- Mobile Header -->
    <div class="sm:hidden flex items-center justify-between p-4 bg-white/80 backdrop-blur-xl border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <a href="/admin/dashboard" class="flex items-center gap-3">
            <div class="w-8 h-8 bg-gradient-to-tr from-indigo-600 to-blue-500 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-md">M</div>
            <span class="text-xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 to-blue-600">Admin</span>
        </a>
        <button data-drawer-target="sidebar" data-drawer-toggle="sidebar" class="text-slate-500 hover:text-indigo-600 focus:outline-none p-2 rounded-lg bg-slate-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 z-40 w-72 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-white/70 backdrop-blur-2xl border-r border-white shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
        <div class="h-full flex flex-col pt-6 pb-4 sidebar-scroll overflow-y-auto">
            
            <div class="px-6 mb-8 flex items-center gap-3 group">
                <div class="w-12 h-12 bg-gradient-to-tr from-indigo-600 to-blue-500 rounded-xl flex items-center justify-center text-white font-extrabold text-2xl shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                    M
                </div>
                <div>
                    <span class="block text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 to-blue-600 tracking-tight leading-none">MotorCare</span>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Admin Panel</span>
                </div>
            </div>

            <!-- Profile Snippet -->
            <div class="px-6 mb-8">
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50/50 rounded-2xl p-4 border border-indigo-100/50 shadow-sm flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold border border-white shadow-sm">
                        A
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-xs text-slate-500">Root Access</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1.5">
                <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 mt-4">Menu Utama</p>
                
                <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }} group flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-200">
                    <svg class="w-5 h-5 {{ request()->is('admin/dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>

                <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 mt-8">Master Data</p>

                <a href="/admin/gejala" class="{{ request()->is('admin/gejala*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-indigo-600' }} group flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-200">
                    <svg class="w-5 h-5 {{ request()->is('admin/gejala*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Data Gejala
                </a>

                <a href="/admin/kerusakan" class="{{ request()->is('admin/kerusakan*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-indigo-600' }} group flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-200">
                    <svg class="w-5 h-5 {{ request()->is('admin/kerusakan*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Data Kerusakan
                </a>

                <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 mt-8">Algoritma C4.5</p>

                <a href="/admin/training" class="{{ request()->is('admin/training*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-indigo-600' }} group flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-200">
                    <svg class="w-5 h-5 {{ request()->is('admin/training*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    Dataset Training
                </a>

                <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 mt-8">Monitoring</p>

                <a href="/admin/riwayat" class="{{ request()->is('admin/riwayat*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-indigo-600' }} group flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-200">
                    <svg class="w-5 h-5 {{ request()->is('admin/riwayat*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat User
                </a>
            </nav>

            <div class="px-6 mt-8">
                <form method="POST" action="/logout">
                    @csrf
                    <button class="w-full flex justify-center items-center gap-2 bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white px-4 py-2.5 rounded-xl font-semibold transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="p-4 sm:ml-72 relative z-10">
        
        <!-- Top App Bar -->
        <header class="hidden sm:flex justify-between items-center mb-8 bg-white/60 backdrop-blur-xl border border-white rounded-2xl p-4 shadow-sm">
            <h2 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 to-blue-600">Administrator Console</h2>
            <div class="flex items-center gap-4 text-slate-500">
                <p class="text-sm font-medium">{{ now()->format('l, d F Y') }}</p>
                <div class="w-2 h-2 bg-indigo-400 rounded-full animate-pulse"></div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl shadow-sm flex items-center gap-3">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-2xl shadow-sm flex items-center gap-3">
                <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>