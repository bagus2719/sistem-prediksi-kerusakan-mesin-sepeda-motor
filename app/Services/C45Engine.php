<?php

namespace App\Services;

use App\Models\Training;
use App\Models\C45Model;
use Illuminate\Support\Facades\Log;

class C45Engine
{
    /**
     * Membangun Model Pohon Keputusan C4.5 berdasarkan data Training yang tersedia.
     * Mengembalikan objek C45Model yang dibuat atau null jika gagal.
     */
    public function generateModel()
    {
        // 1. Ambil semua dataset training beserta hasil Kerusakan dan sistem_pembakaran Motor-nya
        $trainings = Training::with('motor')->get();

        if ($trainings->isEmpty()) {
            throw new \Exception('Data training kosong. Silakan import dataset terlebih dahulu.');
        }

        // 2. Format data menjadi bentuk array yang sesuai untuk mesin C4.5
        $dataset = [];
        $attributes = [];

        foreach ($trainings as $training) {
            $row = [];
            
            // Atribut: Sistem Pembakaran (Injeksi / Karburator)
            // jika Anda ingin menyertakannya. Sesuai diskusi sebelumnya, kita dapat menyertakannya.
            if ($training->motor) {
                $row['sistem_pembakaran'] = $training->motor->sistem_pembakaran;
            } else {
                $row['sistem_pembakaran'] = 'Umum';
            }

            // Atribut: Gejala (G01 - G14)
            $gejalas = is_string($training->data_gejala) ? json_decode($training->data_gejala, true) : $training->data_gejala;
            if (is_array($gejalas)) {
                foreach ($gejalas as $kode => $nilai) {
                    $row[$kode] = $nilai; // misal. "G01" => 1
                }
            }

            // Target/Kelas: Kerusakan (karena C4.5 akan mengklasifikasikan ini)
            $row['class'] = $training->kerusakan_id;

            $dataset[] = $row;
        }

        if (empty($dataset)) {
            throw new \Exception('Data training tidak memiliki format matriks fitur yang valid.');
        }

        // 3. Kumpulkan atribut unik untuk dievaluasi (semua kunci kecuali 'class')
        $attributes = array_keys($dataset[0]);
        $attributes = array_values(array_filter($attributes, fn($key) => $key !== 'class'));

        // 4. Mulai membangun pohon secara rekursif
        $treeData = $this->buildTree($dataset, $attributes);

        // 5. Simpan model ke dalam Database
        // Nonaktifkan semua model lama
        C45Model::where('is_active', true)->update(['is_active' => false]);

        // Simpan model baru
        $model = C45Model::create([
            'tree_data' => $treeData,
            'accuracy' => 100, // Opsional: menghitung akurasi menggunakan split uji (test split) dapat dilakukan nanti
            'is_active' => true
        ]);

        return $model;
    }

    /**
     * Fungsi rekursif untuk membangun Pohon Keputusan.
     * 
     * @param array $dataset Subset data saat ini
     * @param array $attributes Atribut yang tersedia untuk pemisahan
     * @return array Struktur Node
     */
    private function buildTree($dataset, $attributes)
    {
        // Periksa apakah dataset kosong
        if (empty($dataset)) {
            return ['type' => 'leaf', 'class' => null]; // Tidak dapat menentukan
        }

        // Kasus dasar 1: Jika semua instansi dalam dataset memiliki kelas yang SAMA, kembalikan node daun (leaf node)
        $classes = array_column($dataset, 'class');
        $uniqueClasses = array_unique($classes);
        if (count($uniqueClasses) === 1) {
            $c = array_values($uniqueClasses)[0];
            return ['type' => 'leaf', 'class' => $c, 'probabilities' => [$c => 100.0]];
        }

        // Kasus dasar 2: Jika tidak ada atribut tersisa untuk dipisah, kembalikan node daun dengan kelas MAYORITAS
        if (empty($attributes)) {
            $majorityClass = $this->getMajorityClass($classes);
            return ['type' => 'leaf', 'class' => $majorityClass, 'probabilities' => $this->calculateDistribution($classes)];
        }

        // Hitung Entropy dari dataset saat ini (S)
        $entropyS = $this->calculateEntropy($dataset);

        // Hitung Gain Ratio untuk setiap atribut
        $bestGainRatio = -1;
        $bestAttribute = null;

        foreach ($attributes as $attribute) {
            $gainRatio = $this->calculateGainRatio($dataset, $attribute, $entropyS);
            if ($gainRatio > $bestGainRatio) {
                $bestGainRatio = $gainRatio;
                $bestAttribute = $attribute;
            }
        }

        // Kasus dasar 3: Jika gain ratio terbaik adalah 0 atau negatif, kita tidak bisa melakukan pemisahan yang berguna lagi, kembalikan kelas mayoritas
        if ($bestGainRatio <= 0) {
            $majorityClass = $this->getMajorityClass($classes);
            return ['type' => 'leaf', 'class' => $majorityClass, 'probabilities' => $this->calculateDistribution($classes)];
        }

        // Buat node internal
        $node = [
            'type' => 'node',
            'attribute' => $bestAttribute,
            'children' => []
        ];

        // Dapatkan nilai unik dari atribut terbaik dalam dataset saat ini
        $attributeValues = array_unique(array_column($dataset, $bestAttribute));

        // Hapus atribut yang dipilih dari daftar untuk langkah rekursif selanjutnya
        $remainingAttributes = array_values(array_filter($attributes, fn($attr) => $attr !== $bestAttribute));

        // Bangun cabang secara rekursif untuk setiap nilai unik
        foreach ($attributeValues as $value) {
            // Subset S_v (data di mana atribut = nilai)
            $subset = array_filter($dataset, fn($row) => isset($row[$bestAttribute]) && $row[$bestAttribute] === $value);
            
            if (empty($subset)) {
                // Jika subset kosong, pasangkan daun dengan kelas mayoritas dari dataset INDUK
                $majorityClass = $this->getMajorityClass($classes);
                $node['children'][$value] = ['type' => 'leaf', 'class' => $majorityClass, 'probabilities' => $this->calculateDistribution($classes)];
            } else {
                // Rekursi
                $node['children'][$value] = $this->buildTree(array_values($subset), $remainingAttributes);
            }
        }

        return $node;
    }

    /**
     * Hitung Shannon Entropy dari sebuah dataset.
     */
    private function calculateEntropy($dataset)
    {
        $totalItems = count($dataset);
        if ($totalItems === 0) return 0;

        $classCounts = [];
        foreach ($dataset as $row) {
            $c = $row['class'];
            if (!isset($classCounts[$c])) {
                $classCounts[$c] = 0;
            }
            $classCounts[$c]++;
        }

        $entropy = 0;
        foreach ($classCounts as $count) {
            $probability = $count / $totalItems;
            $entropy -= $probability * log($probability, 2);
        }

        return $entropy;
    }

    /**
     * Hitung Gain Ratio untuk atribut tertentu.
     * Gain Ratio = Information Gain / Split Info
     */
    private function calculateGainRatio($dataset, $attribute, $entropyS)
    {


        
        $totalItems = count($dataset);
        if ($totalItems === 0) return 0;

        // Kelompokkan dataset berdasarkan nilai-nilai atribut
        $subsets = [];
        foreach ($dataset as $row) {
            $val = isset($row[$attribute]) ? $row[$attribute] : null;
            if (!isset($subsets[$val])) {
                $subsets[$val] = [];
            }
            $subsets[$val][] = $row;
        }

        // Jumlahkan bobot entropy (untuk Info Gain) dan Split Info
        $subsetEntropySum = 0;
        $splitInfo = 0;

        foreach ($subsets as $val => $subset) {
            $subsetSize = count($subset);
            $weight = $subsetSize / $totalItems;
            
            // Untuk Information Gain
            $subsetEntropy = $this->calculateEntropy($subset);
            $subsetEntropySum += $weight * $subsetEntropy;

            // Untuk Split Info
            $splitInfo -= $weight * log($weight, 2);
        }

        // Information Gain = Entropy(S) - Sum(Bobot * Entropy(S_v))
        $infoGain = $entropyS - $subsetEntropySum;

        // Jika SplitInfo 0 (semua instansi memiliki nilai atribut yang sama), Gain Ratio tidak terdefinisi/0
        if ($splitInfo == 0) {
            return 0;
        }

        // Gain Ratio = Info Gain / Split Info
        return $infoGain / $splitInfo;
    }

    /**
     * Fungsi bantuan (helper) untuk mendapatkan kelas yang paling sering muncul dalam array kelas yang diberikan.
     */
    private function getMajorityClass($classes)
    {
        if (empty($classes)) return null;
        
        $counts = array_count_values($classes);
        arsort($counts);
        return array_key_first($counts);
    }

    /**
     * Hitung distribusi (persentase kepercayaan) dari kelas-kelas.
     */
    private function calculateDistribution($classes)
    {
        if (empty($classes)) return [];
        $total = count($classes);
        $counts = array_count_values($classes);
        $distribution = [];
        foreach ($counts as $class => $count) {
            $distribution[$class] = round(($count / $total) * 100, 2);
        }
        arsort($distribution);
        return $distribution;
    }
}
