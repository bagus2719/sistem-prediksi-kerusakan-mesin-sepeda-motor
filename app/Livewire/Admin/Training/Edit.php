<?php

namespace App\Livewire\Admin\Training;

use Livewire\Component;
use App\Models\Training;
use App\Models\Gejala;
use App\Models\Kerusakan;

class Edit extends Component
{
    public $trainingId;
    public $kerusakan_id;
    public $gejala_input = [];
    public $allGejalas = [];
    public $allKerusakans = [];

    public function mount(Training $training)
    {
        $this->allGejalas = Gejala::orderBy('kode')->get();
        $this->allKerusakans = Kerusakan::orderBy('kode')->get();

        $this->trainingId = $training->id;
        $this->kerusakan_id = $training->kerusakan_id;
        
        $savedGejala = is_string($training->data_gejala) ? json_decode($training->data_gejala, true) : $training->data_gejala;
        $savedGejala = is_array($savedGejala) ? $savedGejala : [];
        
        foreach($this->allGejalas as $g) {
            $val = isset($savedGejala[$g->kode]) ? $savedGejala[$g->kode] : 0;
            $this->gejala_input[$g->kode] = ($val == 1) ? true : false;
        }
    }

    public function update()
    {
        $this->validate([
            'kerusakan_id' => 'required|exists:kerusakans,id',
        ]);

        $training = Training::findOrFail($this->trainingId);
        $training->update([
            'kerusakan_id' => $this->kerusakan_id,
            'data_gejala' => json_encode($this->gejala_input)
        ]);

        session()->flash('message', 'Data training berhasil diperbarui!');
        return redirect()->route('admin.training');
    }

    public function render()
    {
        return view('livewire.admin.training.edit')
            ->layout('livewire.admin.layouts.app');
    }
}
