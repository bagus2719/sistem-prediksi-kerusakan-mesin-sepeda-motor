<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MotorCare') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            .fade-in-up {
                animation: fadeInUp 0.6s ease-out;
            }
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-50 min-h-screen relative overflow-hidden flex flex-col justify-center items-center pt-6 sm:pt-0 selection:bg-indigo-500 selection:text-white">
        <!-- Background Decor -->
        <div class="fixed -top-40 -right-40 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 animate-blob pointer-events-none"></div>
        <div class="fixed -bottom-40 -left-40 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 animate-blob animation-delay-2000 pointer-events-none"></div>

        <div class="fade-in-up">
            <a href="/" class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-gradient-to-tr from-indigo-600 to-blue-500 rounded-2xl flex items-center justify-center text-white font-extrabold text-3xl shadow-lg shadow-indigo-500/30">
                    M
                </div>
                <span class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 to-blue-600 tracking-tight">
                    MotorCare
                </span>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-10 px-8 py-10 bg-white/80 backdrop-blur-xl shadow-[0_8px_40px_rgb(0,0,0,0.06)] border border-white rounded-[2rem] overflow-hidden relative z-10 fade-in-up" style="animation-delay: 0.1s; animation-fill-mode: both;">
            {{ $slot }}
        </div>
    </body>
</html>
