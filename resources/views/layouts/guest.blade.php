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
            .fade-in-up {
                animation: fadeInUp 0.6s ease-out;
            }
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-50 min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 selection:bg-indigo-500 selection:text-white">

        <div class="fade-in-up">
            <a href="/" class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-extrabold text-3xl shadow-sm">
                    M
                </div>
                <span class="text-3xl font-extrabold text-slate-800 tracking-tight">
                    MotorCare
                </span>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-10 px-8 py-10 bg-white shadow-sm border border-slate-200 rounded-[2rem] overflow-hidden relative z-10 fade-in-up" style="animation-delay: 0.1s; animation-fill-mode: both;">
            {{ $slot }}
        </div>
    </body>
</html>
