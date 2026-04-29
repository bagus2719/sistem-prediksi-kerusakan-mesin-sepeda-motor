<div>
    <div class="mb-6">
        <a href="{{ route('admin.training') }}" wire:navigate class="text-base font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-2 mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Data Training
        </a>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Baris Dataset C4.5</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 max-w-4xl">
        <form wire:submit="update" class="space-y-8">
            
            <!-- Kerusakan (Label) -->
            <div>
                <label for="kerusakan" class="block text-base font-semibold text-slate-700 mb-2">Keputusan Kerusakan (Class Label) <span class="text-rose-500">*</span></label>
                <select id="kerusakan" wire:model="kerusakan_id" 
                    class="w-full md:w-1/2 bg-slate-50 border border-slate-200 text-slate-800 text-base rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors">
                    <option value="">-- Pilih Jenis Kerusakan --</option>
                    @foreach($allKerusakans as $k)
                        <option value="{{ $k->id }}">{{ $k->kode }} - {{ $k->nama_kerusakan }}</option>
                    @endforeach
                </select>
                @error('kerusakan_id') <span class="text-rose-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <hr class="border-slate-100">

            <!-- Grid Gejala -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Ceklis Gejala yang Terjadi</h3>
                    <span class="text-sm bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg font-semibold ring-1 ring-emerald-500/20">Centang kotak untuk Yes (1), biarkan kosong untuk No (0).</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($allGejalas as $g)
                    <div class="flex items-start gap-3 p-4 rounded-xl border border-slate-100 hover:border-indigo-200 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="gejala_{{ $g->id }}" wire:model="gejala_input.{{ $g->kode }}" value="1" 
                                class="w-5 h-5 text-indigo-600 bg-slate-100 border-slate-300 rounded focus:ring-indigo-500 focus:ring-2">
                        </div>
                        <div class="text-base">
                            <label for="gejala_{{ $g->id }}" class="font-bold text-slate-700 cursor-pointer block select-none">
                                {{ $g->kode }}
                            </label>
                            <p class="text-slate-500 text-sm mt-0.5 select-none">{{ $g->nama_gejala }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl text-base font-bold shadow-sm transition-colors">
                    Simpan Perubahan
                </button>
            </div>
            
        </form>
    </div>
</div>
