<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotorCare - Prediksi Cerdas</title>
    <!-- Modern Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 antialiased relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">
    <!-- Background Decor -->
    <div class="fixed top-0 left-0 w-full h-1 bg-indigo-600 z-50"></div>

    <nav class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            
            <a href="{{ auth()->check() ? '/dashboard' : '/' }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-blue-500 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                    M
                </div>
                <span class="text-xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 to-blue-600 tracking-tight">
                    MotorCare
                </span>
            </a>

            <div class="flex items-center space-x-8 text-base font-medium">
                <a href="{{ auth()->check() ? '/dashboard' : '/' }}" class="text-slate-600 hover:text-indigo-600 transition-colors duration-200 relative group">
                    Dashboard
                    <span class="absolute -bottom-1.5 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-300 group-hover:w-full rounded-full"></span>
                </a>

                @auth
                    <a href="/prediksi" class="text-slate-600 hover:text-indigo-600 transition-colors duration-200 relative group">
                        Prediksi
                        <span class="absolute -bottom-1.5 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-300 group-hover:w-full rounded-full"></span>
                    </a>

                    <a href="/riwayat" class="text-slate-600 hover:text-indigo-600 transition-colors duration-200 relative group">
                        Riwayat
                        <span class="absolute -bottom-1.5 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-300 group-hover:w-full rounded-full"></span>
                    </a>
                @endauth

                @auth
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-slate-700 hover:text-indigo-600 transition-colors focus:outline-none" data-dropdown-toggle="user-dropdown">
                            <div class="w-9 h-9 bg-gradient-to-br from-indigo-100 to-blue-50 border border-indigo-200 text-indigo-700 rounded-full flex items-center justify-center font-bold shadow-sm group-hover:shadow-md transition-all">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </button>

                        <div id="user-dropdown" class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-lg border border-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right scale-95 group-hover:scale-100 z-50">
                            <div class="p-5 border-b border-slate-100 bg-slate-50 rounded-t-2xl">
                                <p class="text-base font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-slate-500 mt-1 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="p-2">
                                <a href="/profile" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Pengaturan Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-slate-100 pt-1">
                                    @csrf
                                    <button class="w-full flex items-center gap-3 text-left px-4 py-3 text-base font-medium text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar Akun
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <a href="/login" class="text-base font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 px-6 py-3 rounded-xl transition-colors duration-300 text-center">Login</a>
                        <a href="/register" class="text-base font-semibold bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl transition-colors duration-300 text-center">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10 relative z-10 w-full">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>