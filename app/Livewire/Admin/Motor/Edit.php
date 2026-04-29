<?php

namespace App\Livewire\Admin\Motor;

use Livewire\Component;
use App\Models\Motor;

class Edit extends Component
{
    public $motor_id;
    public $merk;
    public $nama_motor;
    public $sistem_pembakaran;

    public function mount($id)
    {
        $motor = Motor::findOrFail($id);
        $this->motor_id = $motor->id;
        $this->merk = $motor->merk;
        $this->nama_motor = $motor->nama_motor;
        $this->sistem_pembakaran = $motor->sistem_pembakaran;
    }

    public function rules()
    {
        return [
            'merk' => 'required|string',
            'nama_motor' => 'required|string|max:100',
            'sistem_pembakaran' => 'required|string|in:Injeksi,Karburator'
        ];
    }

    public function update()
    {
        $this->validate();

        $motor = Motor::findOrFail($this->motor_id);
        $motor->update([
            'merk' => $this->merk,
            'nama_motor' => $this->nama_motor,
            'sistem_pembakaran' => $this->sistem_pembakaran,
        ]);

        session()->flash('message', 'Motor berhasil diupdate.');
        return $this->redirectRoute('admin.motor.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.motor.edit')->layout('livewire.admin.layouts.app');
    }
}
