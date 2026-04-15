<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Riwayat Prediksi</h2>
            <p class="text-slate-500 text-sm mt-1">Daftar rekaman diagnostik kerusakan yang pernah diajukan oleh pengguna sistem.</p>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-bold tracking-wider">Tgl / Waktu</th>
                        <th class="px-6 py-4 font-bold tracking-wider">Nama Pengguna</th>
                        <th class="px-6 py-4 font-bold tracking-wider">Hasil Diagnosis</th>
                        <th class="px-6 py-4 font-bold tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayats as $r)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4 text-slate-500">
                            {{ $r->created_at->format('d M Y - H:i') }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700">
                            {{ $r->user->name ?? 'Pengguna Anonim' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 font-bold text-xs ring-1 ring-inset ring-indigo-700/10">
                                {{ $r->kerusakan->nama_kerusakan ?? 'Tidak Diketahui' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button wire:click="delete({{ $r->id }})" wire:confirm="Yakin ingin menghapus riwayat diagnostik ini?" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-colors shadow-sm">
                                Hapus Log
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                            Belum ada riwayat prediksi yang tercatat dari pengguna.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>