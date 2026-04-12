<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- <livewire:layout.navigation /> -->

        <!-- SIDEBAR -->
    <aside class="w-64 bg-blue-600 text-white p-5">
        <h1 class="text-xl font-bold mb-6">C4.5 System</h1>

        <nav class="space-y-3">
            <a href="/dashboard" class="block hover:bg-blue-700 p-2 rounded">Dashboard</a>
            <a href="/prediksi" class="block hover:bg-blue-700 p-2 rounded">Prediksi</a>
            <a href="/dataset" class="block hover:bg-blue-700 p-2 rounded">Dataset</a>
            <a href="/rules" class="block hover:bg-blue-700 p-2 rounded">Rules</a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1">

        <!-- TOPBAR -->
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h2 class="font-semibold text-lg">
                {{ $header ?? 'Dashboard' }}
            </h2>

            <div class="flex items-center gap-4">
                <span>{{ auth()->user()->name }}</span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-500">Logout</button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>