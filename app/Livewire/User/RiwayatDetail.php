<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Riwayat as ModelRiwayat;
use App\Models\Gejala;
use Illuminate\Support\Facades\Auth;

class RiwayatDetail extends Component
{
    public $riwayat;
    public $gejalaMap = [];

    public function mount($id)
    {
        $this->riwayat = ModelRiwayat::with(['kerusakan', 'motor'])->where('id', $id)->firstOrFail();
        
        // Ensure user can only see their own riwayat
        if ($this->riwayat->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $this->gejalaMap = Gejala::pluck('nama_gejala', 'kode')->toArray();
    }

    public function render()
    {
        return view('livewire.user.riwayat-detail')
            ->layout('livewire.user.layouts.app');
    }
}
