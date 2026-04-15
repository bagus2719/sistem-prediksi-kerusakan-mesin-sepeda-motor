<?php

namespace App\Livewire\Admin\Riwayat;

use Livewire\Component;
use App\Models\Riwayat;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.riwayat.index', [
            'riwayats' => Riwayat::with(['user', 'kerusakan'])
                ->latest()
                ->get()
        ])->layout('livewire.admin.layouts.app');
    }

    public function delete($id)
    {
        Riwayat::findOrFail($id)->delete();
        session()->flash('message', 'Riwayat berhasil dihapus.');
    }
}
