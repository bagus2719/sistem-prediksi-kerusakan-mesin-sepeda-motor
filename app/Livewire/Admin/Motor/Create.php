<?php

namespace App\Livewire\Admin\Motor;

use Livewire\Component;
use App\Models\Motor;

class Create extends Component
{
    public $merk;
    public $nama_motor;
    public $sistem_pembakaran;

    public function rules()
    {
        return [
            'merk' => 'required|string',
            'nama_motor' => 'required|string|max:100',
            'sistem_pembakaran' => 'required|string|in:Injeksi,Karburator'
        ];
    }

    public function save()
    {
        $this->validate();

        Motor::create([
            'merk' => $this->merk,
            'nama_motor' => $this->nama_motor,
            'sistem_pembakaran' => $this->sistem_pembakaran,
        ]);

        session()->flash('message', 'Motor berhasil ditambahkan.');
        return $this->redirectRoute('admin.motor.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.motor.create')->layout('livewire.admin.layouts.app');
    }
}
