@if(isset($node['type']))
    @if($node['type'] === 'leaf')
        <div class="ml-6 mt-2 p-3 bg-rose-50 border border-rose-200 rounded-xl inline-block shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold text-slate-800 text-base">
                    Keputusan: 
                    <span class="text-rose-600">
                        @if(isset($kerusakans[$node['class']]))
                            {{ $kerusakans[$node['class']]->kode }} - {{ $kerusakans[$node['class']]->nama_kerusakan }}
                        @else
                            {{ $node['class'] }}
                        @endif
                    </span>
                </span>
            </div>
            
            @if(isset($node['probabilities']) && is_array($node['probabilities']))
                <div class="mt-2 pl-7 space-y-1">
                    @foreach($node['probabilities'] as $k_id => $conf)
                        <div class="text-sm font-semibold text-slate-600 flex items-center justify-between gap-4">
                            <span>
                                @if(isset($kerusakans[$k_id]))
                                    {{ $kerusakans[$k_id]->kode }}
                                @else
                                    {{ $k_id }}
                                @endif
                            </span>
                            <span class="bg-white px-2 py-0.5 rounded border border-slate-200 text-xs">{{ $conf }}%</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @elseif($node['type'] === 'node')
        <div class="ml-6 mt-3 relative border-l-2 border-indigo-200 pl-6 pb-2">
            <div class="absolute -left-3 top-0 bg-white p-1">
                <div class="w-4 h-4 rounded-full border-4 border-indigo-500 bg-white"></div>
            </div>
            
            <div class="bg-indigo-50 border border-indigo-100 px-4 py-2 rounded-lg inline-block font-bold text-indigo-900 text-base shadow-sm mb-2">
                Pertanyaan: 
                <span class="text-indigo-600">
                    @if(isset($gejalas[$node['attribute']]))
                        [{{ $node['attribute'] }}] {{ $gejalas[$node['attribute']]->nama_gejala }}
                    @else
                        {{ $node['attribute'] }}
                    @endif
                </span> ?
            </div>

            <div class="mt-2 space-y-4">
                @foreach($node['children'] as $value => $childNode)
                    <div>
                        <span class="inline-block px-3 py-1 bg-slate-100 text-slate-700 font-bold text-sm rounded-md border border-slate-200 shadow-sm ml-6 relative">
                            <!-- Hubungan visual -->
                            <div class="absolute -left-6 top-1/2 w-6 h-0.5 bg-slate-200"></div>
                            @if($value === 1 || $value === '1')
                                Ya (1)
                            @elseif($value === 0 || $value === '0')
                                Tidak (0)
                            @else
                                {{ $value }}
                            @endif
                        </span>
                        
                        <!-- Rekursif pemanggilan -->
                        @include('livewire.admin.algoritma.tree-node', ['node' => $childNode, 'gejalas' => $gejalas, 'kerusakans' => $kerusakans])
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endif
