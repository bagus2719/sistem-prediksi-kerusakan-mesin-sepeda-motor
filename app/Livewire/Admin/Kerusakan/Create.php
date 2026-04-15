<?php

namespace App\Livewire\Admin\Kerusakan;

use Livewire\Component;
use App\Models\Kerusakan;

class Create extends Component
{
    public $kode, $nama_kerusakan, $solusi;

    public function store()
    {
        $this->validate([
            'kode' => 'required|unique:kerusakans,kode',
            'nama_kerusakan' => 'required|string|max:255',
        ], [
            'kode.required' => 'Kode kerusakan wajib diisi',
            'kode.unique' => 'Kode kerusakan sudah terpakai',
            'nama_kerusakan.required' => 'Nama kerusakan wajib diisi'
        ]);

        Kerusakan::create([
            'kode' => $this->kode,
            'nama_kerusakan' => $this->nama_kerusakan,
            'solusi' => $this->solusi,
        ]);

        session()->flash('message', 'Kerusakan baru berhasil ditambahkan!');
        return redirect()->route('admin.kerusakan');
    }

    public function render()
    {
        return view('livewire.admin.kerusakan.create')
            ->layout('livewire.admin.layouts.app');
    }
}
