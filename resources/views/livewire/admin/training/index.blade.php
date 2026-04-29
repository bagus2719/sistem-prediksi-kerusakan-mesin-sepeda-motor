<div>
    {{-- Inline Flash Messages (Livewire-compatible) --}}
    @if (session()->has('message'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl shadow-sm flex items-center gap-3">
            <svg class="w-6 h-6 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-2xl shadow-sm flex items-center gap-3">
            <svg class="w-6 h-6 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Dataset Training C4.5</h2>
            <p class="text-slate-500 text-base mt-1">Data historis untuk pembelajaran algoritma *decision tree* (pohon keputusan).</p>
        </div>
    </div>

    <!-- Modul Import CSV -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <h3 class="text-xl font-bold text-slate-800 mb-2">Import Masal via CSV</h3>
        <p class="text-base text-slate-500 mb-4">Unggah file CSV Anda (Format Header: G01, G02, ..., G14, KERUSAKAN) untuk populasi cepat ratusan dataset sekaligus.</p>
        
        <form wire:submit.prevent="importCSV" class="flex flex-col sm:flex-row items-end gap-4">
            <div class="w-full sm:w-auto flex-1">
                <label for="csv_file" class="block text-sm font-semibold text-slate-700 mb-2">Pilih File (.csv)</label>
                <input type="file" id="csv_file" wire:model="file_import" accept=".csv,.txt"
                    class="block w-full text-base text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-base file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-xl cursor-pointer bg-slate-50">
            </div>
            <div class="w-full sm:w-auto">
                <button type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto text-center px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-base font-bold shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="importCSV">Upload & Import</span>
                    <span wire:loading wire:target="importCSV" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </form>
        @error('file_import') <span class="text-rose-500 text-sm font-medium mt-2 block">{{ $message }}</span> @enderror
    </div>

    <!-- Tombol Aksi Tambahan -->
    <div class="flex items-center justify-end gap-3 mb-6">
        <button wire:click="runPreprocessing" wire:loading.attr="disabled" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl text-base font-bold shadow-sm flex items-center gap-2 transition-colors">
            <span wire:loading.remove wire:target="runPreprocessing" class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                Jalankan Preprocessing
            </span>
            <span wire:loading wire:target="runPreprocessing" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Memindai Data...
            </span>
        </button>
        <a href="{{ route('admin.training.create') }}" wire:navigate class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-base font-semibold shadow-sm flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Satu Baris
        </a>
    </div>

    <!-- PREPROCESSING REPORT -->
    @if(!empty($preprocessingReport))
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 mb-6 shadow-sm">
        <h3 class="text-emerald-800 font-extrabold text-xl mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Laporan Preprocessing
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="bg-white p-4 rounded-xl border border-emerald-100">
                <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Total Awal</p>
                <p class="text-3xl font-black text-slate-700">{{ $preprocessingReport['awal'] }}</p>
            </div>
            <div class="bg-rose-50 p-4 rounded-xl border border-rose-200">
                <p class="text-sm font-bold text-rose-600 uppercase tracking-widest mb-1">Missing Value</p>
                <p class="text-3xl font-black text-rose-700">{{ $preprocessingReport['invalid_dihapus'] }}</p>
            </div>
            <div class="bg-amber-50 p-4 rounded-xl border border-amber-200">
                <p class="text-sm font-bold text-amber-600 uppercase tracking-widest mb-1">Duplikat Identik</p>
                <p class="text-3xl font-black text-amber-700">{{ $preprocessingReport['duplikat_dihapus'] }}</p>
            </div>
            <div class="bg-emerald-100 p-4 rounded-xl border border-emerald-300">
                <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest mb-1">Dataset Bersih</p>
                <p class="text-3xl font-black text-emerald-800">{{ $preprocessingReport['akhir'] }}</p>
            </div>
        </div>
        <p class="text-sm text-emerald-700 mt-4 font-medium">*Dataset telah diringkas. Mesin pembelajaran algoritma C4.5 kini dapat bekerja lebih ringan dan menghindari overfitting.</p>
    </div>
    @endif
    
    <!-- TABLE CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto max-h-[70vh]">
            <table class="w-full text-base text-center border-collapse">
                <thead class="text-sm text-slate-500 uppercase bg-slate-50 border-b border-slate-200 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-4 font-bold tracking-wider text-left bg-slate-50 w-48">Keputusan</th>
                        <th class="px-4 py-4 font-bold tracking-wider text-left bg-slate-50 border-l border-slate-200">Profil Kendaraan</th>
                        @foreach($allGejalas as $g)
                            <th class="px-2 py-4 font-bold tracking-wider min-w-[50px] bg-slate-50 border-l border-slate-200" title="{{ $g->nama_gejala }}">{{ $g->kode }}</th>
                        @endforeach
                        <th class="px-4 py-4 font-bold tracking-wider text-right bg-slate-50 border-l border-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($trainings as $t)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-4 py-4 text-left font-semibold text-slate-700 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-bold bg-rose-50 text-rose-700 mb-1 block w-max">{{ $t->kerusakan->kode ?? '-' }}</span>
                            {{ $t->kerusakan->nama_kerusakan ?? 'Unknown' }}
                        </td>
                        <td class="px-4 py-4 text-left border-l border-slate-200">
                            @if($t->motor)
                                <div class="font-bold text-indigo-900 text-base">{{ $t->motor->merk }} {{ $t->motor->nama_motor }}</div>
                                <div class="text-sm text-indigo-600 font-semibold">{{ $t->motor->sistem_pembakaran }}</div>
                            @else
                                <span class="text-slate-400 italic text-sm">Semua Motor</span>
                            @endif
                        </td>
                        @foreach($allGejalas as $g)
                            <td class="px-2 py-4 border-l border-slate-200">
                                @if(isset($t->data_gejala[$g->kode]) && $t->data_gejala[$g->kode] == 1)
                                    <span class="inline-flex w-8 h-8 items-center justify-center rounded-md bg-emerald-100 text-emerald-700 font-bold text-sm ring-1 ring-inset ring-emerald-700/10">1</span>
                                @else
                                    <span class="inline-flex w-8 h-8 items-center justify-center rounded-md bg-slate-100 text-slate-400 font-bold text-sm">0</span>
                                @endif
                            </td>
                        @endforeach
                        <td class="px-4 py-4 text-right space-x-2 border-l border-slate-200 whitespace-nowrap">
                            <a href="{{ route('admin.training.edit', $t->id) }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition-colors shadow-sm">
                                Edit
                            </a>
                            <button wire:click="delete({{ $t->id }})" wire:confirm="Yakin ingin menghapus data training ini?" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-colors shadow-sm">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($allGejalas) + 3 }}" class="px-6 py-10 text-center text-slate-500 text-lg">
                            Belum ada data training. Algoritma C4.5 belum bisa belajar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>