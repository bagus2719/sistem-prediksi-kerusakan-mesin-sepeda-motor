<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Riwayat as ModelRiwayat;
use Illuminate\Support\Facades\Auth;

class Riwayat extends Component
{
    public function render()
    {
        $riwayats = [];
        $gejalaMap = [];
        if (Auth::check()) {
            $riwayats = ModelRiwayat::where('user_id', Auth::id())
                ->with(['kerusakan', 'motor'])
                ->latest()
                ->get();
                
            $gejalaMap = \App\Models\Gejala::pluck('nama_gejala', 'kode')->toArray();
        }

        return view('livewire.user.riwayat', compact('riwayats', 'gejalaMap'))
            ->layout('livewire.user.layouts.app');
    }
}
