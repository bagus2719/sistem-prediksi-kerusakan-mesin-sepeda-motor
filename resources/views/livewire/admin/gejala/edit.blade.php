<div>
    <div class="mb-6">
        <a href="{{ route('admin.gejala') }}" wire:navigate class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-2 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Gejala
        </a>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Gejala</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6 max-w-2xl">
        <form wire:submit="update" class="space-y-6">
            
            <!-- Kode Gejala -->
            <div>
                <label for="kode" class="block text-sm font-semibold text-slate-700 mb-2">Kode Gejala <span class="text-rose-500">*</span></label>
                <input type="text" id="kode" wire:model="kode" 
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors">
                @error('kode') <span class="text-rose-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Nama Gejala -->
            <div>
                <label for="nama_gejala" class="block text-sm font-semibold text-slate-700 mb-2">Nama Gejala <span class="text-rose-500">*</span></label>
                <input type="text" id="nama_gejala" wire:model="nama_gejala" 
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors">
                @error('nama_gejala') <span class="text-rose-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Keterangan -->
            <div>
                <label for="keterangan" class="block text-sm font-semibold text-slate-700 mb-2">Keterangan (Opsional)</label>
                <textarea id="keterangan" wire:model="keterangan" rows="3" 
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors"></textarea>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
                    Simpan Perubahan
                </button>
            </div>
            
        </form>
    </div>
</div>
