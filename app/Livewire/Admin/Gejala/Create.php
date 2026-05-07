<?php

namespace App\Livewire\Admin\Gejala;

use Livewire\Component;
use App\Models\Gejala;

class Create extends Component
{
    public $kode, $nama_gejala;
    public $sistem_pembakaran = 'Keduanya';

    public function store()
    {
        $this->validate([
            'kode' => 'required|unique:gejalas,kode',
            'nama_gejala' => 'required|string|max:255',
            'sistem_pembakaran' => 'required|in:Keduanya,Injeksi,Karburator',
        ], [
            'kode.required' => 'Kode gejala wajib diisi',
            'kode.unique' => 'Kode gejala sudah terpakai',
            'nama_gejala.required' => 'Nama gejala wajib diisi'
        ]);

        Gejala::create([
            'kode' => $this->kode,
            'nama_gejala' => $this->nama_gejala,
            'sistem_pembakaran' => $this->sistem_pembakaran,
        ]);

        session()->flash('message', 'Gejala baru berhasil ditambahkan!');
        return redirect()->route('admin.gejala');
    }

    public function render()
    {
        return view('livewire.admin.gejala.create')
            ->layout('livewire.admin.layouts.app');
    }
}
