<?php

namespace App\Livewire\Admin\Kerusakan;

use Livewire\Component;
use App\Models\Kerusakan;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.kerusakan.index', [
            'kerusakans' => Kerusakan::oldest('kode')->get()
        ])->layout('livewire.admin.layouts.app');
    }

    public function delete($id)
    {
        Kerusakan::findOrFail($id)->delete();
        session()->flash('message', 'Data kerusakan berhasil dihapus.');
    }
}
