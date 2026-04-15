<div class="max-w-5xl mx-auto pb-20 animate-[fade-in-up_0.5s_ease-out]">

    <!-- Header Section -->
    <div class="text-center mb-12 mt-4 relative">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-indigo-500 rounded-full mix-blend-multiply filter blur-[80px] opacity-15"></div>
        <span class="bg-white text-indigo-700 px-5 py-1.5 rounded-full text-sm font-bold tracking-widest uppercase shadow-sm border border-indigo-100 inline-block mb-5">Diagnosa Sistem C4.5</span>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-800 tracking-tight mb-5 leading-tight">
            Apa Keluhan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Motor Anda?</span>
        </h1>
        <p class="text-slate-500 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
            Ikuti tahapan spesifikasi kendaraan dan gejala untuk mendapatkan analisis prediksi kerusakan yang presisi.
        </p>
    </div>

    <!-- Progress Steps -->
    <div class="flex justify-center mb-10">
        <div class="flex items-center gap-2">
            <span class="flex items-center justify-center w-8 h-8 rounded-full border-2 {{ $step >= 1 ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300 text-slate-400' }} font-bold text-sm">1</span>
            <span class="text-sm font-bold {{ $step >= 1 ? 'text-indigo-800' : 'text-slate-400' }}">Kendaraan</span>
            
            <div class="w-12 h-0.5 {{ $step >= 2 ? 'bg-indigo-600' : 'bg-slate-200' }} mx-2"></div>
            
            <span class="flex items-center justify-center w-8 h-8 rounded-full border-2 {{ $step >= 2 ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300 text-slate-400' }} font-bold text-sm">2</span>
            <span class="text-sm font-bold {{ $step >= 2 ? 'text-indigo-800' : 'text-slate-400' }}">Gejala</span>
            
            <div class="w-12 h-0.5 {{ $step == 3 ? 'bg-indigo-600' : 'bg-slate-200' }} mx-2"></div>
            
            <span class="flex items-center justify-center w-8 h-8 rounded-full border-2 {{ $step == 3 ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300 text-slate-400' }} font-bold text-sm">3</span>
            <span class="text-sm font-bold {{ $step == 3 ? 'text-indigo-800' : 'text-slate-400' }}">Prediksi</span>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-xl mb-6 flex items-center justify-center font-bold">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-[0_8px_40px_rgb(0,0,0,0.04)] border border-slate-100 relative mb-10">
        
        <!-- STEP 1: VEHICLE INFO -->
        @if($step == 1)
        <div>
            <h2 class="text-2xl font-bold text-slate-800 mb-6 pb-4 border-b border-slate-100 text-center">Tipe Pabrikan Motor</h2>
            <div class="max-w-2xl mx-auto space-y-6">
                <!-- Select Merek -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Merek Kendaraan</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="merk" wire:model.live="selectedMerk" value="HONDA" class="peer sr-only">
                            <div class="p-4 text-center border-2 border-slate-100 bg-white rounded-2xl transition-all duration-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 hover:bg-slate-50 font-bold text-slate-500">
                                HONDA
                            </div>
                        </label>
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="merk" wire:model.live="selectedMerk" value="YAMAHA" class="peer sr-only">
                            <div class="p-4 text-center border-2 border-slate-100 bg-white rounded-2xl transition-all duration-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 hover:bg-slate-50 font-bold text-slate-500">
                                YAMAHA
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Select Model -->
                @if($selectedMerk)
                <div class="animate-[fade-in-up_0.3s_ease-out]">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tipe Motor {{ $selectedMerk }}</label>
                    <select wire:model.live="selectedMotorId" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3.5 transition-colors font-semibold">
                        <option value="">-- Pilih Model --</option>
                        @foreach($availableMotors as $m)
                            <option value="{{ $m->id }}">{{ $m->nama_motor }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Select Pembakaran -->
                @if($selectedMotorId)
                <div class="animate-[fade-in-up_0.3s_ease-out]">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Sistem Pembakaran</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="sistem" wire:model="sistem_pembakaran" value="Injeksi" class="peer sr-only">
                            <div class="p-4 text-center border-2 border-slate-100 bg-white rounded-2xl transition-all duration-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 hover:bg-slate-50 font-bold text-slate-500">
                                INJEKSI (FI)
                            </div>
                        </label>
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="sistem" wire:model="sistem_pembakaran" value="Karburator" class="peer sr-only">
                            <div class="p-4 text-center border-2 border-slate-100 bg-white rounded-2xl transition-all duration-300 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-700 hover:bg-slate-50 font-bold text-slate-500">
                                KARBURATOR
                            </div>
                        </label>
                    </div>
                </div>
                @endif

                <div class="pt-8 flex justify-end">
                    <button wire:click="nextStep" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
                        Lanjut Pilih Gejala
                    </button>
                </div>
            </div>
        </div>
        @endif


        <!-- STEP 2: GEJALA SELECTION -->
        @if($step == 2)
        <div>
            <!-- Header Controls -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 pb-6 border-b border-slate-100 gap-5 md:gap-0">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                        Daftar Gejala Sistem
                    </h2>
                    <p class="text-slate-500 text-sm mt-1.5 font-medium">Klik pada kartu untuk memilih kendala yang dirasakan.</p>
                </div>
                
                <div class="bg-indigo-50/50 border border-indigo-100 px-6 py-3 rounded-2xl flex items-center gap-4 shadow-sm transition-all {{ count($gejalaDipilih) > 0 ? 'bg-indigo-50 ring-2 ring-indigo-100' : '' }}">
                    <div class="w-12 h-12 {{ count($gejalaDipilih) > 0 ? 'bg-indigo-600 shadow-indigo-600/30 text-white' : 'bg-white text-indigo-600' }} rounded-xl flex items-center justify-center shadow-lg transition-colors duration-300">
                        <span class="font-extrabold text-xl">{{ count($gejalaDipilih) }}</span>
                    </div>
                    <div class="text-indigo-900 font-bold text-sm leading-tight uppercase tracking-wide">
                        Gejala<br/>Terpilih
                    </div>
                </div>
            </div>

            <!-- Gejala Grid checkboxes as Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($listGejala as $g)
                    <label class="cursor-pointer group relative">
                        <input type="checkbox" wire:model.live="gejalaDipilih" value="{{ $g->kode }}" class="peer sr-only">
                        
                        <div class="p-5 border-2 border-slate-100 bg-white rounded-2xl transition-all duration-300
                                    peer-checked:border-indigo-500 peer-checked:bg-gradient-to-br peer-checked:from-indigo-50/80 peer-checked:to-blue-50/30
                                    peer-checked:shadow-[0_8px_20px_rgba(79,70,229,0.12)] peer-checked:-translate-y-0.5
                                    hover:border-indigo-200 hover:bg-slate-50 hover:shadow-md
                                    flex items-start gap-4">
                            
                            <div>
                                <span class="font-extrabold text-indigo-900 block text-xs mb-1">{{ $g->kode }}</span>
                                <span class="font-semibold text-slate-700 peer-checked:text-indigo-900 group-hover:text-indigo-700 transition-colors text-sm">
                                    {{ $g->nama_gejala }}
                                </span>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <!-- Buttons -->
            <div class="mt-12 pt-8 flex justify-between border-t border-slate-100">
                <button wire:click="prevStep" class="text-slate-500 hover:text-slate-700 font-bold py-2 px-4">
                    Koreksi Motor
                </button>
                <button wire:click="prosesPrediksi" 
                        @if(count($gejalaDipilih) == 0) disabled @endif
                        class="relative group disabled:opacity-40 disabled:cursor-not-allowed w-full md:w-auto">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[1.5rem] blur opacity-25 group-hover:opacity-60 transition duration-300 group-disabled:hidden"></div>
                    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-10 py-3 rounded-[1.3em] font-extrabold text-sm shadow-xl flex items-center justify-center gap-3 transition-transform transform group-hover:scale-105 active:scale-95 duration-200 w-full">
                        @if(count($gejalaDipilih) > 0)
                            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            Proses Diagnosa
                        @else
                            Pilih minimal 1 Gejala
                        @endif
                    </div>
                </button>
            </div>
        </div>
        @endif

        <!-- STEP 3: RESULT SECTION -->
        @if($step == 3 && !empty($hasil))
        <div id="hasil-prediksi" class="animate-[fade-in-up_0.6s_ease-out]">
            <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 p-[3px] rounded-[3rem] shadow-2xl relative overflow-hidden -m-4 md:-m-8">
                <!-- Glow effect background -->
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-blue-500 to-purple-500 opacity-20 filter blur-2xl"></div>
                
                <div class="bg-white rounded-[2.8rem] p-8 md:p-12 relative z-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-10 pb-8 border-b border-slate-100">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center shadow-inner border border-green-100 shrink-0">
                                <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 tracking-tight">Keputusan Kerusakan</h2>
                                <p class="text-slate-500 text-base mt-2 font-medium">Berdasarkan pola relasi pakar tingkat kerusakan {{ count($gejalaDipilih) }} gejala.</p>
                            </div>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 text-right">
                            <p class="text-xs uppercase font-extrabold tracking-wider text-indigo-400 mb-1">Kendaraan</p>
                            <p class="font-bold text-indigo-900">{{ App\Models\Motor::find($selectedMotorId)->nama_motor ?? 'Tidak Diketahui' }}</p>
                            <p class="text-sm font-semibold text-indigo-600">{{ $sistem_pembakaran }}</p>
                        </div>
                    </div>

                    <div class="p-8 bg-slate-50 rounded-3xl border border-slate-100 mb-8 text-center text-rose-600">
                        <p class="uppercase text-sm font-black tracking-[0.2em] mb-2 text-rose-500">Hasil Keputusan Utama</p>
                        <h3 class="text-4xl font-black mb-4">{{ $hasil['kerusakan']->kode }} - {{ $hasil['kerusakan']->nama_kerusakan }}</h3>
                        <p class="text-slate-600 max-w-2xl mx-auto font-bold">{{ $hasil['kerusakan']->solusi ?: 'Hubungi mekanik terdekat untuk penanganan.' }}</p>
                    </div>
                    
                    <!-- Ranking Probabilitas -->
                    <div class="space-y-5 mb-8">
                        <h4 class="text-slate-800 font-extrabold text-xl mb-4">Rincian Probabilitas Kerusakan:</h4>
                        @php $rank = 1; @endphp
                        @foreach($hasil['top_3'] as $kodeK => $dataNilai)
                            <div class="group relative bg-white border-2 border-slate-100 p-5 md:p-6 rounded-3xl flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-indigo-200 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300">
                                
                                <!-- Left Info -->
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-extrabold text-lg shadow-sm shrink-0
                                        {{ $rank === 1 ? 'bg-gradient-to-br from-amber-400 to-amber-500 text-white shadow-amber-500/20' : 
                                          ($rank === 2 ? 'bg-gradient-to-br from-slate-300 to-slate-400 text-white' : 
                                          'bg-gradient-to-br from-orange-300 to-orange-400 text-white') }}">
                                        #{{ $rank }}
                                    </div>
                                    <div>
                                        <h3 class="text-xl md:text-2xl font-bold text-slate-800 group-hover:text-indigo-700 transition-colors">{{ App\Models\Kerusakan::where('kode', $kodeK)->first()->nama_kerusakan ?? $kodeK }}</h3>
                                        <p class="text-sm text-slate-500 font-medium mt-1">Potensi {{ $rank === 1 ? 'tertinggi' : 'alternatif' }} dari analisis mesin</p>
                                    </div>
                                </div>
                                
                                <!-- Right Score -->
                                <div class="px-5 py-3 md:py-4 bg-slate-50 group-hover:bg-indigo-50 rounded-2xl border border-slate-100 group-hover:border-indigo-100 flex items-center md:flex-col justify-center min-w-[8rem] transition-colors gap-3 md:gap-0">
                                    <span class="text-3xl font-black text-indigo-600">{{ number_format($dataNilai['persentase'], 1) }}%</span>
                                    <span class="text-[0.65rem] md:text-xs uppercase font-extrabold text-slate-400 group-hover:text-indigo-400 tracking-wider">Kecocokan Gejala</span>
                                </div>

                            </div>
                            @php $rank++; @endphp
                        @endforeach
                    </div>
                    <div class="flex justify-center flex-col items-center gap-4">
                        <p class="text-sm text-slate-400">Peringatan: Kalkulator ini menggunakan Forward Chaining Manual (C4.5 Override).</p>
                        <button wire:click="ulangi" class="px-8 py-3 bg-white border-2 border-indigo-100 text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition-colors shadow-sm">
                            Konsultasi Gejala Lain
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>