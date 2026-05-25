<?php

namespace App\Livewire\User;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $total_gejala = \App\Models\Gejala::count();
        $total_kerusakan = \App\Models\Kerusakan::count();
        
        $riwayatTerakhir = [];
        if (auth()->check()) {
            $riwayatTerakhir = \App\Models\Riwayat::with('kerusakan')
                ->where('user_id', auth()->id())
                ->latest()
                ->take(3)
                ->get();
        }

        return view('livewire.user.dashboard', compact('total_gejala', 'total_kerusakan', 'riwayatTerakhir'))
        ->layout('livewire.user.layouts.app');
    }
}
