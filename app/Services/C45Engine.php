<?php

namespace App\Services;

use App\Models\Training;
use App\Models\C45Model;
use Illuminate\Support\Facades\Log;

class C45Engine
{
    /**
     * Membangun Model Pohon Keputusan C4.5.
     */
    public function generateModel()
    {
        // Ambil data training
        $trainings = Training::with('motor')->get();

        if ($trainings->isEmpty()) {
            throw new \Exception('Data training kosong. Silakan import dataset terlebih dahulu.');
        }

        // Format dataset
        $dataset = [];
        $attributes = [];

        foreach ($trainings as $training) {
            $row = [];
            
            // Atribut: Sistem Pembakaran (Injeksi / Karburator)
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

            // Target Kelas: Kerusakan
            $row['class'] = $training->kerusakan_id;

            $dataset[] = $row;
        }

        if (empty($dataset)) {
            throw new \Exception('Data training tidak memiliki format matriks fitur yang valid.');
        }

        // Ekstrak atribut gejala
        $attributes = array_keys($dataset[0]);
        $attributes = array_values(array_filter($attributes, fn($key) => $key !== 'class' && $key !== 'sistem_pembakaran'));

        // Ambil daftar Gejala Root dan Mapping Branch
        $rootGejalas = \App\Models\Gejala::where('is_root', true)->pluck('kode')->toArray();
        $branchMap = \App\Models\Gejala::whereNotNull('branch')->pluck('branch', 'kode')->toArray();

        // Buat Root Node (Sistem Pembakaran)
        $treeData = [
            'type' => 'node',
            'attribute' => 'sistem_pembakaran',
            'children' => []
        ];

        $pembakaranValues = array_unique(array_column($dataset, 'sistem_pembakaran'));

        foreach ($pembakaranValues as $val) {
            $subset = array_filter($dataset, fn($row) => isset($row['sistem_pembakaran']) && $row['sistem_pembakaran'] === $val);
            
            if (empty($subset)) {
                $classes = array_column($dataset, 'class');
                $majorityClass = $this->getMajorityClass($classes);
                $treeData['children'][$val] = ['type' => 'leaf', 'class' => $majorityClass, 'probabilities' => $this->calculateDistribution($classes)];
            } else {
                $treeData['children'][$val] = $this->buildTree(array_values($subset), $attributes, $rootGejalas, 2, null, $branchMap);
            }
        }

        // 5. Simpan model ke dalam Database
        C45Model::where('is_active', true)->update(['is_active' => false]);

        // Simpan model baru
        $model = C45Model::create([
            'tree_data' => $treeData,
            'accuracy' => null, // Akan diisi saat dievaluasi di halaman Pengujian
            'is_active' => true
        ]);

        return $model;
    }

    /**
     * Build Tree Rekursif.
     */
    private function buildTree($dataset, $attributes, $rootGejalas = [], $min_instances = 2, $activeBranch = null, $branchMap = [])
    {
        // Periksa apakah dataset kosong
        if (empty($dataset)) {
            return ['type' => 'leaf', 'class' => null]; // Tidak dapat menentukan
        }

        $classes = array_column($dataset, 'class');

        // Pre-pruning: Hentikan jika data <= minimum
        if (count($dataset) <= $min_instances) {
            $majorityClass = $this->getMajorityClass($classes);
            return ['type' => 'leaf', 'class' => $majorityClass, 'probabilities' => $this->calculateDistribution($classes)];
        }

        // Base case 1: Kelas homogen
        $uniqueClasses = array_unique($classes);
        if (count($uniqueClasses) === 1) {
            $c = array_values($uniqueClasses)[0];
            return ['type' => 'leaf', 'class' => $c, 'probabilities' => [$c => 100.0]];
        }

        // Base case 2: Atribut habis
        if (empty($attributes)) {
            $majorityClass = $this->getMajorityClass($classes);
            return ['type' => 'leaf', 'class' => $majorityClass, 'probabilities' => $this->calculateDistribution($classes)];
        }

        // Hitung Entropy dari dataset saat ini (S)
        $entropyS = $this->calculateEntropy($dataset);

        // Hitung Gain Ratio untuk setiap atribut
        $bestGainRatio = -1;
        $bestAttribute = null;

        $rootAttrs = array_values(array_filter($attributes, fn($attr) => in_array($attr, $rootGejalas)));
        $detailAttrs = array_values(array_filter($attributes, fn($attr) => !in_array($attr, $rootGejalas)));

        // Tentukan prioritas berdasarkan activeBranch
        $branchDetailAttrs = [];
        $otherDetailAttrs = [];
        if ($activeBranch) {
            foreach ($detailAttrs as $attr) {
                if (isset($branchMap[$attr]) && is_array($branchMap[$attr]) && in_array($activeBranch, $branchMap[$attr])) {
                    $branchDetailAttrs[] = $attr;
                } else {
                    $otherDetailAttrs[] = $attr;
                }
            }
            $priority1 = $branchDetailAttrs;
            $priority2 = $rootAttrs;
            $priority3 = $otherDetailAttrs;
        } else {
            $priority1 = $rootAttrs;
            $priority2 = $detailAttrs;
            $priority3 = [];
        }

        $priorities = [$priority1, $priority2, $priority3];

        // Evaluasi Prioritas berurutan
        foreach ($priorities as $priorityList) {
            if (empty($priorityList)) continue;
            
            foreach ($priorityList as $attribute) {
                $gainRatio = $this->calculateGainRatio($dataset, $attribute, $entropyS);
                if ($gainRatio > $bestGainRatio) {
                    $bestGainRatio = $gainRatio;
                    $bestAttribute = $attribute;
                }
            }

            // Jika menemukan atribut dengan Info Gain > 0 di level prioritas ini, hentikan pencarian
            if ($bestGainRatio > 0) {
                break;
            }
        }

        // Base case 3: Tidak ada Info Gain
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

        // Buat cabang untuk nilai 0 dan 1
        $attributeValues = [0, 1];

        // Hapus atribut terpilih
        $remainingAttributes = array_values(array_filter($attributes, fn($attr) => $attr !== $bestAttribute));

        // Bangun cabang secara rekursif untuk nilai 0 dan 1
        foreach ($attributeValues as $value) {
            // Subset S_v (data di mana atribut = nilai)
            $subset = array_filter($dataset, fn($row) => isset($row[$bestAttribute]) && $row[$bestAttribute] == $value);
            
            if (empty($subset)) {
                // Tandai 'unknown' jika data kosong untuk Fallback
                $node['children'][$value] = ['type' => 'unknown'];
            } else {
                // Tentukan activeBranch baru
                $isRootAttr = in_array($bestAttribute, $rootGejalas);
                $newActiveBranch = $activeBranch;
                if ($isRootAttr && $value == 1) {
                    $newActiveBranch = $bestAttribute;
                }

                // Rekursi
                $node['children'][$value] = $this->buildTree(array_values($subset), $remainingAttributes, $rootGejalas, $min_instances, $newActiveBranch, $branchMap);
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

        // Hindari division by zero
        if ($splitInfo == 0) {
            return 0;
        }

        // Gain Ratio = Info Gain / Split Info
        return $infoGain / $splitInfo;
    }

    /**
     * Dapatkan kelas mayoritas.
     */
    private function getMajorityClass($classes)
    {
        if (empty($classes)) return null;
        
        $counts = array_count_values($classes);
        arsort($counts);
        return array_key_first($counts);
    }

    /**
     * Hitung probabilitas kelas.
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

    /**
     * Prediksi C4.5 + Fallback.
     */
    public function predict($testData, $gejalaDipilih, $sistemPembakaran = null)
    {
        $activeModel = C45Model::where('is_active', true)->first();
        if (!$activeModel) return [];

        $tree = is_string($activeModel->tree_data) ? json_decode($activeModel->tree_data, true) : $activeModel->tree_data;
        $leafNode = $this->traverseTree($tree, $testData);

        return $this->predictFromLeaf($leafNode, $gejalaDipilih, $sistemPembakaran);
    }

    /**
     * Prediksi langsung dari leaf node.
     */
    public function predictFromLeaf($leafNode, $gejalaDipilih, $sistemPembakaran = null)
    {
        $predictedKerusakanId = $leafNode ? ($leafNode['class'] ?? null) : null;
        $probabilities = $leafNode ? ($leafNode['probabilities'] ?? []) : [];

        $top3 = [];
        $existingIds = [];

        if ($predictedKerusakanId) {
            $topKerusakanDb = \App\Models\Kerusakan::find($predictedKerusakanId);
            if ($topKerusakanDb) {
                // Probabilitas C4.5 murni
                foreach ($probabilities as $k_id => $conf) {
                    if (count($top3) >= 3) break;
                    $k = \App\Models\Kerusakan::find($k_id);
                    if ($k) {
                        $top3[] = [
                            'kerusakan' => $k,
                            'confidence' => round($conf, 1),
                        ];
                        $existingIds[] = $k_id;
                    }
                }

                // Urutkan probabilitas
                usort($top3, function ($a, $b) {
                    return $b['confidence'] <=> $a['confidence'];
                });
            }
        }

        // Fallback Confidence Score jika prediksi < 3
        if (count($top3) < 3) {
            $alternatives = $this->getAlternativePredictions($existingIds, $gejalaDipilih, 3 - count($top3), $sistemPembakaran);
            foreach ($alternatives as $k_id => $conf) {
                $k = \App\Models\Kerusakan::find($k_id);
                if ($k) {
                    // Batasi persentase alternatif
                    $maxConf = empty($top3) ? 99.0 : end($top3)['confidence'] - 0.1;
                    if ($conf > $maxConf) {
                        $conf = $maxConf;
                    }

                    $top3[] = [
                        'kerusakan' => $k,
                        'confidence' => round($conf, 1),
                    ];
                }
            }
        }

        return $top3;
    }

    /**
     * Traversal JSON Tree
     */
    public function traverseTree($node, $testData)
    {
        if (!isset($node['type'])) return null;
        if ($node['type'] === 'unknown') return null;
        if ($node['type'] === 'leaf') return $node;

        if ($node['type'] === 'node') {
            $attribute = $node['attribute'];
            $children = $node['children'];

            $userValue = isset($testData[$attribute]) ? $testData[$attribute] : null;

            if ($userValue !== null && isset($children[$userValue])) {
                return $this->traverseTree($children[$userValue], $testData);
            }
            return null;
        }

        return null;
    }

    /**
     * Hitung Fallback Confidence Score.
     */
    private function getAlternativePredictions($existingKerusakanIds, $gejalaDipilih, $limit, $sistemPembakaran = null)
    {
        $trainings = Training::with('motor')->get();

        if ($sistemPembakaran) {
            $trainings = $trainings->filter(function ($t) use ($sistemPembakaran) {
                if ($sistemPembakaran == 'Umum') return true;
                return $t->motor && $t->motor->sistem_pembakaran == $sistemPembakaran;
            });
        }

        $scores = [];

        foreach ($trainings as $t) {
            if (in_array($t->kerusakan_id, $existingKerusakanIds)) continue;

            $gejalasTrain = is_string($t->data_gejala) ? json_decode($t->data_gejala, true) : $t->data_gejala;
            if (!is_array($gejalasTrain)) continue;

            $trainSymptoms = [];
            foreach ($gejalasTrain as $kode => $val) {
                if ($val == 1) $trainSymptoms[] = $kode;
            }

            // Confidence Score: (Cocok / Total Gejala) * 100
            $intersection = count(array_intersect($gejalaDipilih, $trainSymptoms));
            $totalSymptoms = count($trainSymptoms);
            $score = $totalSymptoms > 0 ? ($intersection / $totalSymptoms) * 100 : 0;

            if (!isset($scores[$t->kerusakan_id]) || $score > $scores[$t->kerusakan_id]) {
                $scores[$t->kerusakan_id] = round($score, 1);
            }
        }

        arsort($scores);

        $results = [];
        foreach ($scores as $k_id => $score) {
            if ($score <= 0) continue;
            if (count($results) >= $limit) break;
            $results[$k_id] = $score;
        }

        return $results;
    }
}
