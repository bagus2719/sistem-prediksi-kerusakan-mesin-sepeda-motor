<?php

namespace App\Livewire\User;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $total_gejala = \App\Models\Gejala::count();
        $total_kerusakan = \App\Models\Kerusakan::count();

        return view('livewire.user.dashboard', compact('total_gejala', 'total_kerusakan'))
        ->layout('livewire.user.layouts.app');
    }
}
