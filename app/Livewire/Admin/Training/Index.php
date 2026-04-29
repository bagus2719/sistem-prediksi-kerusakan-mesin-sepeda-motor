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
    public $preprocessingReport = [];

    public function importCSV()
    {
        $this->validate([
            'file_import' => 'required|file|mimes:csv,txt|max:10240', // Maksimal 10MB
        ]);

        try {
            $filepath = $this->file_import->getRealPath();
            
            // Deteksi Delimiter (Koma atau Titik Koma)
            $content = file_get_contents($filepath);
            $delimiter = strpos($content, ';') !== false ? ';' : ',';

            $file = fopen($filepath, "r");

            // Membaca header (baris pertama)
            $header = fgetcsv($file, 0, $delimiter);
            $header = array_map('trim', $header);

            // Cari index kolom dinamis
            $kodeKerusakanIndex = array_search('Kode_Kerusakan', $header);
            if ($kodeKerusakanIndex === false) $kodeKerusakanIndex = array_search('KODE_KERUSAKAN', $header);

            $namaKerusakanIndex = array_search('Nama_Kerusakan', $header);
            if ($namaKerusakanIndex === false) $namaKerusakanIndex = array_search('Kerusakan', $header);
            if ($namaKerusakanIndex === false) $namaKerusakanIndex = array_search('KERUSAKAN', $header);
            
            $solusiIndex = array_search('Solusi', $header);
            if ($solusiIndex === false) $solusiIndex = array_search('SOLUSI', $header);
            
            // Fallback: Jika tidak ditemukan sama sekali, asumsikan kolom terakhir
            if ($kodeKerusakanIndex === false && $namaKerusakanIndex === false) {
                $namaKerusakanIndex = count($header) - 1;
            }
            
            $brandIndex = array_search('Brand', $header);
            if ($brandIndex === false) $brandIndex = array_search('BRAND', $header);

            $modelIndex = array_search('Model', $header);
            if ($modelIndex === false) $modelIndex = array_search('MODEL', $header);

            $tipeIndex = array_search('Tipe', $header);
            if ($tipeIndex === false) $tipeIndex = array_search('TIPE', $header);

            // Ambil data referensi agar query tak berulang
            $allGejalas = Gejala::all()->pluck('kode')->toArray();
            $allKerusakans = Kerusakan::all();

            $countInsert = 0;
            $countSkipped = 0;

            while (($data = fgetcsv($file, 0, $delimiter)) !== false) {
                if(count($data) < 2) continue; // Skip baris kosong

                $data = array_map('trim', $data);
                $gejala_input = [];

                // Memetakan nilai setiap Gejala (G01 - G14) "Ya" -> 1, "Tidak" -> 0
                foreach ($allGejalas as $kode_gejala) {
                    $indexGejala = array_search($kode_gejala, $header);
                    $val = ($indexGejala !== false && isset($data[$indexGejala])) ? strtolower($data[$indexGejala]) : '0';
                    $gejala_input[$kode_gejala] = ($val === 'ya' || $val === 'y' || $val === '1' || $val === 1) ? 1 : 0;
                }

                // AUTO-CREATE MOTOR JIKA BELUM ADA
                $motorId = null;
                if ($brandIndex !== false && $modelIndex !== false && isset($data[$brandIndex]) && isset($data[$modelIndex])) {
                    $brand = strtoupper($data[$brandIndex]);
                    $nama_motor = $data[$modelIndex];
                    $tipe = ($tipeIndex !== false && isset($data[$tipeIndex])) ? $data[$tipeIndex] : 'Injeksi';

                    if(!empty($nama_motor)) {
                        $motorObj = \App\Models\Motor::firstOrCreate(
                            ['nama_motor' => $nama_motor],
                            ['merk' => $brand, 'sistem_pembakaran' => $tipe]
                        );
                        $motorId = $motorObj->id;
                    }
                }

                // Mendapatkan Kerusakan_id
                $kodeKerusakanCSV = $kodeKerusakanIndex !== false ? ($data[$kodeKerusakanIndex] ?? null) : null;
                $namaKerusakanCSV = $namaKerusakanIndex !== false ? ($data[$namaKerusakanIndex] ?? null) : null;
                $solusiCSV = $solusiIndex !== false ? ($data[$solusiIndex] ?? null) : null;
                $kerusakanId = null;

                // 1. Coba match berdasarkan Kode_Kerusakan
                if ($kodeKerusakanCSV) {
                    $kerusakanMatch = $allKerusakans->where('kode', strtoupper(trim($kodeKerusakanCSV)))->first();
                    if ($kerusakanMatch) {
                        $kerusakanId = $kerusakanMatch->id;
                        // Update solusi dari CSV jika tersedia
                        if ($solusiCSV && trim($solusiCSV) !== '') {
                            $kerusakanMatch->update(['solusi' => trim($solusiCSV)]);
                        }
                    }
                }

                // 2. Jika tidak ketemu dari Kode, coba cari dari Nama Kerusakan
                if (!$kerusakanId && $namaKerusakanCSV) {
                    $kerusakanMatchName = $allKerusakans->filter(function($item) use ($namaKerusakanCSV) {
                        return strtolower(trim($item->nama_kerusakan)) == strtolower(trim($namaKerusakanCSV));
                    })->first();
                    
                    if ($kerusakanMatchName) {
                        $kerusakanId = $kerusakanMatchName->id;
                        // Update solusi dari CSV jika tersedia
                        if ($solusiCSV && trim($solusiCSV) !== '') {
                            $kerusakanMatchName->update(['solusi' => trim($solusiCSV)]);
                        }
                    } else {
                        // AUTO CREATE KERUSAKAN BARU JIKA KEDUANYA TIDAK ADA
                        $maxId = \App\Models\Kerusakan::max('id') ?? 0;
                        $newKode = $kodeKerusakanCSV ?: 'K'.str_pad($maxId + 1, 2, '0', STR_PAD_LEFT);
                        $newKerusakan = \App\Models\Kerusakan::create([
                            'kode' => $newKode,
                            'nama_kerusakan' => trim($namaKerusakanCSV),
                            'solusi' => ($solusiCSV && trim($solusiCSV) !== '') ? trim($solusiCSV) : 'Belum ada solusi tercatat.'
                        ]);
                        $kerusakanId = $newKerusakan->id;
                        $allKerusakans->push($newKerusakan); // update cache
                    }
                }

                // Simpan Baris Training
                if ($kerusakanId) {
                    Training::create([
                        'kerusakan_id' => $kerusakanId,
                        'motor_id' => $motorId,
                        'data_gejala' => json_encode($gejala_input)
                    ]);
                    $countInsert++;
                } else {
                    $countSkipped++;
                }
            }

            fclose($file);
            $this->reset('file_import');

            if($countInsert > 0) {
                session()->flash('message', "Import Selesai! {$countInsert} baris data berhasil masuk. ({$countSkipped} baris diabaikan).");
            } else {
                session()->flash('error', "Gagal! Format CSV tidak dikenali atau kolom 'Kerusakan' hilang. (Terdeteksi Delimiter: {$delimiter})");
            }

        } catch (\Exception $e) {
            session()->flash('error', "Terjadi kesalahan sistem: " . $e->getMessage());
        }
    }

    public function runPreprocessing()
    {
        $countAwal = Training::count();
        if ($countAwal === 0) {
            session()->flash('error', 'Dataset masih kosong. Lakukan import terlebih dahulu.');
            return;
        }

        // 1. Membersihkan missing values/anomali
        // (Misalnya data dengan kerusakan_id null atau tidak valid)
        $invalidRows = Training::whereNull('kerusakan_id')->delete();

        // 2. Penghapusan Duplikat 
        // Mengelompokkan berdasarkan motor_id, kerusakan_id, dan data_gejala
        $trainings = Training::all();
        $uniqueHashes = [];
        $duplicateCount = 0;

        foreach ($trainings as $t) {
            $hash = md5($t->motor_id . $t->kerusakan_id . $t->data_gejala);
            if (isset($uniqueHashes[$hash])) {
                // Duplikat ditemukan, hapus.
                $t->delete();
                $duplicateCount++;
            } else {
                $uniqueHashes[$hash] = true;
            }
        }

        $countAkhir = Training::count();

        $this->preprocessingReport = [
            'awal' => $countAwal,
            'invalid_dihapus' => $invalidRows,
            'duplikat_dihapus' => $duplicateCount,
            'akhir' => $countAkhir
        ];

        session()->flash('message', "Preprocessing selesai! {$duplicateCount} data duplikat dihapus.");
    }

    public function render()
    {
        $trainings = Training::with(['kerusakan', 'motor'])->latest()->get();
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
