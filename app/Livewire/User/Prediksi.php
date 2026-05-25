<?php

namespace App\Livewire\User;

use App\Models\C45Model;
use App\Models\Gejala;
use App\Models\Kerusakan;
use App\Models\Motor;
use App\Models\Riwayat;
use App\Models\Training;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Prediksi extends Component
{
    public $step = 1;

    // Properties for Step 1
    public $selectedMerk = '';

    public $availableMotors = [];

    public $selectedMotorId = '';

    // Properties for Step 2 (Wizard & Fallback)
    public $listGejala = [];

    public $gejalaDipilih = [];

    public $currentNode = null;

    public $gejalaDijawabYa = [];

    public $gejalaDijawabTidak = [];

    public $isWizardMode = true;

    public $riwayatWawancara = [];

    public $path_history = [];

    // Properties for Step 3
    public $hasil = [];

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
            'selectedMotorId' => 'required',
        ], [
            'selectedMerk.required' => 'Merek pabrikan harus dipilih.',
            'selectedMotorId.required' => 'Tipe motor harus dipilih.',
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

        // Inisialisasi State Wizard
        $this->isWizardMode = true;
        $this->gejalaDijawabYa = [];
        $this->gejalaDijawabTidak = [];
        $this->riwayatWawancara = [];
        $this->path_history = [];
        $this->gejalaDipilih = [];

        $activeModel = C45Model::where('is_active', true)->first();
        if ($activeModel) {
            $this->currentNode = $activeModel->tree_data;
            $this->checkNodeStatus();
        } else {
            // Tidak ada model aktif, kembalikan ke Step 1 dan tampilkan pesan error
            $this->step = 1;
            session()->flash('error', 'Model C4.5 belum tersedia. Silakan hubungi Administrator untuk melakukan Generate Model terlebih dahulu.');
            return;
        }
    }

    public function prevStep()
    {
        $this->step = 1;
    }

    public function ulangiPilihGejala()
    {
        $this->nextStep(); // Re-initialize Step 2 with the same motor
    }

    public function ubahMotor()
    {
        $this->step = 1;
        // Optionally reset motor selection or keep it so user can just change it
    }

    public function prosesFallback()
    {
        $this->step = 1;
    }

    public function jawabPertanyaan($jawaban)
    {
        if (! $this->currentNode || $this->currentNode['type'] !== 'node') {
            return;
        }

        // Simpan state saat ini sebelum diubah
        $this->path_history[] = [
            'node' => $this->currentNode,
            'gejalaDipilih' => $this->gejalaDipilih,
            'gejalaDijawabYa' => $this->gejalaDijawabYa,
            'gejalaDijawabTidak' => $this->gejalaDijawabTidak,
            'riwayatWawancara' => $this->riwayatWawancara,
        ];

        $atribut = $this->currentNode['attribute'];

        // Simpan riwayat jika atribut adalah kode gejala (dimulai dengan G)
        if (str_starts_with($atribut, 'G')) {
            $gejala = Gejala::where('kode', $atribut)->first();
            if ($gejala) {
                $this->riwayatWawancara[] = [
                    'kode' => $atribut,
                    'nama' => $gejala->nama_gejala,
                    'jawaban' => $jawaban == 1 ? 'Ya' : 'Tidak',
                ];
            }

            if ($jawaban == 1) {
                $this->gejalaDijawabYa[] = $atribut;
                if (! in_array($atribut, $this->gejalaDipilih)) {
                    $this->gejalaDipilih[] = $atribut;
                }
            } else {
                $this->gejalaDijawabTidak[] = $atribut;
            }
        }

        // Pindah ke child node
        if (isset($this->currentNode['children'][$jawaban])) {
            $this->currentNode = $this->currentNode['children'][$jawaban];
            $this->checkNodeStatus();
        } else {
            // Cabang tidak ada -> Fallback
            $this->isWizardMode = false;
        }
    }

    public function kembaliPertanyaan()
    {
        if (count($this->path_history) > 0) {
            $lastState = array_pop($this->path_history);
            $this->currentNode = $lastState['node'];
            $this->gejalaDipilih = $lastState['gejalaDipilih'];
            $this->gejalaDijawabYa = $lastState['gejalaDijawabYa'];
            $this->gejalaDijawabTidak = $lastState['gejalaDijawabTidak'];
            $this->riwayatWawancara = $lastState['riwayatWawancara'];
            $this->isWizardMode = true;
            $this->hasil = [];
            $this->step = 2; // Pastikan step tetap di 2
        }
    }

    private function checkNodeStatus()
    {
        if (! $this->currentNode) {
            $this->isWizardMode = false;

            return;
        }

        if ($this->currentNode['type'] === 'leaf') {
            // Prediksi selesai
            $this->prosesPrediksi(true);
        } elseif ($this->currentNode['type'] === 'unknown') {
            // Buntu, fallback ke mode checkbox
            $this->isWizardMode = false;
        } elseif ($this->currentNode['type'] === 'node') {
            // Jika atribut adalah sistem_pembakaran, otomatis jawab berdasar data motor
            $attr = $this->currentNode['attribute'];
            if ($attr === 'sistem_pembakaran') {
                $motor = Motor::find($this->selectedMotorId);
                if ($motor && isset($this->currentNode['children'][$motor->sistem_pembakaran])) {
                    $this->jawabPertanyaan($motor->sistem_pembakaran);
                } else {
                    $this->isWizardMode = false;
                }
            }
        }
    }

    public function prosesPrediksi($fromWizard = false)
    {
        if ($fromWizard && empty($this->gejalaDipilih)) {
            $this->step = 3;
            $motorSehat = new Kerusakan([
                'kode' => 'K00',
                'nama_kerusakan' => 'Kondisi Motor Normal / Baik-baik Saja',
                'solusi' => 'Berdasarkan jawaban Anda, tidak ditemukan indikasi kerusakan yang signifikan pada motor. Terus lakukan perawatan berkala secara rutin seperti ganti oli dan periksa kondisi motor secara berkala.',
            ]);
            $motorSehat->id = 0; // ID virtual

            $this->hasil = [
                'kerusakan' => $motorSehat,
                'gejala_dipilih' => [],
                'top_3' => [
                    ['kerusakan' => $motorSehat, 'confidence' => 100.0],
                ],
            ];

            return;
        }

        if (! $fromWizard && empty($this->gejalaDipilih)) {
            session()->flash('error', 'Pilih minimal satu gejala kendaraan Anda!');

            return;
        }

        $activeModel = C45Model::where('is_active', true)->first();

        if (! $activeModel) {
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

        // 2. Kalkulasi Prediksi & Confidence langsung di C45Engine
        $engine = new \App\Services\C45Engine();
        
        if ($fromWizard && $this->currentNode && $this->currentNode['type'] === 'leaf') {
            // Jika dari wizard, kita inject leafNode yang sudah didapat agar Jaccard dan Confidence dihitung oleh Engine
            $top3 = $engine->predictFromLeaf($this->currentNode, $this->gejalaDipilih, $motor ? $motor->sistem_pembakaran : 'Umum');
        } else {
            $top3 = $engine->predict($testData, $this->gejalaDipilih, $motor ? $motor->sistem_pembakaran : 'Umum');
        }

        if (count($top3) > 0) {
            $this->hasil = [
                'kerusakan' => $top3[0]['kerusakan'], // Top 1 default
                'gejala_dipilih' => $this->gejalaDipilih,
                'top_3' => $top3,
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
                    'confidence' => $top3[0]['confidence'],
                ]);
            }

            $this->step = 3;
        } else {
            session()->flash('error', 'Sistem tidak dapat mengklasifikasikan kerusakan berdasarkan gejala tersebut.');
        }
    }

    // Logic traverseTree dan getAlternativePredictions dipindahkan ke C45Engine

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
