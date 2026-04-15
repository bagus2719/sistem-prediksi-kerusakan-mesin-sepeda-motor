<?php

namespace App\Livewire\Admin\Gejala;

use Livewire\Component;
use App\Models\Gejala;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.gejala.index', [
            'gejalas' => Gejala::oldest('kode')->get()
        ])->layout('livewire.admin.layouts.app');
    }

    public function delete($id)
    {
        Gejala::findOrFail($id)->delete();
        session()->flash('message', 'Data gejala berhasil dihapus.');
    }
}