<?php

namespace App\Livewire\Admin\Training;

use Livewire\Component;
use App\Models\Training;
use App\Models\Gejala;
use App\Models\Kerusakan;

class Create extends Component
{
    public $kerusakan_id;
    public $gejala_input = [];
    public $allGejalas = [];
    public $allKerusakans = [];

    public function mount()
    {
        $this->allGejalas = Gejala::orderBy('kode')->get();
        $this->allKerusakans = Kerusakan::orderBy('kode')->get();

        foreach($this->allGejalas as $g) {
            $this->gejala_input[$g->kode] = 0;
        }
    }

    public function store()
    {
        $this->validate([
            'kerusakan_id' => 'required|exists:kerusakans,id',
        ], [
            'kerusakan_id.required' => 'Kategori kerusakan harus dipilih'
        ]);

        Training::create([
            'kerusakan_id' => $this->kerusakan_id,
            'data_gejala' => json_encode($this->gejala_input)
        ]);

        session()->flash('message', 'Data training baru berhasil ditambahkan!');
        return redirect()->route('admin.training');
    }

    public function render()
    {
        return view('livewire.admin.training.create')
            ->layout('livewire.admin.layouts.app');
    }
}
