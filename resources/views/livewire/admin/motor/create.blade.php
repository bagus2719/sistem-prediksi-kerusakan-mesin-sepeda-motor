<div>
    <div class="mb-6">
        <a href="{{ route('admin.motor.index') }}" wire:navigate class="text-base font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-2 mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Motor
        </a>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Tambah Motor</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 max-w-2xl">
        <form wire:submit="save" class="space-y-6">
            
            <!-- Merek -->
            <div>
                <label for="merk" class="block text-base font-semibold text-slate-700 mb-2">Merek <span class="text-rose-500">*</span></label>
                <select id="merk" wire:model="merk" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-base rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors">
                    <option value="">-- Pilih Merek --</option>
                    <option value="HONDA">HONDA</option>
                    <option value="YAMAHA">YAMAHA</option>
                </select>
                @error('merk') <span class="text-rose-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Nama Motor -->
            <div>
                <label for="nama_motor" class="block text-base font-semibold text-slate-700 mb-2">Nama Motor <span class="text-rose-500">*</span></label>
                <input type="text" id="nama_motor" wire:model="nama_motor" placeholder="Misal: Vario 160" 
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-base rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors">
                @error('nama_motor') <span class="text-rose-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Sistem Pembakaran -->
            <div>
                <label for="sistem_pembakaran" class="block text-base font-semibold text-slate-700 mb-2">Sistem Pembakaran <span class="text-rose-500">*</span></label>
                <select id="sistem_pembakaran" wire:model="sistem_pembakaran" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-base rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors">
                    <option value="">-- Pilih Sistem --</option>
                    <option value="Injeksi">Injeksi (FI)</option>
                    <option value="Karburator">Karburator</option>
                </select>
                @error('sistem_pembakaran') <span class="text-rose-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-base font-bold shadow-sm transition-colors">
                    Simpan Motor Baru
                </button>
            </div>
            
        </form>
    </div>
</div>
