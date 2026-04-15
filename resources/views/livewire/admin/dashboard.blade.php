<div class="px-2">

    <!-- Title -->
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">
            Ikhtisar Sistem
        </h1>
        <p class="text-slate-500 mt-2 text-sm font-medium">
            Pantau statistik master data dan riwayat diagnostik pengguna.
        </p>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- User -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
            <div class="relative z-10 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-blue-100/50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Total Pengguna</h2>
                    <p class="text-3xl font-extrabold text-slate-800">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Gejala -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
            <div class="relative z-10 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-rose-100/50 text-rose-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Total Gejala</h2>
                    <p class="text-3xl font-extrabold text-slate-800">{{ \App\Models\Gejala::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Kerusakan -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
            <div class="relative z-10 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-amber-100/50 text-amber-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Kategori Kerusakan</h2>
                    <p class="text-3xl font-extrabold text-slate-800">{{ \App\Models\Kerusakan::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Training -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
            <div class="relative z-10 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-emerald-100/50 text-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Set Training C4.5</h2>
                    <p class="text-3xl font-extrabold text-slate-800">{{ \App\Models\Training::count() }}</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Menu -->
    <div class="mt-12">
        <h2 class="text-xl font-bold text-slate-800 mb-6">Akses Cepat</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <a href="/admin/gejala/create" class="group bg-gradient-to-br from-indigo-600 to-blue-500 p-8 rounded-3xl shadow-xl shadow-indigo-500/20 hover:-translate-y-1 transition-all overflow-hidden relative">
                <div class="absolute -right-10 -bottom-10 opacity-20 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-48 h-48 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-extrabold text-white mb-2">Tambah Gejala Baru</h3>
                    <p class="text-indigo-100 w-3/4">Indikasi kerusakan baru ditemukan? Langsung rekam ke pangkalan data master.</p>
                </div>
            </a>

            <a href="/admin/training/create" class="group bg-gradient-to-br from-emerald-500 to-teal-400 p-8 rounded-3xl shadow-xl shadow-emerald-500/20 hover:-translate-y-1 transition-all overflow-hidden relative">
                <div class="absolute -right-10 -bottom-10 opacity-20 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-48 h-48 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-extrabold text-white mb-2">Cetak Set Evaluasi</h3>
                    <p class="text-emerald-50 w-3/4">Bangun data relasional pohon keputusan C4.5 baru langsung ke dalam sistem model pakar.</p>
                </div>
            </a>

        </div>
    </div>

</div>