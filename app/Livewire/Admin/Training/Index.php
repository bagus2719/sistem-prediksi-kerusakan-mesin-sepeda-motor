<?php

namespace App\Livewire\Admin\Training;

use App\Models\Gejala;
use App\Models\Training;
use App\Services\PreprocessingService;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public $file_import;

    public $preprocessingReport = [];

    public function importCSV()
    {
        $this->validate([
            'file_import' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        try {
            $filepath = $this->file_import->getRealPath();

            $service = new PreprocessingService;
            $result = $service->importCsv($filepath);

            $this->reset('file_import');

            if ($result['status'] === 'success') {
                session()->flash('message', $result['message']);
            } else {
                session()->flash('error', $result['message']);
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan sistem: '.$e->getMessage());
        }
    }

    public function runPreprocessing()
    {
        $service = new PreprocessingService;
        $result = $service->run();

        if ($result['status'] === 'error') {
            session()->flash('error', $result['message']);

            return;
        }

        $this->preprocessingReport = $result['report'];
        session()->flash('message', $result['message']);
    }

    public function render()
    {
        $trainings = Training::with(['kerusakan', 'motor'])->latest()->get();
        // Decode JSON back to associative array formatting for view logic handling
        foreach ($trainings as $t) {
            $t->data_gejala = json_decode($t->data_gejala, true) ?? [];
        }

        $allGejalas = Gejala::orderBy('kode')->get();

        return view('livewire.admin.training.index', compact('trainings', 'allGejalas'))
            ->layout('livewire.admin.layouts.app');
    }

    public function delete($id)
    {
        Training::findOrFail($id)->delete();
        session()->flash('message', 'Data training berhasil dihapus.');
    }
}
