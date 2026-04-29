<?php

namespace App\Livewire\Admin\Algoritma;

use Livewire\Component;
use App\Models\C45Model;
use App\Services\C45Engine;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    public $activeModel;

    public function mount()
    {
        $this->activeModel = C45Model::where('is_active', true)->latest()->first();
    }

    public function generateModel()
    {
        try {
            $engine = new C45Engine();
            $this->activeModel = $engine->generateModel();
            
            session()->flash('message', 'Pohon Keputusan C4.5 berhasil dibangun dan siap digunakan!');
        } catch (\Exception $e) {
            Log::error('C4.5 Error: ' . $e->getMessage());
            session()->flash('error', 'Gagal memproses C4.5: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.algoritma.index', [
            'gejalas' => \App\Models\Gejala::all()->keyBy('kode'),
            'kerusakans' => \App\Models\Kerusakan::all()->keyBy('id')
        ])->layout('livewire.admin.layouts.app');
    }
}
