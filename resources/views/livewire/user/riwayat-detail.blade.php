<div class="max-w-5xl mx-auto pb-20 animate-[fade-in-up_0.5s_ease-out]">

    <!-- Header Section -->
    <div class="text-center mb-12 mt-4 relative">
        <span class="bg-indigo-50 text-indigo-700 px-5 py-1.5 rounded-full text-sm font-bold tracking-widest uppercase border border-indigo-100 inline-block mb-5">Detail Riwayat Prediksi</span>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-800 tracking-tight mb-5 leading-tight">
            Hasil Analisis Kendaraan
        </h1>
        <p class="text-slate-500 text-lg max-w-2xl mx-auto leading-relaxed">
            Prediksi ini dilakukan pada tanggal {{ $riwayat->created_at->format('d F Y, H:i') }} WIB.
        </p>
    </div>

    @php
        $gejalaCount = is_array($riwayat->gejala_dipilih) ? count($riwayat->gejala_dipilih) : 0;
    @endphp

    <div id="hasil-prediksi" class="mt-6">
        <div class="bg-white border border-slate-200 rounded-3xl p-8 md:p-12 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-10 pb-8 border-b border-slate-100">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center shadow-inner border border-green-100 shrink-0">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 tracking-tight">Keputusan Kerusakan</h2>
                        <p class="text-slate-500 text-lg mt-2 font-medium">Berdasarkan pola relasi pakar tingkat kerusakan {{ $gejalaCount }} gejala.</p>
                    </div>
                </div>
                <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 text-right">
                    <p class="text-sm uppercase font-extrabold tracking-wider text-indigo-400 mb-1">Kendaraan</p>
                    <p class="font-bold text-indigo-900 text-lg">{{ $riwayat->motor->nama_motor ?? 'Tidak Diketahui' }}</p>
                    <p class="text-base font-semibold text-indigo-600">{{ $riwayat->sistem_pembakaran ?? 'Sistem Tidak Diketahui' }}</p>
                </div>
            </div>

            <div class="space-y-4 mb-8">
                <div class="bg-rose-50 border-rose-200 p-8 rounded-3xl border shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-rose-500 text-white px-4 py-1 rounded-bl-xl font-bold text-sm">Kemungkinan Terbesar</div>
                    <p class="uppercase text-sm font-black tracking-[0.2em] mb-2 text-rose-500">Peringkat 1 &mdash; {{ $riwayat->confidence }}% Cocok</p>
                    <h3 class="text-3xl font-black mb-4 text-rose-700">{{ $riwayat->kerusakan->kode ?? '' }} - {{ $riwayat->kerusakan->nama_kerusakan ?? 'Tidak Diketahui' }}</h3>
                    <div class="bg-white/60 p-4 rounded-xl">
                        <p class="font-bold text-slate-800 text-base mb-1">Solusi / Penanganan:</p>
                        <p class="text-slate-700 font-medium text-base">{{ $riwayat->kerusakan->solusi ?? 'Hubungi mekanik terdekat untuk penanganan.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Gejala Yang Dialami -->
            <div class="mb-8 p-6 bg-white border border-slate-200 rounded-2xl shadow-sm">
                <h4 class="text-slate-800 font-extrabold text-lg mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Gejala Kendaraan yang Anda Laporkan
                </h4>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @if(is_array($riwayat->gejala_dipilih) && count($riwayat->gejala_dipilih) > 0)
                        @foreach($riwayat->gejala_dipilih as $g)
                        @php $namaGejala = $gejalaMap[$g] ?? $g; @endphp
                        <li class="flex items-start gap-2 text-base text-slate-600">
                            <span class="text-indigo-500 font-bold mt-0.5">&bull;</span>
                            <div>
                                <span class="font-bold text-slate-700">[{{ $g }}]</span> {{ $namaGejala }}
                            </div>
                        </li>
                        @endforeach
                    @else
                        <li class="col-span-full text-slate-500 italic">
                            Anda tidak melaporkan gejala apa pun.
                        </li>
                    @endif
                </ul>
            </div>
            
            <div class="flex justify-center flex-col items-center gap-4 border-t border-slate-100 pt-8 mt-4">
                <a href="{{ route('riwayat') }}" wire:navigate class="px-8 py-3 bg-white border-2 border-indigo-100 text-indigo-600 font-bold text-base rounded-xl hover:bg-indigo-50 transition-colors shadow-sm inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Riwayat
                </a>
            </div>
        </div>
    </div>
</div>
