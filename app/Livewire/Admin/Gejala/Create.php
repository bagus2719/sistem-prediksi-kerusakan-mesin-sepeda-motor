<?php

namespace App\Livewire\Admin\Gejala;

use Livewire\Component;
use App\Models\Gejala;

class Create extends Component
{
    public $kode, $nama_gejala;
    public $sistem_pembakaran = 'Keduanya';
    public $is_root = false;
    public $branch = [];

    public function store()
    {
        $this->validate([
            'kode' => 'required|unique:gejalas,kode',
            'nama_gejala' => 'required|string|max:255',
            'sistem_pembakaran' => 'required|in:Keduanya,Injeksi,Karburator',
            'is_root' => 'boolean',
            'branch' => 'nullable|array',
        ], [
            'kode.required' => 'Kode gejala wajib diisi',
            'kode.unique' => 'Kode gejala sudah terpakai',
            'nama_gejala.required' => 'Nama gejala wajib diisi'
        ]);

        Gejala::create([
            'kode' => $this->kode,
            'nama_gejala' => $this->nama_gejala,
            'sistem_pembakaran' => $this->sistem_pembakaran,
            'is_root' => $this->is_root,
            'branch' => $this->is_root ? null : $this->branch,
        ]);

        session()->flash('message', 'Gejala baru berhasil ditambahkan!');
        return redirect()->route('admin.gejala');
    }

    public function render()
    {
        return view('livewire.admin.gejala.create', [
            'roots' => Gejala::where('is_root', true)->orderBy('kode')->get()
        ])
            ->layout('livewire.admin.layouts.app');
    }
}
