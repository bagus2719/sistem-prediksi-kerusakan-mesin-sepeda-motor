<?php

namespace App\Services;

use App\Models\Gejala;
use App\Models\Kerusakan;
use App\Models\Motor;
use App\Models\Training;

class PreprocessingService
{
    /**
     * Run all preprocessing steps: cleaning missing values and removing duplicates.
     * Returns an array with the report of the preprocessing execution.
     *
     * @return array
     */
    public function run()
    {
        $countAwal = Training::count();
        if ($countAwal === 0) {
            return [
                'status' => 'error',
                'message' => 'Dataset masih kosong. Lakukan import terlebih dahulu.',
            ];
        }

        // 1. Data Cleaning: Membersihkan missing values/anomali
        $invalidRows = Training::whereNull('kerusakan_id')->delete();

        // 2. Data Reduction: Penghapusan Duplikat
        $trainings = Training::all();
        $uniqueHashes = [];
        $duplicateCount = 0;

        foreach ($trainings as $t) {
            $hash = md5($t->motor_id.$t->kerusakan_id.$t->data_gejala);
            if (isset($uniqueHashes[$hash])) {
                // Duplikat ditemukan, hapus.
                $t->delete();
                $duplicateCount++;
            } else {
                $uniqueHashes[$hash] = true;
            }
        }

        $countAkhir = Training::count();

        // Catatan: Data Integration & Transformation sudah terjadi saat proses Import CSV.
        // Data Splitting (Train/Test Split) dapat diimplementasikan di sini di masa mendatang jika diperlukan.
        return [
            'status' => 'success',
            'message' => "Preprocessing selesai! {$duplicateCount} data duplikat dihapus.",
            'report' => [
                'awal' => $countAwal,
                'invalid_dihapus' => $invalidRows,
                'duplikat_dihapus' => $duplicateCount,
                'akhir' => $countAkhir,
            ],
        ];
    }

    /**
     * Process CSV file for Data Integration and Transformation
     *
     * @param  string  $filepath  Path to the uploaded CSV file
     * @return array
     */
    public function importCsv($filepath)
    {
        $content = file_get_contents($filepath);
        $delimiter = strpos($content, ';') !== false ? ';' : ',';
        $file = fopen($filepath, 'r');

        $header = fgetcsv($file, 0, $delimiter);
        if (! $header) {
            return ['status' => 'error', 'message' => 'File CSV kosong atau format tidak valid.'];
        }

        $header = array_map('trim', $header);

        // Dynamic Column Detection
        $kodeKerusakanIndex = array_search('Kode_Kerusakan', $header);
        if ($kodeKerusakanIndex === false) {
            $kodeKerusakanIndex = array_search('KODE_KERUSAKAN', $header);
        }

        $namaKerusakanIndex = array_search('Nama_Kerusakan', $header);
        if ($namaKerusakanIndex === false) {
            $namaKerusakanIndex = array_search('Kerusakan', $header);
        }
        if ($namaKerusakanIndex === false) {
            $namaKerusakanIndex = array_search('KERUSAKAN', $header);
        }

        $solusiIndex = array_search('Solusi', $header);
        if ($solusiIndex === false) {
            $solusiIndex = array_search('SOLUSI', $header);
        }

        if ($kodeKerusakanIndex === false && $namaKerusakanIndex === false) {
            $namaKerusakanIndex = count($header) - 1; // Fallback
        }

        $brandIndex = array_search('Brand', $header);
        if ($brandIndex === false) {
            $brandIndex = array_search('BRAND', $header);
        }
        if ($brandIndex === false) {
            $brandIndex = array_search('Merk', $header);
        }
        if ($brandIndex === false) {
            $brandIndex = array_search('MERK', $header);
        }

        $modelIndex = array_search('Model', $header);
        if ($modelIndex === false) {
            $modelIndex = array_search('MODEL', $header);
        }

        $tipeIndex = array_search('Tipe', $header);
        if ($tipeIndex === false) {
            $tipeIndex = array_search('TIPE', $header);
        }
        if ($tipeIndex === false) {
            $tipeIndex = array_search('Tipe_Sistem', $header);
        }
        if ($tipeIndex === false) {
            $tipeIndex = array_search('TIPE_SISTEM', $header);
        }

        $allGejalas = Gejala::all()->pluck('kode')->toArray();
        $allKerusakans = Kerusakan::all();

        $countInsert = 0;
        $countSkipped = 0;

        while (($data = fgetcsv($file, 0, $delimiter)) !== false) {
            if (count($data) < 2) {
                continue;
            }

            $data = array_map('trim', $data);
            $gejala_input = [];

            // Data Transformation: Yes/No -> 1/0
            foreach ($allGejalas as $kode_gejala) {
                $indexGejala = array_search($kode_gejala, $header);
                $val = ($indexGejala !== false && isset($data[$indexGejala])) ? strtolower($data[$indexGejala]) : '0';
                $gejala_input[$kode_gejala] = ($val === 'ya' || $val === 'y' || $val === '1' || $val === 1) ? 1 : 0;
            }

            // Data Integration: Motor
            $motorId = null;
            if ($brandIndex !== false && $modelIndex !== false && isset($data[$brandIndex]) && isset($data[$modelIndex])) {
                $brand = strtoupper($data[$brandIndex]);
                $nama_motor = $data[$modelIndex];
                $tipe = ($tipeIndex !== false && isset($data[$tipeIndex])) ? $data[$tipeIndex] : 'Injeksi';

                if (! empty($nama_motor)) {
                    $motorObj = Motor::firstOrCreate(
                        ['nama_motor' => $nama_motor],
                        ['merk' => $brand, 'sistem_pembakaran' => $tipe]
                    );
                    $motorId = $motorObj->id;
                }
            }

            // Data Integration: Kerusakan
            $kodeKerusakanCSV = $kodeKerusakanIndex !== false ? ($data[$kodeKerusakanIndex] ?? null) : null;
            $namaKerusakanCSV = $namaKerusakanIndex !== false ? ($data[$namaKerusakanIndex] ?? null) : null;
            $solusiCSV = $solusiIndex !== false ? ($data[$solusiIndex] ?? null) : null;
            $kerusakanId = null;

            if ($kodeKerusakanCSV) {
                $kerusakanMatch = $allKerusakans->where('kode', strtoupper(trim($kodeKerusakanCSV)))->first();
                if ($kerusakanMatch) {
                    $kerusakanId = $kerusakanMatch->id;
                    if ($solusiCSV && trim($solusiCSV) !== '') {
                        $kerusakanMatch->update(['solusi' => trim($solusiCSV)]);
                    }
                }
            }

            if (! $kerusakanId && $namaKerusakanCSV) {
                $kerusakanMatchName = $allKerusakans->filter(function ($item) use ($namaKerusakanCSV) {
                    return strtolower(trim($item->nama_kerusakan)) == strtolower(trim($namaKerusakanCSV));
                })->first();

                if ($kerusakanMatchName) {
                    $kerusakanId = $kerusakanMatchName->id;
                    if ($solusiCSV && trim($solusiCSV) !== '') {
                        $kerusakanMatchName->update(['solusi' => trim($solusiCSV)]);
                    }
                } else {
                    $maxId = Kerusakan::max('id') ?? 0;
                    $newKode = $kodeKerusakanCSV ?: 'K'.str_pad($maxId + 1, 2, '0', STR_PAD_LEFT);
                    $newKerusakan = Kerusakan::create([
                        'kode' => $newKode,
                        'nama_kerusakan' => trim($namaKerusakanCSV),
                        'solusi' => ($solusiCSV && trim($solusiCSV) !== '') ? trim($solusiCSV) : 'Belum ada solusi tercatat.',
                    ]);
                    $kerusakanId = $newKerusakan->id;
                    $allKerusakans->push($newKerusakan);
                }
            }

            if ($kerusakanId) {
                Training::create([
                    'kerusakan_id' => $kerusakanId,
                    'motor_id' => $motorId,
                    'data_gejala' => json_encode($gejala_input),
                ]);
                $countInsert++;
            } else {
                $countSkipped++;
            }
        }

        fclose($file);

        if ($countInsert > 0) {
            return ['status' => 'success', 'message' => "Import Selesai! {$countInsert} baris data berhasil masuk. ({$countSkipped} baris diabaikan)."];
        } else {
            return ['status' => 'error', 'message' => "Gagal! Format CSV tidak dikenali atau kolom target hilang. (Terdeteksi Delimiter: {$delimiter})"];
        }
    }
}
