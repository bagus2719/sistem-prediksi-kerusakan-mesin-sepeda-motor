<div class="max-w-5xl mx-auto pb-20 animate-[fade-in-up_0.5s_ease-out]">

    <!-- Header Section -->
    <div class="text-center mb-12 mt-4 relative">
        <span class="bg-indigo-50 text-indigo-700 px-5 py-1.5 rounded-full text-sm font-bold tracking-widest uppercase border border-indigo-100 inline-block mb-5">Diagnosa Sistem C4.5</span>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-800 tracking-tight mb-5 leading-tight">
            Apa Keluhan Motor Anda?
        </h1>
        <p class="text-slate-500 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
            Ikuti tahapan spesifikasi kendaraan dan gejala untuk mendapatkan analisis prediksi kerusakan yang presisi.
        </p>
    </div>

    <!-- Progress Steps -->
    <div class="flex justify-center mb-10">
        <div class="flex items-center gap-3">
            <span class="flex items-center justify-center w-10 h-10 rounded-full border-2 {{ $step >= 1 ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300 text-slate-400' }} font-bold text-base">1</span>
            <span class="text-base font-bold {{ $step >= 1 ? 'text-indigo-800' : 'text-slate-400' }}">Kendaraan</span>
            
            <div class="w-12 h-0.5 {{ $step >= 2 ? 'bg-indigo-600' : 'bg-slate-200' }} mx-2"></div>
            
            <span class="flex items-center justify-center w-10 h-10 rounded-full border-2 {{ $step >= 2 ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300 text-slate-400' }} font-bold text-base">2</span>
            <span class="text-base font-bold {{ $step >= 2 ? 'text-indigo-800' : 'text-slate-400' }}">Gejala</span>
            
            <div class="w-12 h-0.5 {{ $step == 3 ? 'bg-indigo-600' : 'bg-slate-200' }} mx-2"></div>
            
            <span class="flex items-center justify-center w-10 h-10 rounded-full border-2 {{ $step == 3 ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300 text-slate-400' }} font-bold text-base">3</span>
            <span class="text-base font-bold {{ $step == 3 ? 'text-indigo-800' : 'text-slate-400' }}">Prediksi</span>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-xl mb-6 flex items-center justify-center font-bold">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-slate-200 relative mb-10">
        
        <!-- STEP 1: VEHICLE INFO -->
        @if($step == 1)
        <div>
            <h2 class="text-2xl font-bold text-slate-800 mb-6 pb-4 border-b border-slate-100 text-center">Tipe Pabrikan Motor</h2>
            <div class="max-w-2xl mx-auto space-y-6">
                <!-- Select Merek -->
                <div>
                    <label class="block text-base font-semibold text-slate-700 mb-2">Pilih Merek Kendaraan</label>
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
                    <label class="block text-base font-semibold text-slate-700 mb-2">Tipe Motor {{ $selectedMerk }}</label>
                    <select wire:model.live="selectedMotorId" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-4 transition-colors font-semibold text-base">
                        <option value="">-- Pilih Model --</option>
                        @foreach($availableMotors as $m)
                            <option value="{{ $m->id }}">{{ $m->nama_motor }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Info Card Motor Terpilih --}}
                @if($selectedMotorId)
                @php $motorTerpilih = collect($availableMotors)->firstWhere('id', (int)$selectedMotorId); @endphp
                @if($motorTerpilih)
                <div class="animate-[fade-in-up_0.3s_ease-out] bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-100 rounded-2xl p-5 flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-0.5">Motor Anda</p>
                        <p class="text-lg font-extrabold text-indigo-900">{{ $motorTerpilih->nama_motor }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-0.5">Sistem</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold {{ $motorTerpilih->sistem_pembakaran == 'Injeksi' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $motorTerpilih->sistem_pembakaran }}
                        </span>
                    </div>
                </div>
                @endif
                @endif

                <div class="pt-8 flex justify-end">
                    <button wire:click="nextStep" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl text-base font-bold transition-colors">
                        Lanjut Pilih Gejala
                    </button>
                </div>
            </div>
        </div>
        @endif


        <!-- STEP 2: GEJALA SELECTION -->
        @if($step == 2)
        <div>
            {{-- Info Banner Kendaraan --}}
            @php $motorInfo = App\Models\Motor::find($selectedMotorId); @endphp
            @if($motorInfo)
            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-100 rounded-2xl p-5 mb-8 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Kendaraan Anda</p>
                        <p class="text-base font-extrabold text-indigo-900">{{ $selectedMerk }} {{ $motorInfo->nama_motor }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-sm font-bold {{ $motorInfo->sistem_pembakaran == 'Injeksi' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-amber-100 text-amber-700 border border-amber-200' }}">
                    {{ $motorInfo->sistem_pembakaran }}
                </span>
            </div>
            @endif

            <!-- Header Controls -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 pb-6 border-b border-slate-100 gap-5 md:gap-0">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                        Daftar Gejala Sistem
                    </h2>
                    <p class="text-slate-500 text-base mt-1.5 font-medium">Klik pada kartu untuk memilih kendala yang dirasakan.</p>
                </div>
                
                <div class="bg-indigo-50/50 border border-indigo-100 px-6 py-3 rounded-2xl flex items-center gap-4 shadow-sm transition-all {{ count($gejalaDipilih) > 0 ? 'bg-indigo-50 ring-2 ring-indigo-100' : '' }}">
                    <div class="w-12 h-12 {{ count($gejalaDipilih) > 0 ? 'bg-indigo-600 shadow-indigo-600/30 text-white' : 'bg-white text-indigo-600' }} rounded-xl flex items-center justify-center shadow-lg transition-colors duration-300">
                        <span class="font-extrabold text-xl">{{ count($gejalaDipilih) }}</span>
                    </div>
                    <div class="text-indigo-900 font-bold text-base leading-tight uppercase tracking-wide">
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
                                <span class="font-extrabold text-indigo-900 block text-sm mb-1">{{ $g->kode }}</span>
                                <span class="font-semibold text-slate-700 peer-checked:text-indigo-900 group-hover:text-indigo-700 transition-colors text-base">
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
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[1.5rem] blur opacity-10 group-hover:opacity-30 transition duration-300 group-disabled:hidden"></div>
                    <div class="relative bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-extrabold text-base shadow-sm flex items-center justify-center gap-3 transition-colors w-full">
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
        <div id="hasil-prediksi" class="animate-[fade-in-up_0.6s_ease-out] mt-6">
            <div class="bg-white border border-slate-200 rounded-3xl p-8 md:p-12 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-10 pb-8 border-b border-slate-100">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center shadow-inner border border-green-100 shrink-0">
                                <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 tracking-tight">Keputusan Kerusakan</h2>
                                <p class="text-slate-500 text-lg mt-2 font-medium">Berdasarkan pola relasi pakar tingkat kerusakan {{ count($gejalaDipilih) }} gejala.</p>
                            </div>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 text-right">
                            <p class="text-sm uppercase font-extrabold tracking-wider text-indigo-400 mb-1">Kendaraan</p>
                            <p class="font-bold text-indigo-900 text-lg">{{ App\Models\Motor::find($selectedMotorId)->nama_motor ?? 'Tidak Diketahui' }}</p>
                            <p class="text-base font-semibold text-indigo-600">{{ App\Models\Motor::find($selectedMotorId)->sistem_pembakaran ?? 'Sistem Tidak Diketahui' }}</p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-8">
                        @foreach($hasil['top_3'] as $index => $item)
                            <div class="{{ $index === 0 ? 'bg-rose-50 border-rose-200 p-8' : 'bg-slate-50 border-slate-200 p-6' }} rounded-3xl border shadow-sm relative overflow-hidden">
                                @if($index === 0)
                                    <div class="absolute top-0 right-0 bg-rose-500 text-white px-4 py-1 rounded-bl-xl font-bold text-sm">Kemungkinan Terbesar</div>
                                    <p class="uppercase text-sm font-black tracking-[0.2em] mb-2 text-rose-500">Peringkat {{ $index + 1 }} &mdash; {{ $item['confidence'] }}% Cocok</p>
                                    <h3 class="text-3xl font-black mb-4 text-rose-700">{{ $item['kerusakan']->kode }} - {{ $item['kerusakan']->nama_kerusakan }}</h3>
                                    <div class="bg-white/60 p-4 rounded-xl">
                                        <p class="font-bold text-slate-800 text-base mb-1">Solusi / Penanganan:</p>
                                        <p class="text-slate-700 font-medium text-base">{{ $item['kerusakan']->solusi ?: 'Hubungi mekanik terdekat untuk penanganan.' }}</p>
                                    </div>
                                @else
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div>
                                            <p class="uppercase text-xs font-bold tracking-widest text-slate-500 mb-1">Peringkat {{ $index + 1 }} &mdash; {{ $item['confidence'] }}% Cocok</p>
                                            <h3 class="text-xl font-bold text-slate-800">{{ $item['kerusakan']->kode }} - {{ $item['kerusakan']->nama_kerusakan }}</h3>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-slate-200">
                                        <p class="font-bold text-slate-800 text-sm mb-1">Solusi / Penanganan:</p>
                                        <p class="text-slate-600 text-sm">{{ $item['kerusakan']->solusi ?: 'Hubungi mekanik terdekat untuk penanganan.' }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Gejala Yang Dialami -->
                    <div class="mb-8 p-6 bg-white border border-slate-200 rounded-2xl shadow-sm">
                        <h4 class="text-slate-800 font-extrabold text-lg mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Gejala Kendaraan yang Anda Laporkan
                        </h4>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(App\Models\Gejala::whereIn('kode', $hasil['gejala_dipilih'])->get() as $g)
                            <li class="flex items-start gap-2 text-base text-slate-600">
                                <span class="text-indigo-500 font-bold mt-0.5">&bull;</span>
                                <div>
                                    <span class="font-bold text-slate-700">[{{ $g->kode }}]</span> {{ $g->nama_gejala }}
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="flex justify-center flex-col items-center gap-4">
                        <p class="text-base text-slate-400">Peringatan: Keputusan di atas didiagnosis berdasarkan model Machine Learning C4.5 dari data riwayat pakar.</p>
                        <button wire:click="ulangi" class="px-8 py-3 bg-white border-2 border-indigo-100 text-indigo-600 font-bold text-base rounded-xl hover:bg-indigo-50 transition-colors shadow-sm">
                            Konsultasi Gejala Lain
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>