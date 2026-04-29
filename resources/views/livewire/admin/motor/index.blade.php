<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Data Motor Master</h2>
            <p class="text-slate-500 text-base mt-1">Kelola daftar kendaraan bermotor (Merek, Nama Motor, dan Sistem Pembakaran).</p>
        </div>
        <a href="{{ route('admin.motor.create') }}" wire:navigate class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-base font-semibold shadow-sm flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Motor
        </a>
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-base text-left">
                <thead class="text-sm text-slate-500 uppercase bg-slate-50/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-bold tracking-wider">Merek</th>
                        <th class="px-6 py-4 font-bold tracking-wider">Nama Motor</th>
                        <th class="px-6 py-4 font-bold tracking-wider">Sistem Pembakaran</th>
                        <th class="px-6 py-4 font-bold tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($motors as $m)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-md {{ $m->merk === 'HONDA' ? 'bg-red-50 text-red-700 ring-red-700/10' : 'bg-blue-50 text-blue-700 ring-blue-700/10' }} font-bold text-sm ring-1 ring-inset">
                                {{ $m->merk }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700">{{ $m->nama_motor }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-md {{ $m->sistem_pembakaran === 'Injeksi' ? 'bg-emerald-50 text-emerald-700 ring-emerald-700/10' : 'bg-amber-50 text-amber-700 ring-amber-700/10' }} font-bold text-sm ring-1 ring-inset">
                                {{ $m->sistem_pembakaran ?: '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.motor.edit', $m->id) }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition-colors shadow-sm">
                                Edit
                            </a>
                            <button wire:click="delete({{ $m->id }})" wire:confirm="Yakin ingin menghapus referensi motor ini?" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-colors shadow-sm">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                            Belum ada data motor.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
