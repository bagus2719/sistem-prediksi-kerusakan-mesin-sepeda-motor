<div>
    <div class="mb-6">
        <a href="{{ route('admin.gejala') }}" wire:navigate class="text-base font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-2 mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Gejala
        </a>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Tambah Gejala</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 max-w-2xl">
        <form wire:submit="store" class="space-y-6">
            
            <!-- Kode Gejala -->
            <div>
                <label for="kode" class="block text-base font-semibold text-slate-700 mb-2">Kode Gejala <span class="text-rose-500">*</span></label>
                <input type="text" id="kode" wire:model="kode" placeholder="Misal: G15" 
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-base rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors">
                @error('kode') <span class="text-rose-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Nama Gejala -->
            <div>
                <label for="nama_gejala" class="block text-base font-semibold text-slate-700 mb-2">Nama Gejala <span class="text-rose-500">*</span></label>
                <input type="text" id="nama_gejala" wire:model="nama_gejala" placeholder="Deskripsi singkat gejala..." 
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-base rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors">
                @error('nama_gejala') <span class="text-rose-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Kategori Sistem Kendaraan -->
            <div>
                <label for="sistem_pembakaran" class="block text-base font-semibold text-slate-700 mb-2">Kategori Sistem Kendaraan <span class="text-rose-500">*</span></label>
                <select id="sistem_pembakaran" wire:model="sistem_pembakaran" 
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-base rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors">
                    <option value="Keduanya">Umum (Bisa Injeksi maupun Karburator)</option>
                    <option value="Injeksi">Hanya Injeksi (Sensors, FI, dll)</option>
                    <option value="Karburator">Hanya Karburator (Spuyer, Choke, dll)</option>
                </select>
                @error('sistem_pembakaran') <span class="text-rose-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                <p class="text-sm text-slate-500 mt-2">Gejala ini hanya akan muncul di halaman prediksi jika user memilih jenis kendaraan yang sesuai.</p>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-base font-bold shadow-sm transition-colors">
                    Simpan Gejala Baru
                </button>
            </div>
            
        </form>
    </div>
</div>
