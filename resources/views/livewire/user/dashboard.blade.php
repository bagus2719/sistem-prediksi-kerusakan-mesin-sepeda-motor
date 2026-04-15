<div class="w-full flex flex-col gap-8 animate-[fade-in-up_0.6s_ease-out]">
    <!-- HERO SECTION -->
    <section class="relative overflow-hidden bg-gradient-to-br from-indigo-900 via-slate-800 to-blue-900 rounded-[2.5rem] shadow-2xl text-white px-8 py-16 md:px-16 md:py-20 lg:py-24">
        <!-- Abstract Shapes -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[30rem] h-[30rem] bg-indigo-500 rounded-full mix-blend-screen filter blur-[80px] opacity-40"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-[20rem] h-[20rem] bg-blue-500 rounded-full mix-blend-screen filter blur-[60px] opacity-30"></div>

        <div class="relative z-10 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-sm font-medium mb-6">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                Prediksi Cerdas Matic 4-Tak
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6 tracking-tight">
                Kenali Masalah Motor Anda <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-indigo-300">Sebelum Terlambat.</span>
            </h1>
            
            <p class="text-lg md:text-xl text-indigo-100/90 mb-10 max-w-2xl font-light leading-relaxed">
                Sistem pakar diagnosis kerusakan mesin sepeda motor. Dirancang khusus untuk menganalisis gejala tidak wajar menggunakan metode komputasi cerdas (Decision Tree).
            </p>
            
            <div class="flex flex-wrap items-center gap-4">
                @auth
                    <a href="/prediksi" class="bg-white text-indigo-900 px-8 py-4 rounded-2xl font-bold hover:bg-indigo-50 transition-all duration-300 shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-1 flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Mulai Analisis Sekarang
                    </a>
                @else
                    <a href="/login" class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-indigo-500 transition-all duration-300 shadow-xl shadow-indigo-600/30 hover:-translate-y-1">
                        Login untuk Mulai
                    </a>
                    <a href="#info" class="px-8 py-4 rounded-2xl font-medium text-white/90 hover:text-white bg-white/5 hover:bg-white/10 transition-colors duration-300 backdrop-blur-sm border border-white/10 hidden sm:inline-flex">
                        Pelajari Lebih Lanjut
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <p class="text-slate-500 font-medium mb-1">Total Algoritma Mengenali</p>
                <h3 class="text-4xl font-extrabold text-slate-800">14<span class="text-lg text-slate-400 font-medium ml-1.5">Jenis Gejala</span></h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                    <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <p class="text-slate-500 font-medium mb-1">Dapat Mengidentifikasi</p>
                <h3 class="text-4xl font-extrabold text-slate-800">17<span class="text-lg text-slate-400 font-medium ml-1.5">Kerusakan Mesin</span></h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <p class="text-slate-500 font-medium mb-1">Sistem Pendukung Keputusan</p>
                <h3 class="text-4xl font-extrabold text-slate-800">C4.5<span class="text-lg text-slate-400 font-medium ml-1.5">Metode</span></h3>
            </div>
        </div>
    </div>

    <!-- ABOUT SECTION -->
    <section id="info" class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 mt-2 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-slate-50 rounded-bl-full -mr-10 -mt-10 opacity-50"></div>
        <div class="relative z-10 flex flex-col md:flex-row gap-12 items-center">
            <div class="flex-1">
                <div class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-600 text-xs font-extrabold tracking-widest rounded-full uppercase mb-6">Tentang AI Mekanik</div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-6 tracking-tight">Ketahui Kerusakan Tanpa Harus Bongkar Mesin Dulu</h2>
                <p class="text-slate-600 leading-relaxed text-lg mb-8">
                    Sistem prediksi MotorCare berperan sebagai mekanik virtual Anda. Dengan hanya mencentang beberapa gejala yang rasional sesuai dengan kondisi motor, sistem akan menganalisa dan mencocokkan pola tersebut dengan ribuan basis data historis secara instan dan cermat.
                </p>
                <div class="flex flex-wrap gap-4">
                    <span class="bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2 border border-slate-100 shadow-sm"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Cegah Turun Mesin</span>
                    <span class="bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2 border border-slate-100 shadow-sm"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Hemat Biaya Analisis</span>
                </div>
            </div>
            
            <!-- Live Preview UI Widget -->
            <div class="w-full md:w-5/12 bg-indigo-50/50 rounded-[2rem] p-6 text-indigo-900 border border-indigo-100 flex flex-col gap-4 relative shadow-inner">
                <div class="absolute -right-5 -top-5 w-14 h-14 bg-amber-400 rounded-full flex items-center justify-center text-white text-xl shadow-xl shadow-amber-400/40 transform rotate-12">✨</div>
                
                <h4 class="font-bold flex items-center gap-2 text-sm uppercase tracking-wider text-indigo-700"><span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span> Simulasi Diagnosa</h4>
                
                <div class="bg-white p-3.5 rounded-xl text-sm shadow-sm border border-slate-100 font-semibold flex items-center gap-3">
                    <div class="w-5 h-5 bg-indigo-600 rounded flex items-center justify-center text-white"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg></div>
                    Suara kasar area CVT
                </div>
                <div class="bg-white p-3.5 rounded-xl text-sm shadow-sm border border-slate-100 font-semibold flex items-center gap-3">
                    <div class="w-5 h-5 bg-indigo-600 rounded flex items-center justify-center text-white"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg></div>
                    Tarikan bergetar / gredek
                </div>
                
                <div class="flex justify-center py-2">
                    <svg class="w-6 h-6 text-indigo-300 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                </div>
                
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-5 rounded-[1.5rem] shadow-xl shadow-indigo-600/20 relative overflow-hidden hover:scale-[1.02] transition-transform">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-10 rounded-full blur-2xl transform translate-x-4 -translate-y-4"></div>
                    <div class="text-xs font-bold text-indigo-200 mb-1.5 uppercase tracking-wide">Output AI Sistem</div>
                    <div class="font-extrabold text-xl flex items-center gap-2">⚠️ Kampas Ganda Aus</div>
                </div>
            </div>
        </div>
    </section>
</div>