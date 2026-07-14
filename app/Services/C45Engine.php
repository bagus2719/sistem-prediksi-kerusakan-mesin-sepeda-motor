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
        // Mengambil semua data latih (dataset) dari database beserta relasi tipe motornya
        $trainings = Training::with('motor')->get();

        // Jika tidak ada data latih sama sekali di database, hentikan proses dan lemparkan error
        if ($trainings->isEmpty()) {
            throw new \Exception('Data training kosong. Silakan import dataset terlebih dahulu.');
        }

        // Variabel penampung untuk format dataset mentah yang siap dihitung
        $dataset = [];
        $attributes = [];

        // Melakukan perulangan (loop) pada setiap baris data latih
        foreach ($trainings as $training) {
            $row = []; // Array sementara untuk menampung satu baris data
            
            // ATRIBUT 1: Memasukkan data sistem pembakaran (Injeksi/Karburator)
            // Jika relasi motor ditemukan, gunakan sistem pembakarannya
            if ($training->motor) {
                $row['sistem_pembakaran'] = $training->motor->sistem_pembakaran;
            } else {
                // Jika tidak ada relasi motor, berikan nilai default 'Umum'
                $row['sistem_pembakaran'] = 'Umum';
            }

            // ATRIBUT 2: Memasukkan daftar gejala (G01, G02, dst)
            // Cek apakah data_gejala bentuknya string JSON. Jika iya, ubah jadi array (json_decode)
            $gejalas = is_string($training->data_gejala) ? json_decode($training->data_gejala, true) : $training->data_gejala;
            // Jika berhasil diubah jadi array, lakukan perulangan untuk setiap gejala
            if (is_array($gejalas)) {
                foreach ($gejalas as $kode => $nilai) {
                    $row[$kode] = $nilai; // Menyimpan data gejala ke baris, misal. "G01" => 1 (Ya) atau 0 (Tidak)
                }
            }

            // TARGET KELAS: Memasukkan ID kerusakan (penyakit) sebagai target atau kesimpulan dari baris ini
            $row['class'] = $training->kerusakan_id;

            // Memasukkan baris yang sudah rapi ini ke dalam variabel utama dataset
            $dataset[] = $row;
        }

        // Jika setelah diolah datasetnya kosong, lemparkan error
        if (empty($dataset)) {
            throw new \Exception('Data training tidak memiliki format matriks fitur yang valid.');
        }

        // EKSTRAKSI ATRIBUT GEJALA
        // Mengambil nama-nama kolom dari baris pertama dataset (misal: sistem_pembakaran, G01, G02, class)
        $attributes = array_keys($dataset[0]);
        // Membuang kolom 'class' (target) dan 'sistem_pembakaran' (node paksa) agar tidak ikut dihitung Entropy-nya
        $attributes = array_values(array_filter($attributes, fn($key) => $key !== 'class' && $key !== 'sistem_pembakaran'));

        // AMBIL PRIORITAS GEJALA
        // Mencari semua kode gejala yang berstatus 'is_root' (akar/utama)
        $rootGejalas = \App\Models\Gejala::where('is_root', true)->pluck('kode')->toArray();
        // Mencari gejala yang memiliki cabang (branch) turunan
        $branchMap = \App\Models\Gejala::whereNotNull('branch')->pluck('branch', 'kode')->toArray();

        // MEMBUAT ROOT NODE SECARA MANUAL
        // Sistem ini memaksa agar pemisahan pohon pertama kali didasarkan pada tipe mesin (sistem pembakaran)
        $treeData = [
            'type' => 'node', // Menandakan ini adalah titik cabang, bukan kesimpulan (leaf)
            'attribute' => 'sistem_pembakaran', // Nama kolom yang menjadi cabang
            'children' => [] // Variabel untuk menyimpan anak cabang selanjutnya
        ];

        // Mengambil semua jenis sistem pembakaran yang ada di dataset (misal: 'Injeksi', 'Karburator')
        $pembakaranValues = array_unique(array_column($dataset, 'sistem_pembakaran'));

        // Melakukan perulangan untuk setiap tipe mesin pembakaran
        foreach ($pembakaranValues as $val) {
            // Memfilter dataset agar hanya berisi data untuk tipe mesin tersebut saja (misal: Injeksi saja)
            $subset = array_filter($dataset, fn($row) => isset($row['sistem_pembakaran']) && $row['sistem_pembakaran'] === $val);
            
            // Jika data untuk mesin tersebut kosong
            if (empty($subset)) {
                // Ambil semua class dari keseluruhan dataset
                $classes = array_column($dataset, 'class');
                // Cari class yang paling sering muncul (mayoritas)
                $majorityClass = $this->getMajorityClass($classes);
                // Jadikan cabang ini sebagai Daun (Leaf) / Kesimpulan langsung
                $treeData['children'][$val] = ['type' => 'leaf', 'class' => $majorityClass, 'probabilities' => $this->calculateDistribution($classes)];
            } else {
                // Jika data ada, lempar ke fungsi algoritma C4.5 (buildTree) untuk menghitung Entropy dan membuat cabang selanjutnya
                $treeData['children'][$val] = $this->buildTree(array_values($subset), $attributes, $rootGejalas, 2, null, $branchMap);
            }
        }

        // PENYIMPANAN MODEL C4.5
        // Menonaktifkan model pohon keputusan yang lama di database
        C45Model::where('is_active', true)->update(['is_active' => false]);

        // Menyimpan struktur pohon keputusan JSON yang baru dibuat ke database
        $model = C45Model::create([
            'tree_data' => $treeData, // Data struktur pohon
            'accuracy' => null, // Akurasi akan diisi nanti di halaman pengujian
            'is_active' => true // Mengaktifkan model ini sebagai model utama
        ]);

        return $model; // Mengembalikan model yang berhasil dibuat
    }

    /**
     * Build Tree Rekursif.
     */
    private function buildTree($dataset, $attributes, $rootGejalas = [], $min_instances = 2, $activeBranch = null, $branchMap = [])
    {
        // 1. Jika data sisa kosong, maka tidak bisa mengambil keputusan. Kembalikan nilai null.
        if (empty($dataset)) {
            return ['type' => 'leaf', 'class' => null];
        }

        // Mengambil seluruh kolom target class (kerusakan_id) dari data sisa
        $classes = array_column($dataset, 'class');

        // PRE-PRUNING (Pencegahan Overfitting)
        // 2. Jika sisa data <= batas minimal (contoh: 2 baris), paksa berhenti agar pohon tidak terlalu rumit
        if (count($dataset) <= $min_instances) {
            $majorityClass = $this->getMajorityClass($classes); // Cari kerusakan mayoritas
            // Ubah jadi Leaf (Daun/Kesimpulan)
            return ['type' => 'leaf', 'class' => $majorityClass, 'probabilities' => $this->calculateDistribution($classes)];
        }

        // BASE CASE 1: KELAS HOMOGEN
        // Mengecek seberapa banyak jenis kerusakan yang berbeda di sisa data
        $uniqueClasses = array_unique($classes);
        // 3. Jika hanya ada 1 jenis kerusakan saja (100% homogen/murni), langsung hentikan
        if (count($uniqueClasses) === 1) {
            $c = array_values($uniqueClasses)[0]; // Ambil ID kerusakan tersebut
            // Ubah jadi Leaf dengan probabilitas mutlak 100%
            return ['type' => 'leaf', 'class' => $c, 'probabilities' => [$c => 100.0]];
        }

        // BASE CASE 2: ATRIBUT HABIS
        // 4. Jika semua gejala sudah dipakai dan habis, tapi kerusakannya masih beragam
        if (empty($attributes)) {
            $majorityClass = $this->getMajorityClass($classes); // Ambil kesimpulan dari kelas terbanyak
            return ['type' => 'leaf', 'class' => $majorityClass, 'probabilities' => $this->calculateDistribution($classes)];
        }

        // MENGHITUNG ENTROPY SEMESTA (S)
        // 5. Menghitung tingkat kekotoran/keberagaman dari data saat ini
        $entropyS = $this->calculateEntropy($dataset);

        // VARIABEL PENCARIAN ATRIBUT TERBAIK
        $bestGainRatio = -1; // Menyimpan nilai Gain Ratio tertinggi (dimulai dari -1)
        $bestAttribute = null; // Menyimpan nama gejala dengan nilai terbaik

        // Membagi sisa atribut ke dalam dua kelompok: Gejala Utama (Root) dan Gejala Detail
        $rootAttrs = array_values(array_filter($attributes, fn($attr) => in_array($attr, $rootGejalas)));
        $detailAttrs = array_values(array_filter($attributes, fn($attr) => !in_array($attr, $rootGejalas)));

        // PRIORITAS ATRIBUT (MODIFIKASI CUSTOM)
        // Menentukan prioritas gejala mana yang harus dihitung lebih dulu berdasarkan cabang aktif
        $branchDetailAttrs = [];
        $otherDetailAttrs = [];
        
        if ($activeBranch) {
            // Jika ada cabang aktif (contoh: user sedang di bagian CVT)
            foreach ($detailAttrs as $attr) {
                // Masukkan gejala ke prioritas utama jika gejala tersebut adalah turunan dari cabang CVT
                if (isset($branchMap[$attr]) && is_array($branchMap[$attr]) && in_array($activeBranch, $branchMap[$attr])) {
                    $branchDetailAttrs[] = $attr;
                } else {
                    // Jika bukan, masukkan ke prioritas akhir
                    $otherDetailAttrs[] = $attr;
                }
            }
            $priority1 = $branchDetailAttrs; // Prioritas 1: Gejala turunan
            $priority2 = $rootAttrs; // Prioritas 2: Gejala Root
            $priority3 = $otherDetailAttrs; // Prioritas 3: Gejala sisa
        } else {
            // Jika tidak ada cabang aktif, utamakan Gejala Root
            $priority1 = $rootAttrs;
            $priority2 = $detailAttrs;
            $priority3 = [];
        }

        // Menggabungkan prioritas menjadi antrean pengecekan
        $priorities = [$priority1, $priority2, $priority3];

        // EVALUASI MENCARI GAIN RATIO TERTINGGI (INTI C4.5)
        foreach ($priorities as $priorityList) {
            if (empty($priorityList)) continue; // Lewati jika daftar kosong
            
            // Looping ke semua atribut di level prioritas ini
            foreach ($priorityList as $attribute) {
                // 6. Hitung Gain Ratio dari gejala ini (Information Gain / Split Info)
                $gainRatio = $this->calculateGainRatio($dataset, $attribute, $entropyS);
                
                // Jika Gain Ratio lebih besar dari yang terbaik saat ini
                if ($gainRatio > $bestGainRatio) {
                    $bestGainRatio = $gainRatio; // Perbarui rekor Gain Ratio tertinggi
                    $bestAttribute = $attribute; // Tetapkan gejala ini sebagai gejala terbaik sementara
                }
            }

            // Jika di level prioritas ini sudah ditemukan gejala yang berguna (Gain > 0), hentikan pencarian ke prioritas bawah
            if ($bestGainRatio > 0) {
                break;
            }
        }

        // BASE CASE 3: TIDAK ADA INFORMATION GAIN
        // 7. Jika dari semua gejala nilainya 0 atau mines, artinya gejala yang tersisa tidak berguna lagi untuk memecah data
        if ($bestGainRatio <= 0) {
            $majorityClass = $this->getMajorityClass($classes); // Ambil kelas terbanyak
            return ['type' => 'leaf', 'class' => $majorityClass, 'probabilities' => $this->calculateDistribution($classes)];
        }

        // MEMBUAT NODE BARU (Cabang Gejala Terbaik)
        $node = [
            'type' => 'node', // Ini adalah cabang
            'attribute' => $bestAttribute, // Gejala terbaik terpilih (contoh: 'G01')
            'children' => [] // Wadah untuk anak cabangnya (0 dan 1)
        ];

        // Nilai boolean untuk gejala: 0 (Tidak) dan 1 (Ya)
        $attributeValues = [0, 1];

        // 8. Menghapus gejala yang terpilih dari daftar atribut agar tidak dihitung lagi di level bawahnya
        $remainingAttributes = array_values(array_filter($attributes, fn($attr) => $attr !== $bestAttribute));

        // MEMECAH CABANG (Rekursif)
        // Melakukan perulangan untuk nilai 0 dan 1 pada gejala yang terpilih
        foreach ($attributeValues as $value) {
            // Mengambil baris data yang nilai gejalanya sama dengan $value (0 atau 1)
            $subset = array_filter($dataset, fn($row) => isset($row[$bestAttribute]) && $row[$bestAttribute] == $value);
            
            if (empty($subset)) {
                // Jika datanya tidak ada, pasang tipe 'unknown' (tidak diketahui) untuk dijadikan fallback nanti
                $node['children'][$value] = ['type' => 'unknown'];
            } else {
                // Melacak apakah gejala yang terpilih ini adalah Gejala Root
                $isRootAttr = in_array($bestAttribute, $rootGejalas);
                $newActiveBranch = $activeBranch; // Bawa silsilah cabang aktif
                // Jika ini Gejala Root dan dijawab Ya (1), set ini sebagai cabang aktif yang baru
                if ($isRootAttr && $value == 1) {
                    $newActiveBranch = $bestAttribute;
                }

                // 9. Panggil kembali fungsi buildTree untuk anak cabang ini (Rekursif)
                $node['children'][$value] = $this->buildTree(array_values($subset), $remainingAttributes, $rootGejalas, $min_instances, $newActiveBranch, $branchMap);
            }
        }

        // Kembalikan node cabang yang sudah selesai disusun
        return $node;
    }

    /**
     * Hitung Shannon Entropy dari sebuah dataset.
     * Mengukur ketidakteraturan data. Semakin beragam kerusakan, nilai semakin mendekati 1.
     */
    private function calculateEntropy($dataset)
    {
        $totalItems = count($dataset); // Hitung total baris data
        if ($totalItems === 0) return 0; // Jika kosong, kembalikan 0

        // Menghitung jumlah masing-masing kelas (kerusakan) di dalam data
        $classCounts = [];
        foreach ($dataset as $row) {
            $c = $row['class'];
            if (!isset($classCounts[$c])) {
                $classCounts[$c] = 0; // Inisialisasi dari 0 jika belum ada
            }
            $classCounts[$c]++; // Tambah 1 hitungan
        }

        // Variabel penampung hasil Entropy
        $entropy = 0;
        // Rumus Shannon Entropy: -Sigma (Probabilitas * Logaritma Basis 2 dari Probabilitas)
        foreach ($classCounts as $count) {
            $probability = $count / $totalItems; // Hitung peluang (P)
            $entropy -= $probability * log($probability, 2); // Terapkan rumus
        }

        return $entropy; // Kembalikan nilai Entropy
    }

    /**
     * Hitung Gain Ratio untuk atribut tertentu.
     * Gain Ratio = Information Gain / Split Info.
     */
    private function calculateGainRatio($dataset, $attribute, $entropyS)
    {
        $totalItems = count($dataset); // Total baris data
        if ($totalItems === 0) return 0;

        // Kelompokkan data berdasarkan nilai atribut (contoh: kelompok 1 dan kelompok 0)
        $subsets = [];
        foreach ($dataset as $row) {
            $val = isset($row[$attribute]) ? $row[$attribute] : null;
            if (!isset($subsets[$val])) {
                $subsets[$val] = []; // Buat wadah baru
            }
            $subsets[$val][] = $row; // Masukkan baris ke kelompok yang sesuai
        }

        // Variabel untuk menjumlahkan Bobot Entropy anak cabang dan Split Info
        $subsetEntropySum = 0;
        $splitInfo = 0;

        // Melakukan perulangan untuk setiap kelompok (0 dan 1)
        foreach ($subsets as $val => $subset) {
            $subsetSize = count($subset); // Jumlah data di kelompok ini
            $weight = $subsetSize / $totalItems; // Hitung bobot (Porsi data terhadap keseluruhan)
            
            // Hitung Entropy untuk kelompok ini dan kalikan dengan bobotnya
            $subsetEntropy = $this->calculateEntropy($subset);
            $subsetEntropySum += $weight * $subsetEntropy;

            // Hitung nilai Split Info dari cabang ini
            $splitInfo -= $weight * log($weight, 2);
        }

        // INFORMATION GAIN: Entropy Semesta dikurangi total Entropy anak cabang
        $infoGain = $entropyS - $subsetEntropySum;

        // Jika Split Info 0, kembalikan 0 untuk menghindari error Division by Zero (pembagian dengan nol)
        if ($splitInfo == 0) {
            return 0;
        }

        // GAIN RATIO: Information Gain dibagi dengan Split Info
        return $infoGain / $splitInfo;
    }

    /**
     * Dapatkan kelas mayoritas (Kerusakan yang paling sering muncul).
     * Digunakan saat mengubah cabang mentok menjadi kesimpulan akhir (Leaf).
     */
    private function getMajorityClass($classes)
    {
        if (empty($classes)) return null; // Jika array kosong
        
        $counts = array_count_values($classes); // Hitung kemunculan masing-masing kelas
        arsort($counts); // Urutkan dari yang terbanyak (Descending)
        return array_key_first($counts); // Ambil kunci (ID Kerusakan) yang paling atas
    }

    /**
     * Hitung persentase/distribusi probabilitas setiap kelas di sebuah simpul/daun.
     */
    private function calculateDistribution($classes)
    {
        if (empty($classes)) return [];
        $total = count($classes); // Total data di simpul
        $counts = array_count_values($classes); // Hitung kemunculan tiap kelas
        $distribution = [];
        
        // Loop tiap kelas dan jadikan persentase
        foreach ($counts as $class => $count) {
            $distribution[$class] = round(($count / $total) * 100, 2);
        }
        arsort($distribution); // Urutkan dari persentase terbesar
        return $distribution;
    }

    /**
     * Prediksi C4.5 + Fallback (Pemanggilan dari Halaman User).
     */
    public function predict($testData, $gejalaDipilih, $sistemPembakaran = null)
    {
        // 1. Ambil model pohon yang sedang aktif di database
        $activeModel = C45Model::where('is_active', true)->first();
        if (!$activeModel) return []; // Kosong jika belum ada model

        // 2. Baca string JSON dari database dan ubah jadi Array
        $tree = is_string($activeModel->tree_data) ? json_decode($activeModel->tree_data, true) : $activeModel->tree_data;
        
        // 3. Susuri (traverse) pohon dari akar ke bawah berdasarkan jawaban user
        $leafNode = $this->traverseTree($tree, $testData);

        // 4. Setelah sampai di ujung, ambil prediksinya
        return $this->predictFromLeaf($leafNode, $gejalaDipilih, $sistemPembakaran);
    }

    /**
     * Prediksi langsung dari titik Daun (Leaf Node).
     */
    public function predictFromLeaf($leafNode, $gejalaDipilih, $sistemPembakaran = null)
    {
        $predictedKerusakanId = $leafNode ? ($leafNode['class'] ?? null) : null;
        $probabilities = $leafNode ? ($leafNode['probabilities'] ?? []) : [];

        $top3 = []; // Menyimpan hasil Top 3
        $existingIds = []; // Mencatat ID kerusakan yang sudah diprediksi agar tidak dobel

        // Jika berhasil mendapat ID kerusakan mutlak dari C4.5
        if ($predictedKerusakanId) {
            $topKerusakanDb = \App\Models\Kerusakan::find($predictedKerusakanId);
            if ($topKerusakanDb) {
                // Loop pada semua probabilitas kerusakan di node ini
                foreach ($probabilities as $k_id => $conf) {
                    if (count($top3) >= 3) break; // Batasi maksimal 3
                    $k = \App\Models\Kerusakan::find($k_id);
                    if ($k) {
                        // Simpan ke array Top 3
                        $top3[] = [
                            'kerusakan' => $k,
                            'confidence' => round($conf, 1),
                        ];
                        $existingIds[] = $k_id;
                    }
                }

                // Mengurutkan Top 3 dari probabilitas terbesar ke terkecil
                usort($top3, function ($a, $b) {
                    return $b['confidence'] <=> $a['confidence'];
                });
            }
        }

        // FALLBACK: Similarity Checker
        // Jika prediksi C4.5 menghasilkan daftar kurang dari 3 penyakit (karena data homogen)
        if (count($top3) < 3) {
            // Panggil algoritma pencocokan alternatif untuk menambal sisa kekurangan Top 3
            $alternatives = $this->getAlternativePredictions($existingIds, $gejalaDipilih, 3 - count($top3), $sistemPembakaran);
            foreach ($alternatives as $k_id => $conf) {
                $k = \App\Models\Kerusakan::find($k_id);
                if ($k) {
                    // MENCEGAH KETIDAKLOGISAN
                    // Pastikan persentase penyakit alternatif TIDAK BOLEH melebihi penyakit utama dari C4.5
                    // Menurunkan batas maksimal dari 99% menjadi 85% agar lebih realistis (Revisi Sidang)
                    $maxConf = empty($top3) ? 85.0 : end($top3)['confidence'] - 0.1;
                    if ($conf > $maxConf) {
                        $conf = $maxConf; // Paksa nilai mentok di bawah penyakit utama
                    }

                    // Tambahkan ke Top 3
                    $top3[] = [
                        'kerusakan' => $k,
                        'confidence' => round($conf, 1),
                    ];
                }
            }
        }

        return $top3; // Kembalikan array berisi objek kerusakan dan angka kepercayaannya
    }

    /**
     * Traversal JSON Tree (Menyusuri Pohon Keputusan).
     */
    public function traverseTree($node, $testData)
    {
        // 1. Cek validitas node (titik cabang)
        if (!isset($node['type'])) return null;
        if ($node['type'] === 'unknown') return null;
        
        // 2. Jika tipe node adalah 'leaf', proses pencarian selesai, kembalikan daunnya
        if ($node['type'] === 'leaf') return $node;

        // 3. Jika tipe node adalah 'node' (cabang)
        if ($node['type'] === 'node') {
            $attribute = $node['attribute']; // Ambil nama gejalanya
            $children = $node['children']; // Ambil cabangnya (0/1)

            // Lihat jawaban user untuk gejala ini (contoh: apakah user menjawab 1 pada G01?)
            $userValue = isset($testData[$attribute]) ? $testData[$attribute] : null;

            // Jika user punya jawaban, dan cabangnya tersedia di pohon
            if ($userValue !== null && isset($children[$userValue])) {
                // Susuri terus ke bawah (Rekursif traversal) mengikuti jalur jawaban user
                return $this->traverseTree($children[$userValue], $testData);
            }
            // Mentok atau data user tidak dikenali
            return null;
        }

        return null;
    }

    /**
     * Algoritma Alternatif (Similarity Confidence Score).
     * Berfungsi jika algoritma C4.5 menghasilkan prediksi mutlak kurang dari 3.
     */
    private function getAlternativePredictions($existingKerusakanIds, $gejalaDipilih, $limit, $sistemPembakaran = null)
    {
        $trainings = Training::with('motor')->get(); // Ambil seluruh data latih

        // Jika user spesifik Injeksi/Karbu, filter datanya agar tidak melenceng
        if ($sistemPembakaran) {
            $trainings = $trainings->filter(function ($t) use ($sistemPembakaran) {
                if ($sistemPembakaran == 'Umum') return true;
                return $t->motor && $t->motor->sistem_pembakaran == $sistemPembakaran;
            });
        }

        $scores = []; // Tempat menampung skor

        // Periksa setiap baris di data latih
        foreach ($trainings as $t) {
            // Abaikan penyakit yang sudah masuk ke daftar utama C4.5 (hindari duplikat)
            if (in_array($t->kerusakan_id, $existingKerusakanIds)) continue;

            // Buka JSON gejala di data latih ini
            $gejalasTrain = is_string($t->data_gejala) ? json_decode($t->data_gejala, true) : $t->data_gejala;
            if (!is_array($gejalasTrain)) continue;

            $trainSymptoms = [];
            // Cari tahu gejala apa saja yang dimiliki kerusakan pada baris data latih ini
            foreach ($gejalasTrain as $kode => $val) {
                if ($val == 1) $trainSymptoms[] = $kode;
            }

            // RUMUS KEMIRIPAN (Similarity): 
            // Gejala yang sama (Irisan / Intersection) dibagi dengan Total Gejala Penyakit tersebut
            $intersection = count(array_intersect($gejalaDipilih, $trainSymptoms));
            $totalSymptoms = count($trainSymptoms);
            // Hitung ke persen (100)
            $score = $totalSymptoms > 0 ? ($intersection / $totalSymptoms) * 100 : 0;

            // Simpan skor tertinggi dari setiap jenis penyakit
            if (!isset($scores[$t->kerusakan_id]) || $score > $scores[$t->kerusakan_id]) {
                $scores[$t->kerusakan_id] = round($score, 1);
            }
        }

        // Urutkan nilai skor dari yang terbesar ke terkecil
        arsort($scores);

        $results = [];
        // Masukkan hasil ke array sesuai batas kuota yang dibutuhkan (misal cuma butuh 2 lagi untuk Top 3)
        foreach ($scores as $k_id => $score) {
            if ($score <= 0) continue; // Abaikan penyakit yang skor kemiripannya 0%
            if (count($results) >= $limit) break; // Jika kuota terpenuhi, berhenti mencari
            $results[$k_id] = $score;
        }

        return $results; // Kirimkan hasilnya kembali ke pemanggil
    }
}
