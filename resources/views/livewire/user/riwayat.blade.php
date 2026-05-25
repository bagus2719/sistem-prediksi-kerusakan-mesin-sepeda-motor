<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">
                Riwayat <span class="text-indigo-600">Diagnosa</span>
            </h1>
            <p class="text-gray-500 max-w-2xl text-sm">
                Lihat kembali rekam jejak konsultasi dan prediksi kerusakan sepeda motor yang pernah Anda lakukan sebelumnya.
            </p>
        </div>
        <div>
            <a href="{{ route('prediksi') }}" wire:navigate class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 shadow-sm hover:shadow-md">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Diagnosa Baru
            </a>
        </div>
    </div>

    <!-- Content Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        @if(count($riwayats) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Kendaraan</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Gejala</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Hasil Prediksi</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Tingkat Kepercayaan</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($riwayats as $r)
                            <tr class="hover:bg-gray-50/50 transition-colors duration-150 group">
                                
                                <!-- Tanggal -->
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="text-gray-900 font-medium">{{ $r->created_at->format('d M Y') }}</div>
                                            <div class="text-gray-400 text-xs">{{ $r->created_at->format('H:i') }} WIB</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kendaraan -->
                                <td class="px-6 py-5">
                                    <div class="text-gray-900 font-medium">{{ $r->motor ? $r->motor->nama_motor : '-' }}</div>
                                    <div class="text-gray-500 text-xs">{{ $r->sistem_pembakaran ?? 'Umum' }}</div>
                                </td>

                                <!-- Gejala -->
                                <td class="px-6 py-5">
                                    @if(is_array($r->gejala_dipilih))
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($r->gejala_dipilih as $g)
                                                @php $namaGejala = $gejalaMap[$g] ?? $g; @endphp
                                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-md text-xs font-medium bg-gray-100 text-gray-700" title="{{ $namaGejala }}">
                                                    {{ $namaGejala }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic text-xs">Tidak ada data</span>
                                    @endif
                                </td>

                                <!-- Hasil Prediksi -->
                                <td class="px-6 py-5">
                                    @if($r->kerusakan)
                                        <div class="text-gray-900 font-medium">{{ $r->kerusakan->nama_kerusakan }}</div>
                                        <div class="text-gray-500 text-xs truncate max-w-[200px]" title="{{ $r->kerusakan->solusi }}">
                                            {{ \Illuminate\Support\Str::limit($r->kerusakan->solusi, 40) }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Tidak diketahui</span>
                                    @endif
                                </td>

                                <!-- Confidence -->
                                <td class="px-6 py-5 text-right whitespace-nowrap">
                                    @php
                                        $conf = (float) $r->confidence;
                                        $colorClass = $conf >= 80 ? 'text-emerald-600 bg-emerald-50 ring-emerald-500/20' : 
                                                    ($conf >= 50 ? 'text-amber-600 bg-amber-50 ring-amber-500/20' : 
                                                    'text-rose-600 bg-rose-50 ring-rose-500/20');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $colorClass }}">
                                        {{ number_format($conf, 1) }}%
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-5 text-center">
                                    <a href="{{ route('riwayat.detail', $r->id) }}" wire:navigate class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        Lihat Detail
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                <div class="w-24 h-24 mb-6 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-300">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Riwayat Diagnosa</h3>
                <p class="text-gray-500 max-w-sm mb-6 text-sm">
                    Anda belum pernah melakukan konsultasi atau diagnosa kerusakan. Lakukan diagnosa pertama Anda sekarang!
                </p>
                <a href="{{ route('prediksi') }}" wire:navigate class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 shadow-sm hover:shadow-md">
                    Mulai Diagnosa Sekarang
                </a>
            </div>
        @endif

    </div>
</div>