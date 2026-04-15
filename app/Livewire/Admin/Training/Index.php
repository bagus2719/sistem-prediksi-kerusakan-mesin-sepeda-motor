<?php

namespace App\Livewire\Admin\Training;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Training;
use App\Models\Gejala;
use App\Models\Kerusakan;

class Index extends Component
{
    use WithFileUploads;

    public $file_import;

    public function importCSV()
    {
        $this->validate([
            'file_import' => 'required|file|mimes:csv,txt|max:10240', // Maksimal 10MB
        ]);

        $filepath = $this->file_import->getRealPath();
        $file = fopen($filepath, "r");

        // Membaca header (baris pertama)
        $header = fgetcsv($file);

        // Pastikan kolom Kerusakan ada di akhir (opsional, tergantung logic)
        $kerusakanIndex = array_search('KERUSAKAN', $header);
        if ($kerusakanIndex === false) {
            $kerusakanIndex = count($header) - 1; // Default pakai kolom paling ujung jika header beda
        }

        // Ambil data referensi agar query tak berulang
        $allGejalas = Gejala::all()->pluck('kode')->toArray();
        $allKerusakans = Kerusakan::all();

        $countInsert = 0;

        while (($data = fgetcsv($file)) !== false) {
            $gejala_input = [];

            // Memetakan nilai setiap Gejala (G01 - G14)
            foreach ($allGejalas as $kode_gejala) {
                $indexGejala = array_search($kode_gejala, $header);
                $gejala_input[$kode_gejala] = ($indexGejala !== false && isset($data[$indexGejala])) ? (int)$data[$indexGejala] : 0;
            }

            // Mendapatkan Kerusakan_id
            $kodeKerusakanCSV = $data[$kerusakanIndex] ?? null;
            $kerusakanId = null;

            if ($kodeKerusakanCSV) {
                // Cari apakah kode_kerusakan K01 cocok
                $kerusakanMatch = $allKerusakans->where('kode', $kodeKerusakanCSV)->first();
                if ($kerusakanMatch) {
                    $kerusakanId = $kerusakanMatch->id;
                } else {
                    // Coba fallback dengan mencari kesamaan nama kerusakan jika kodenya tidak ada
                    $kerusakanMatchName = $allKerusakans->filter(function($item) use ($kodeKerusakanCSV) {
                        return strtolower($item->nama_kerusakan) == strtolower($kodeKerusakanCSV);
                    })->first();
                    $kerusakanId = $kerusakanMatchName ? $kerusakanMatchName->id : null;
                }
            }

            // Simpan Baris
            if ($kerusakanId) {
                Training::create([
                    'kerusakan_id' => $kerusakanId,
                    'data_gejala' => json_encode($gejala_input)
                ]);
                $countInsert++;
            }
        }

        fclose($file);
        $this->reset('file_import');

        session()->flash('message', "Import Selesai! Sebanyak {$countInsert} baris data training berhasil dimasukkan.");
    }

    public function render()
    {
        $trainings = Training::with('kerusakan')->latest()->get();
        // Decode JSON back to associative array formatting for view logic handling
        foreach($trainings as $t) {
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
