<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Motor;
use App\Models\Gejala;
use App\Models\Kerusakan;
use App\Models\Riwayat;
use Illuminate\Support\Facades\Auth;

class Prediksi extends Component
{
    public $step = 1;
    
    // Properties for Step 1
    public $selectedMerk = '';
    public $availableMotors = [];
    public $selectedMotorId = '';

    // Properties for Step 2
    public $listGejala = [];
    public $gejalaDipilih = [];

    // Properties for Step 3
    public $hasil = [];

    // Removed the hardcoded rules array. It is no longer needed!

    public function mount()
    {
        $this->listGejala = Gejala::orderBy('kode', 'asc')->get();
    }

    public function updatedSelectedMerk($val)
    {
        $this->selectedMotorId = '';
        if ($val) {
            $this->availableMotors = Motor::where('merk', $val)->get();
        } else {
            $this->availableMotors = [];
        }
    }

    public function nextStep()
    {
        $this->validate([
            'selectedMerk' => 'required',
            'selectedMotorId' => 'required'
        ], [
            'selectedMerk.required' => 'Merek pabrikan harus dipilih.',
            'selectedMotorId.required' => 'Tipe motor harus dipilih.'
        ]);

        $motor = Motor::find($this->selectedMotorId);
        if ($motor) {
            $this->listGejala = Gejala::whereIn('sistem_pembakaran', ['Keduanya', $motor->sistem_pembakaran])
                                      ->orderBy('kode', 'asc')
                                      ->get();
        } else {
            $this->listGejala = Gejala::orderBy('kode', 'asc')->get();
        }

        $this->step = 2;
    }

    public function prevStep()
    {
        $this->step = 1;
    }

    public function prosesPrediksi()
    {
        if (empty($this->gejalaDipilih)) {
            session()->flash('error', 'Pilih minimal satu gejala kendaraan Anda!');
            return;
        }

        $activeModel = \App\Models\C45Model::where('is_active', true)->first();
        
        if (!$activeModel) {
            session()->flash('error', 'Sistem pakar belum dilatih. Silakan hubungi Administrator untuk melakukan Generate Model C4.5 terlebih dahulu.');
            return;
        }

        $motor = Motor::find($this->selectedMotorId);
        
        // 1. Siapkan data testing (matriks fitur)
        $testData = [];
        $testData['sistem_pembakaran'] = $motor ? $motor->sistem_pembakaran : 'Umum';
        
        // Asumsikan semua gejala default 0 (Tidak), lalu yang dipilih adalah 1 (Ya)
        $semuaGejala = Gejala::pluck('kode')->toArray();
        foreach ($semuaGejala as $kode) {
            $testData[$kode] = in_array($kode, $this->gejalaDipilih) ? 1 : 0;
        }

        // 2. Traversal Decision Tree
        $tree = $activeModel->tree_data;
        $leafNode = $this->traverseTree($tree, $testData);

        $predictedKerusakanId = $leafNode ? ($leafNode['class'] ?? null) : null;
        $probabilities = $leafNode ? ($leafNode['probabilities'] ?? []) : [];

        $top3 = [];
        $existingIds = [];

        if ($predictedKerusakanId) {
            $topKerusakanDb = Kerusakan::find($predictedKerusakanId);
            if ($topKerusakanDb) {
                // 1. Ambil dari probabilitas daun C4.5 (MURNI C4.5)
                foreach ($probabilities as $k_id => $conf) {
                    if (count($top3) >= 3) break;
                    $k = Kerusakan::find($k_id);
                    if ($k) {
                        $top3[] = [
                            'kerusakan' => $k,
                            'confidence' => round($conf, 1)
                        ];
                        $existingIds[] = $k_id;
                    }
                }

                // Urutkan probabilitas bawaan C4.5
                usort($top3, function($a, $b) {
                    return $b['confidence'] <=> $a['confidence'];
                });
            }
        }

        // Jika C4.5 gagal penuh atau kurang dari 3, ambil dari Jaccard
        if (count($top3) < 3) {
            // Filter alternatif sesuai sistem pembakaran
            $alternatives = $this->getAlternativePredictions($existingIds, $this->gejalaDipilih, 3 - count($top3), $motor ? $motor->sistem_pembakaran : null);
            foreach ($alternatives as $k_id => $conf) {
                $k = Kerusakan::find($k_id);
                if ($k) {
                    // Pastikan persentase alternatif murni sekadar pelengkap
                    $maxConf = empty($top3) ? 99 : end($top3)['confidence'] - 0.1;
                    if ($conf > $maxConf) {
                        $conf = $maxConf;
                    }

                    $top3[] = [
                        'kerusakan' => $k,
                        'confidence' => round($conf, 1)
                    ];
                }
            }
        }

        if (count($top3) > 0) {
            $this->hasil = [
                'kerusakan' => $top3[0]['kerusakan'], // Top 1 default
                'gejala_dipilih' => $this->gejalaDipilih,
                'top_3' => $top3
            ];

            // Simpan Histori ke Riwayats
            if (Auth::check()) {
                $motorTerpilih = Motor::find($this->selectedMotorId);
                
                Riwayat::create([
                    'user_id' => Auth::id(),
                    'kerusakan_id' => $top3[0]['kerusakan']->id,
                    'gejala_dipilih' => $this->gejalaDipilih,
                    'motor_id' => $this->selectedMotorId,
                    'sistem_pembakaran' => $motorTerpilih ? $motorTerpilih->sistem_pembakaran : null,
                    'confidence' => $top3[0]['confidence']
                ]);
            }

            $this->step = 3;
        } else {
            session()->flash('error', 'Sistem tidak dapat mengklasifikasikan kerusakan berdasarkan gejala tersebut.');
        }
    }

    /**
     * Rekursif untuk menelusuri JSON Pohon Keputusan (C4.5 Tree Traversal)
     */
    private function traverseTree($node, $testData)
    {
        if (!isset($node['type'])) return null;

        // Node 'unknown' = cabang kosong tanpa data latih, C4.5 tidak bisa menebak
        if ($node['type'] === 'unknown') return null;

        if ($node['type'] === 'leaf') {
            return $node; // Return the entire leaf node containing class and probabilities
        }

        if ($node['type'] === 'node') {
            $attribute = $node['attribute'];
            $children = $node['children'];

            // Apa nilai dari fitur ini pada inputan user?
            $userValue = isset($testData[$attribute]) ? $testData[$attribute] : null;

            // Jika node memiliki cabang untuk nilai user tersebut
            if ($userValue !== null && isset($children[$userValue])) {
                return $this->traverseTree($children[$userValue], $testData);
            }

            // Jika dataset testing memiliki nilai yang TIDAK PERNAH dilihat saat training (Outlier)
            // Hentikan C4.5 agar sistem beralih ke Fallback (Jaccard Similarity).
            return null;
        }

        return null;
    }



    /**
     * Hitung prediksi alternatif berdasarkan kemiripan (Jaccard Similarity) 
     * antara gejala user dengan data training historis.
     */
    private function getAlternativePredictions($existingKerusakanIds, $gejalaDipilih, $limit, $sistemPembakaran = null)
    {
        $trainings = \App\Models\Training::with('motor')->get();

        if ($sistemPembakaran) {
            $trainings = $trainings->filter(function($t) use ($sistemPembakaran) {
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
                if ($val == 1) {
                    $trainSymptoms[] = $kode;
                }
            }

            // Jaccard Similarity
            $intersection = count(array_intersect($gejalaDipilih, $trainSymptoms));
            $union = count(array_unique(array_merge($gejalaDipilih, $trainSymptoms)));
            $score = $union > 0 ? ($intersection / $union) * 100 : 0;

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

    public function ulangi()
    {
        $this->reset(['step', 'selectedMerk', 'selectedMotorId', 'gejalaDipilih', 'hasil', 'availableMotors']);
    }

    public function render()
    {
        return view('livewire.user.prediksi')
            ->layout('livewire.user.layouts.app');
    }
}
