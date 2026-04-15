<?php

namespace App\Livewire\User;

use Livewire\Component;

class Riwayat extends Component
{
    public function render()
    {
        return view('livewire.user.riwayat')
            ->layout('livewire.user.layouts.app');
    }
}
