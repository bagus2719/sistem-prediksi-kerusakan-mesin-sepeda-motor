<div class="w-full flex flex-col gap-8 animate-[fade-in-up_0.6s_ease-out]">
    <!-- HERO SECTION -->
    <section class="bg-indigo-600 rounded-3xl shadow-sm text-white px-8 py-16 md:px-16 md:py-20 lg:py-24">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/50 border border-indigo-400 text-sm font-medium mb-6">
                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                Prediksi Cerdas Matic 4-Tak
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6 tracking-tight">
                Kenali Masalah Motor Anda Sebelum Terlambat.
            </h1>
            
            <p class="text-lg md:text-xl text-indigo-100 mb-10 max-w-2xl font-light leading-relaxed">
                Sistem pakar diagnosis kerusakan mesin sepeda motor. Dirancang khusus untuk menganalisis gejala tidak wajar menggunakan metode komputasi cerdas (Decision Tree).
            </p>
            
            <div class="flex flex-wrap items-center gap-4">
                @auth
                    <a href="/prediksi" class="bg-white text-indigo-600 px-8 py-4 rounded-xl font-bold hover:bg-slate-50 transition-colors duration-200 flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Mulai Analisis Sekarang
                    </a>
                @else
                    <a href="/login" class="bg-white text-indigo-600 px-8 py-4 rounded-xl font-bold hover:bg-slate-50 transition-colors duration-200">
                        Login untuk Mulai
                    </a>
                    <a href="#info" class="px-8 py-4 rounded-xl font-medium text-white hover:bg-indigo-700 transition-colors duration-200 border border-indigo-400 hidden sm:inline-flex">
                        Pelajari Lebih Lanjut
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-start gap-4">
            <div class="w-12 h-12 shrink-0 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <p class="text-slate-500 font-medium text-sm">Total Gejala Dikenali</p>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $total_gejala }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-start gap-4">
            <div class="w-12 h-12 shrink-0 bg-rose-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <p class="text-slate-500 font-medium text-sm">Identifikasi Kerusakan</p>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $total_kerusakan }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-start gap-4">
            <div class="w-12 h-12 shrink-0 bg-amber-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <p class="text-slate-500 font-medium text-sm">Sistem Prediksi</p>
                <h3 class="text-3xl font-extrabold text-slate-800">C4.5</h3>
            </div>
        </div>
    </div>
    <!-- RECENT DIAGNOSTICS (IF LOGGED IN) -->
    @auth
    @if(count($riwayatTerakhir) > 0)
    <div class="mt-8 bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Diagnosa Terakhir Anda
            </h2>
            <a href="/riwayat" class="text-sm font-bold text-indigo-600 hover:text-indigo-700">Lihat Semua &rarr;</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($riwayatTerakhir as $riwayat)
            <div class="border border-slate-100 bg-slate-50 rounded-2xl p-5 hover:border-indigo-200 hover:shadow-md transition-all group">
                <div class="flex justify-between items-start mb-3">
                    <span class="text-xs font-bold bg-white px-2.5 py-1 rounded-md text-slate-500 border border-slate-200">
                        {{ $riwayat->created_at->format('d M Y') }}
                    </span>
                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md">
                        {{ $riwayat->motor_name ?? 'Umum' }}
                    </span>
                </div>
                <h3 class="font-bold text-slate-800 text-lg mb-1">{{ $riwayat->kerusakan->nama_kerusakan }}</h3>
                <p class="text-sm text-slate-500 line-clamp-2">{{ $riwayat->kerusakan->solusi }}</p>
                <div class="mt-4 pt-4 border-t border-slate-200 flex justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="/riwayat/{{ $riwayat->id }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                        Detail Analisis <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endauth

    <!-- HOW TO USE SECTION -->
    <div class="mt-8 mb-2 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-indigo-600 rounded-3xl p-8 shadow-sm text-white flex flex-col justify-center relative overflow-hidden">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-6">
                    <span class="text-xl font-black">1</span>
                </div>
                <h3 class="text-xl font-bold mb-2">Pilih Tipe Motor</h3>
                <p class="text-indigo-100 text-sm">Pilih jenis motor Anda (Injeksi / Karburator) agar sistem menyesuaikan pola akar masalah.</p>
            </div>
            <svg class="absolute -bottom-4 -right-4 w-32 h-32 text-indigo-500/50" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
        </div>

        <div class="bg-indigo-700 rounded-3xl p-8 shadow-sm text-white flex flex-col justify-center relative overflow-hidden">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-6">
                    <span class="text-xl font-black">2</span>
                </div>
                <h3 class="text-xl font-bold mb-2">Centang Gejala</h3>
                <p class="text-indigo-100 text-sm">Pilih semua gejala fisik yang motor Anda rasakan. Usahakan akurat sesuai kondisi asli.</p>
            </div>
            <svg class="absolute -bottom-4 -right-4 w-32 h-32 text-indigo-600/50" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        </div>

        <div class="bg-slate-800 rounded-3xl p-8 shadow-sm text-white flex flex-col justify-center relative overflow-hidden">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center mb-6">
                    <span class="text-xl font-black">3</span>
                </div>
                <h3 class="text-xl font-bold mb-2">Dapatkan Hasil</h3>
                <p class="text-slate-300 text-sm">AI akan menganalisa dan memberikan persentase prediksi kerusakan beserta solusinya.</p>
            </div>
            <svg class="absolute -bottom-4 -right-4 w-32 h-32 text-slate-700/50" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"></path></svg>
        </div>
    </div>

    <!-- ABOUT SECTION -->
    <section id="info" class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200 mt-2">
        <div class="flex flex-col md:flex-row gap-12 items-center">
            <div class="flex-1">
                <div class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold tracking-wider rounded-md uppercase mb-4">Tentang AI Mekanik</div>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-4 tracking-tight">Ketahui Kerusakan Tanpa Bongkar Mesin</h2>
                <p class="text-slate-600 leading-relaxed mb-6">
                    Sistem prediksi MotorCare berperan sebagai mekanik virtual Anda. Dengan hanya mencentang beberapa gejala yang rasional sesuai dengan kondisi motor, sistem akan menganalisa dan mencocokkan pola tersebut dengan ribuan basis data historis secara instan dan cermat.
                </p>
                <div class="flex flex-wrap gap-3">
                    <span class="bg-slate-50 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 border border-slate-200"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Cegah Turun Mesin</span>
                    <span class="bg-slate-50 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 border border-slate-200"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Hemat Biaya Analisis</span>
                </div>
            </div>
            
            <!-- Live Preview UI Widget -->
            <div class="w-full md:w-5/12 bg-slate-50 rounded-2xl p-6 border border-slate-200 flex flex-col gap-3">
                <h4 class="font-semibold flex items-center gap-2 text-sm text-slate-700 mb-2">Simulasi Diagnosa</h4>
                
                <div class="bg-white p-3 rounded-lg text-sm shadow-sm border border-slate-100 flex items-center gap-3">
                    <div class="w-5 h-5 bg-slate-200 rounded flex items-center justify-center text-slate-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg></div>
                    Suara kasar area CVT
                </div>
                <div class="bg-white p-3 rounded-lg text-sm shadow-sm border border-slate-100 flex items-center gap-3">
                    <div class="w-5 h-5 bg-slate-200 rounded flex items-center justify-center text-slate-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg></div>
                    Tarikan bergetar / gredek
                </div>
                
                <div class="flex justify-center py-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                </div>
                
                <div class="bg-indigo-600 text-white p-4 rounded-xl shadow-sm">
                    <div class="text-xs font-semibold text-indigo-200 mb-1 uppercase tracking-wider">Output AI Sistem</div>
                    <div class="font-bold text-lg">⚠️ Kampas Ganda Aus</div>
                </div>
            </div>
        </div>
    </section>
</div>