<div>
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Evaluasi Model C4.5</h2>
        <p class="text-slate-500 text-base mt-1">Uji keakuratan algoritma C4.5 Anda menggunakan data latih dan Confusion Matrix.</p>
    </div>

    @if (session()->has('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-sm">
            <svg class="w-6 h-6 text-rose-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h4 class="font-bold text-base">Error</h4>
                <p class="text-base mt-1">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if ($confusionMatrix !== null)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Hasil Evaluasi Keseluruhan
            </h3>
            <button wire:click="evaluateModel" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh Evaluasi
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-indigo-50 border border-indigo-100 p-6 rounded-xl text-center">
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-1">Akurasi</p>
                <p class="text-4xl font-extrabold text-indigo-900">{{ $accuracy }}%</p>
            </div>
            <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-xl text-center">
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-1">Presisi (Macro)</p>
                <p class="text-4xl font-extrabold text-emerald-900">{{ $precision ?? 0 }}%</p>
            </div>
            <div class="bg-blue-50 border border-blue-100 p-6 rounded-xl text-center">
                <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-1">Recall (Macro)</p>
                <p class="text-4xl font-extrabold text-blue-900">{{ $recall ?? 0 }}%</p>
            </div>
            <div class="bg-slate-50 border border-slate-100 p-6 rounded-xl text-center">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Total Data</p>
                <p class="text-4xl font-extrabold text-slate-800">{{ $totalData }}</p>
            </div>
        </div>

        <h4 class="font-bold text-lg text-slate-800 mb-4">Tabel Confusion Matrix</h4>
        <div class="overflow-x-auto border border-slate-200 rounded-xl">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 border-b border-r border-slate-200 bg-slate-50 sticky left-0 z-10">Asli \ Prediksi</th>
                        @foreach ($kerusakans as $id => $k)
                            <th class="px-4 py-3 border-b border-slate-200 text-center" title="{{ $k->nama_kerusakan }}">{{ $k->kode_kerusakan }}</th>
                        @endforeach
                        <th class="px-4 py-3 border-b border-slate-200 text-center text-rose-600">Unknown</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kerusakans as $actualId => $kActual)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-4 py-3 font-bold border-r border-slate-200 bg-white sticky left-0 z-10" title="{{ $kActual->nama_kerusakan }}">{{ $kActual->kode_kerusakan }}</td>
                            @foreach ($kerusakans as $predId => $kPred)
                                @php
                                    $count = $confusionMatrix[$actualId][$predId] ?? 0;
                                    $isCorrect = ($actualId == $predId);
                                    $bgColor = $isCorrect && $count > 0 ? 'bg-emerald-100 text-emerald-800 font-bold shadow-inner' : ($count > 0 ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-400');
                                @endphp
                                <td class="px-4 py-3 text-center {{ $bgColor }}">
                                    {{ $count }}
                                </td>
                            @endforeach
                            @php
                                $unknownCount = $confusionMatrix[$actualId]['Unknown'] ?? 0;
                            @endphp
                            <td class="px-4 py-3 text-center {{ $unknownCount > 0 ? 'bg-rose-100 text-rose-800 font-bold' : 'text-slate-400' }}">
                                {{ $unknownCount }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-sm text-slate-500 mt-4">* <strong>Diagonal hijau</strong> menunjukkan tebakan yang tepat (True Positive). <strong>Sisi merah</strong> menunjukkan tebakan meleset (False Positive/Negative). Hover pada kode Kxx untuk melihat nama kerusakannya.</p>
    </div>
    @else
        @if(!$activeModel)
        <div class="text-center py-10 bg-white border border-slate-200 shadow-sm rounded-xl">
            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-4 text-base font-bold text-slate-800">Belum Ada Model C4.5 Aktif</h3>
            <p class="mt-2 text-base text-slate-500">Sistem belum dilatih. Silakan menu "Algoritma C4.5" dan Generate Model terlebih dahulu.</p>
        </div>
        @endif
    @endif
</div>
