<?php

namespace App\Livewire\Admin\Motor;

use Livewire\Component;
use App\Models\Motor;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.motor.index', [
            'motors' => Motor::orderBy('merk', 'asc')->orderBy('nama_motor', 'asc')->get()
        ])->layout('livewire.admin.layouts.app');
    }

    public function delete($id)
    {
        Motor::findOrFail($id)->delete();
        session()->flash('message', 'Data motor berhasil dihapus.');
    }
}
