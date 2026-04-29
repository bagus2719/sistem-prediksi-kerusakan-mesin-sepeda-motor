<?php

namespace App\Livewire\Admin\Gejala;

use Livewire\Component;
use App\Models\Gejala;

class Edit extends Component
{
    public $gejalaId;
    public $kode, $nama_gejala, $keterangan;
    public $sistem_pembakaran;

    public function mount($id)
    {
        $gejala = Gejala::findOrFail($id);
        $this->gejalaId = $gejala->id;
        $this->kode = $gejala->kode;
        $this->nama_gejala = $gejala->nama_gejala;
        $this->sistem_pembakaran = $gejala->sistem_pembakaran;
        $this->keterangan = $gejala->keterangan;
    }

    public function update()
    {
        $this->validate([
            'kode' => 'required|unique:gejalas,kode,' . $this->gejalaId,
            'nama_gejala' => 'required|string|max:255',
            'sistem_pembakaran' => 'required|in:Keduanya,Injeksi,Karburator',
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
            'keterangan' => $this->keterangan,
        ]);

        session()->flash('message', 'Gejala berhasil diperbarui!');
        return redirect()->route('admin.gejala');
    }

    public function render()
    {
        return view('livewire.admin.gejala.edit')
            ->layout('livewire.admin.layouts.app');
    }
}
