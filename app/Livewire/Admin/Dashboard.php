<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $activeModel = \App\Models\C45Model::where('is_active', true)->latest()->first();
        $recentRiwayats = \App\Models\Riwayat::with(['user', 'kerusakan', 'motor'])->latest()->take(5)->get();

        return view('livewire.admin.dashboard', [
            'activeModel' => $activeModel,
            'recentRiwayats' => $recentRiwayats
        ])->layout('livewire.admin.layouts.app');
    }
}
