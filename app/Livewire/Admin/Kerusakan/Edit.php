<?php

namespace App\Livewire\Admin\Kerusakan;

use Livewire\Component;
use App\Models\Kerusakan;

class Edit extends Component
{
    public $kerusakanId;
    public $kode, $nama_kerusakan, $solusi;

    public function mount($id)
    {
        $kerusakan = Kerusakan::findOrFail($id);
        $this->kerusakanId = $kerusakan->id;
        $this->kode = $kerusakan->kode;
        $this->nama_kerusakan = $kerusakan->nama_kerusakan;
        $this->solusi = $kerusakan->solusi;
    }

    public function update()
    {
        $this->validate([
            'kode' => 'required|unique:kerusakans,kode,' . $this->kerusakanId,
            'nama_kerusakan' => 'required|string|max:255',
        ], [
            'kode.required' => 'Kode kerusakan wajib diisi',
            'kode.unique' => 'Kode kerusakan sudah terpakai',
            'nama_kerusakan.required' => 'Nama kerusakan wajib diisi'
        ]);

        $kerusakan = Kerusakan::findOrFail($this->kerusakanId);
        $kerusakan->update([
            'kode' => $this->kode,
            'nama_kerusakan' => $this->nama_kerusakan,
            'solusi' => $this->solusi,
        ]);

        session()->flash('message', 'Kerusakan berhasil diperbarui!');
        return redirect()->route('admin.kerusakan');
    }

    public function render()
    {
        return view('livewire.admin.kerusakan.edit')
            ->layout('livewire.admin.layouts.app');
    }
}
