<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Kategori Kerusakan</h2>
            <p class="text-slate-500 text-base mt-1">Daftar jenis kerusakan beserta perbaikan/solusinya.</p>
        </div>
        <a href="{{ route('admin.kerusakan.create') }}" wire:navigate class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-base font-semibold shadow-sm flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Kerusakan
        </a>
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-base text-left">
                <thead class="text-sm text-slate-500 uppercase bg-slate-50/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-bold tracking-wider">Kode</th>
                        <th class="px-6 py-4 font-bold tracking-wider">Nama Kerusakan</th>
                        <th class="px-6 py-4 font-bold tracking-wider">Solusi/Tindakan</th>
                        <th class="px-6 py-4 font-bold tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kerusakans as $k)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-md bg-amber-50 text-amber-700 font-bold text-sm ring-1 ring-inset ring-amber-700/10">
                                {{ $k->kode }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700">{{ $k->nama_kerusakan }}</td>
                        <td class="px-6 py-4 text-slate-500 truncate max-w-xs">{{ $k->solusi ?: '-' }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.kerusakan.edit', $k->id) }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition-colors shadow-sm">
                                Edit
                            </a>
                            <button wire:click="delete({{ $k->id }})" wire:confirm="Yakin ingin menghapus kerusakan ini?" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-colors shadow-sm">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                            Belum ada data kerusakan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>