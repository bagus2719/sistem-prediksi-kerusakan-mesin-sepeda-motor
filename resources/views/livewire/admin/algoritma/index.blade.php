<div>
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Algoritma C4.5 Engine</h2>
            <p class="text-slate-500 text-base mt-1">Bangun model pohon keputusan (Decision Tree) dari dataset latih Anda.</p>
        </div>
        <div class="flex items-center gap-3">


            <button wire:click="generateModel" wire:loading.attr="disabled" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl text-base font-bold shadow-sm flex items-center gap-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="generateModel">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </span>
                <span wire:loading wire:target="generateModel">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </span>
                Generate Model C4.5
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-sm">
            <svg class="w-6 h-6 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h4 class="font-bold text-base">Sukses</h4>
                <p class="text-base mt-1">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-sm">
            <svg class="w-6 h-6 text-rose-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h4 class="font-bold text-base">Error</h4>
                <p class="text-base mt-1">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-xl font-bold text-slate-800 mb-4">Model Aktif</h3>
        
        @if ($activeModel)
            <div class="mb-4">
                <span class="inline-flex items-center gap-1.5 py-2 px-4 rounded-md text-sm font-bold bg-emerald-100 text-emerald-800">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Model ID #{{ $activeModel->id }} (Dibuat: {{ $activeModel->created_at->format('d M Y H:i') }})
                </span>
            </div>

            <div class="bg-white rounded-xl p-6 overflow-x-auto shadow-inner border border-slate-100">
                @if(is_array($activeModel->tree_data) && !empty($activeModel->tree_data))
                    <div class="py-4">
                        @include('livewire.admin.algoritma.tree-node', ['node' => $activeModel->tree_data, 'gejalas' => $gejalas, 'kerusakans' => $kerusakans])
                    </div>
                @else
                    <p class="text-slate-500 font-bold text-center py-4">Data pohon kosong.</p>
                @endif
            </div>
        @else
            <div class="text-center py-10 bg-slate-50 border border-dashed border-slate-300 rounded-xl">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-4 text-base font-bold text-slate-800">Belum Ada Model</h3>
                <p class="mt-2 text-base text-slate-500">Sistem belum pernah dilatih. Silakan klik tombol "Generate Model" di atas.</p>
            </div>
        @endif
    </div>


</div>
