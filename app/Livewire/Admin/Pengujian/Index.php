<?php

namespace App\Livewire\Admin\Pengujian;

use Livewire\Component;
use App\Models\C45Model;

class Index extends Component
{
    public $activeModel;
    public $confusionMatrix = null;
    public $accuracy = null;
    public $precision = null;
    public $recall = null;
    public $totalData = 0;

    public function mount()
    {   
        $this->activeModel = C45Model::where('is_active', true)->latest()->first();
        // Auto-evaluate when visiting the page
        if ($this->activeModel) {
            $this->evaluateModel();
        }
    }

    public function evaluateModel()
    {
        if (!$this->activeModel) {
            session()->flash('error', 'Tidak ada model aktif untuk dievaluasi.');
            return;
        }

        $trainings = \App\Models\Training::with(['motor', 'kerusakan'])->get();
        $tree = is_string($this->activeModel->tree_data) ? json_decode($this->activeModel->tree_data, true) : $this->activeModel->tree_data;
        $semuaGejala = \App\Models\Gejala::pluck('kode')->toArray();

        $matrix = [];
        $correct = 0;
        $total = 0;

        $tp = [];
        $fp = [];
        $fn = [];

        $semuaKerusakan = \App\Models\Kerusakan::pluck('id')->toArray();
        foreach ($semuaKerusakan as $k_id) {
            $tp[$k_id] = 0;
            $fp[$k_id] = 0;
            $fn[$k_id] = 0;
        }

        foreach ($trainings as $t) {
            $testData = [];
            $testData['sistem_pembakaran'] = $t->motor ? $t->motor->sistem_pembakaran : 'Umum';
            $gejalas = is_string($t->data_gejala) ? json_decode($t->data_gejala, true) : $t->data_gejala;
            
            foreach ($semuaGejala as $kode) {
                $testData[$kode] = (isset($gejalas[$kode]) && $gejalas[$kode] == 1) ? 1 : 0;
            }

            $predictedNode = $this->traverseTree($tree, $testData);
            $predictedId = $predictedNode ? $predictedNode['class'] : 'Unknown';
            $actualId = $t->kerusakan_id;

            if (!isset($matrix[$actualId])) {
                $matrix[$actualId] = [];
            }
            if (!isset($matrix[$actualId][$predictedId])) {
                $matrix[$actualId][$predictedId] = 0;
            }

            $matrix[$actualId][$predictedId]++;
            $total++;
            
            if ($actualId == $predictedId) {
                $correct++;
                if (isset($tp[$actualId])) $tp[$actualId]++;
            } else {
                if (isset($fn[$actualId])) $fn[$actualId]++;
                if ($predictedId != 'Unknown' && isset($fp[$predictedId])) $fp[$predictedId]++;
            }
        }

        // Kalkulasi Macro Average Precision & Recall
        $precisions = [];
        $recalls = [];

        foreach ($semuaKerusakan as $k_id) {
            $true_pos = $tp[$k_id];
            $false_pos = $fp[$k_id];
            $false_neg = $fn[$k_id];

            if (($true_pos + $false_pos) > 0) {
                $precisions[] = $true_pos / ($true_pos + $false_pos);
            } else {
                // Jika tidak ada prediksi positif sama sekali untuk kelas ini
                $precisions[] = 0;
            }

            if (($true_pos + $false_neg) > 0) {
                $recalls[] = $true_pos / ($true_pos + $false_neg);
            } else {
                // Jika tidak ada data aktual sama sekali untuk kelas ini (tidak mungkin jika training seimbang)
                // $recalls[] = 0; // Sebaiknya diabaikan agar tidak merusak rata-rata jika kelas tidak ada di test set
            }
        }

        $this->totalData = $total;
        $this->accuracy = $total > 0 ? round(($correct / $total) * 100, 2) : 0;
        $this->precision = count($precisions) > 0 ? round((array_sum($precisions) / count($precisions)) * 100, 2) : 0;
        $this->recall = count($recalls) > 0 ? round((array_sum($recalls) / count($recalls)) * 100, 2) : 0;
        
        $this->confusionMatrix = $matrix;
    }

    private function traverseTree($node, $testData)
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

    public function render()
    {
        return view('livewire.admin.pengujian.index', [
            'kerusakans' => \App\Models\Kerusakan::all()->keyBy('id')
        ])->layout('livewire.admin.layouts.app');
    }
}
