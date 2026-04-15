<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Data Gejala Master</h2>
            <p class="text-slate-500 text-sm mt-1">Kelola daftar gejala kerusakan yang bisa dialami sepeda motor.</p>
        </div>
        <a href="{{ route('admin.gejala.create') }}" wire:navigate class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-0.5 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Gejala
        </a>
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-bold tracking-wider">Kode</th>
                        <th class="px-6 py-4 font-bold tracking-wider">Gejala</th>
                        <th class="px-6 py-4 font-bold tracking-wider">Keterangan</th>
                        <th class="px-6 py-4 font-bold tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($gejalas as $g)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 font-bold text-xs ring-1 ring-inset ring-indigo-700/10">
                                {{ $g->kode }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700">{{ $g->nama_gejala }}</td>
                        <td class="px-6 py-4 text-slate-500 truncate max-w-xs">{{ $g->keterangan ?: '-' }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.gejala.edit', $g->id) }}" wire:navigate class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition-colors shadow-sm">
                                Edit
                            </a>
                            <button wire:click="delete({{ $g->id }})" wire:confirm="Yakin ingin menghapus gejala ini?" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-colors shadow-sm">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                            Belum ada data gejala.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>