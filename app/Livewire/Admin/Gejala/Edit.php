<?php

namespace App\Livewire\Admin\Gejala;

use Livewire\Component;
use App\Models\Gejala;

class Edit extends Component
{
    public $gejalaId;
    public $kode, $nama_gejala;
    public $sistem_pembakaran;
    public $is_root;
    public $branch = [];

    public function mount($id)
    {
        $gejala = Gejala::findOrFail($id);
        $this->gejalaId = $gejala->id;
        $this->kode = $gejala->kode;
        $this->nama_gejala = $gejala->nama_gejala;
        $this->sistem_pembakaran = $gejala->sistem_pembakaran;
        $this->is_root = $gejala->is_root;
        $this->branch = $gejala->branch ?? [];
    }

    public function update()
    {
        $this->validate([
            'kode' => 'required|unique:gejalas,kode,' . $this->gejalaId,
            'nama_gejala' => 'required|string|max:255',
            'sistem_pembakaran' => 'required|in:Keduanya,Injeksi,Karburator',
            'is_root' => 'boolean',
            'branch' => 'nullable|array',
        ], [
            'kode.required' => 'Kode gejala wajib diisi',
            'kode.unique' => 'Kode gejala sudah terpakai',
            'nama_gejala.required' => 'Nama gejala wajib diisi'
        ]);

        $gejala = Gejala::findOrFail($this->gejalaId);
        $gejala->update([
            'kode' => $this->kode,
            'nama_gejala' => $this->nama_gejala,
            'sistem_pembakaran' => $this->sistem_pembakaran,
            'is_root' => $this->is_root,
            'branch' => $this->is_root ? null : $this->branch,
        ]);

        session()->flash('message', 'Gejala berhasil diperbarui!');
        return redirect()->route('admin.gejala');
    }

    public function render()
    {
        return view('livewire.admin.gejala.edit', [
            'roots' => Gejala::where('is_root', true)->orderBy('kode')->get()
        ])
            ->layout('livewire.admin.layouts.app');
    }
}
