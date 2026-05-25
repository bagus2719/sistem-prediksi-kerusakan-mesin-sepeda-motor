@if(isset($node['type']))
    @if($node['type'] === 'leaf')
        {{-- ═══ LEAF NODE (Keputusan Akhir) ═══ --}}
        <div class="inline-block p-4 bg-gradient-to-r from-rose-50 to-red-50 border border-rose-200 rounded-xl shadow-sm whitespace-nowrap">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-rose-100 flex items-center justify-center shrink-0 border border-rose-200">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-rose-400 uppercase tracking-widest">Keputusan</span>
                    <span class="font-extrabold text-rose-700 text-sm leading-tight">
                        @if(isset($kerusakans[$node['class']]))
                            {{ $kerusakans[$node['class']]->kode }} - {{ $kerusakans[$node['class']]->nama_kerusakan }}
                        @else
                            {{ $node['class'] }}
                        @endif
                    </span>
                </div>
            </div>
            @if(isset($node['probabilities']) && is_array($node['probabilities']))
                <div class="mt-3 pt-3 border-t border-rose-100 space-y-1">
                    @foreach($node['probabilities'] as $k_id => $conf)
                        <div class="text-xs font-bold text-slate-600 flex items-center justify-between gap-6">
                            <span class="flex items-center gap-1.5">
                                <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                                {{ isset($kerusakans[$k_id]) ? $kerusakans[$k_id]->kode : $k_id }}
                            </span>
                            <span class="bg-white px-2 py-0.5 rounded border border-slate-200 text-[10px] text-rose-600">{{ $conf }}%</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    @elseif($node['type'] === 'node')
        {{-- ═══ INTERNAL NODE (Pertanyaan / Cabang) ═══ --}}
        <div x-data="{ open: true }">
            {{-- Box Pertanyaan --}}
            <div class="inline-flex items-center gap-4 bg-white border-2 border-indigo-100 px-5 py-3 rounded-xl font-bold text-slate-800 shadow-sm cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/50 transition-all group whitespace-nowrap" @click="open = !open">
                <div>
                    <span class="text-[10px] text-indigo-400 uppercase tracking-widest block mb-0.5">Pertanyaan</span>
                    <span class="text-indigo-700 text-sm">
                        @if($node['attribute'] === 'sistem_pembakaran')
                            <span class="bg-indigo-600 text-white px-2 py-0.5 rounded text-xs mr-1.5 font-black">ROOT</span>
                            Sistem Pembakaran
                        @elseif(isset($gejalas[$node['attribute']]))
                            <span class="bg-indigo-100 text-indigo-800 px-1.5 py-0.5 rounded text-xs mr-1.5 font-bold">{{ $node['attribute'] }}</span>
                            {{ $gejalas[$node['attribute']]->nama_gejala }}
                        @else
                            {{ $node['attribute'] }}
                        @endif
                    </span>
                    <span class="text-slate-400 font-normal">?</span>
                </div>
                <div class="w-7 h-7 rounded-full bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center text-indigo-500 transition-transform duration-300 shrink-0" :class="{'rotate-180': open}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            {{-- Children: Vertical line + branches --}}
            <div x-show="open" x-collapse class="ml-6 border-l-2 border-slate-200 mt-1">
                @foreach($node['children'] as $value => $childNode)
                    @php
                        if($value === 1 || $value === '1') {
                            $cLineBg = 'bg-emerald-400';
                            $cLabelCls = 'bg-emerald-50 text-emerald-700 border-emerald-300';
                            $cLabel = '✓ Ya';
                        } elseif ($value === 0 || $value === '0') {
                            $cLineBg = 'bg-rose-400';
                            $cLabelCls = 'bg-rose-50 text-rose-700 border-rose-300';
                            $cLabel = '✗ Tidak';
                        } else {
                            $cLineBg = 'bg-indigo-300';
                            $cLabelCls = 'bg-indigo-50 text-indigo-700 border-indigo-300';
                            $cLabel = $value;
                        }
                    @endphp
                    <div class="flex items-start py-1.5">
                        {{-- Garis horizontal + Label + Garis --}}
                        <div class="flex items-center shrink-0 mt-4">
                            <div class="w-6 h-0.5 {{ $cLineBg }}"></div>
                            <span class="px-2.5 py-0.5 {{ $cLabelCls }} font-bold text-xs rounded-md border whitespace-nowrap shadow-sm">
                                {{ $cLabel }}
                            </span>
                            <div class="w-6 h-0.5 {{ $cLineBg }}"></div>
                        </div>
                        {{-- Node anak (rekursif) --}}
                        <div>
                            @include('livewire.admin.algoritma.tree-node', ['node' => $childNode, 'gejalas' => $gejalas, 'kerusakans' => $kerusakans])
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @elseif($node['type'] === 'unknown')
        {{-- UNKNOWN NODE (Fallback) --}}
        <div class="inline-block p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm font-bold text-amber-700 whitespace-nowrap">
            ⚠ Data tidak cukup
        </div>
    @endif
@endif
