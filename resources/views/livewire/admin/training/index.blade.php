<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Dataset Training C4.5</h2>
            <p class="text-slate-500 text-sm mt-1">Data historis untuk pembelajaran algoritma *decision tree* (pohon keputusan).</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.training.create') }}" wire:navigate class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Satu Baris
            </a>
        </div>
    </div>

    <!-- Modul Import CSV -->
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-2">Import Masal via CSV</h3>
        <p class="text-sm text-slate-500 mb-4">Unggah file CSV Anda (Format Header: G01, G02, ..., G14, KERUSAKAN) untuk populasi cepat ratusan dataset sekaligus.</p>
        
        <form wire:submit="importCSV" class="flex flex-col sm:flex-row items-end gap-4">
            <div class="w-full sm:w-auto flex-1">
                <label for="csv_file" class="block text-xs font-semibold text-slate-700 mb-1">Pilih File (.csv)</label>
                <input type="file" id="csv_file" wire:model="file_import" accept=".csv,.txt"
                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-xl cursor-pointer bg-slate-50">
            </div>
            <div class="w-full sm:w-auto">
                <button type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto text-center px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="importCSV">Upload & Import</span>
                    <span wire:loading wire:target="importCSV" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </form>
        @error('file_import') <span class="text-rose-500 text-xs font-medium mt-2 block">{{ $message }}</span> @enderror
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto max-h-[70vh]">
            <table class="w-full text-sm text-center border-collapse">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50/50 border-b border-slate-100 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-4 font-bold tracking-wider text-left bg-slate-50/90 backdrop-blur">Keputusan (Kerusakan)</th>
                        @foreach($allGejalas as $g)
                            <th class="px-2 py-4 font-bold tracking-wider min-w-[50px] bg-slate-50/90 backdrop-blur border-l border-slate-100/50" title="{{ $g->nama_gejala }}">{{ $g->kode }}</th>
                        @endforeach
                        <th class="px-4 py-4 font-bold tracking-wider text-right bg-slate-50/90 backdrop-blur border-l border-slate-100/50">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($trainings as $t)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-4 py-4 text-left font-semibold text-slate-700">
                            {{ $t->kerusakan->nama_kerusakan ?? 'Unknown' }}
                        </td>
                        @foreach($allGejalas as $g)
                            <td class="px-2 py-4 border-l border-slate-100/50">
                                @if(isset($t->data_gejala[$g->kode]) && $t->data_gejala[$g->kode] == 1)
                                    <span class="inline-flex w-6 h-6 items-center justify-center rounded-md bg-emerald-100 text-emerald-700 font-bold text-xs ring-1 ring-inset ring-emerald-700/10">1</span>
                                @else
                                    <span class="inline-flex w-6 h-6 items-center justify-center rounded-md bg-slate-100 text-slate-400 font-bold text-xs">0</span>
                                @endif
                            </td>
                        @endforeach
                        <td class="px-4 py-4 text-right space-x-2 border-l border-slate-100/50 whitespace-nowrap">
                            <a href="{{ route('admin.training.edit', $t->id) }}" wire:navigate class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition-colors shadow-sm">
                                Edit
                            </a>
                            <button wire:click="delete({{ $t->id }})" wire:confirm="Yakin ingin menghapus data training ini?" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-colors shadow-sm">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($allGejalas) + 2 }}" class="px-6 py-10 text-center text-slate-500">
                            Belum ada data training. Algoritma C4.5 belum bisa belajar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>