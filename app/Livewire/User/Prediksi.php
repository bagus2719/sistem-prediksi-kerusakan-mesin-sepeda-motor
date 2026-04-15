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
    public $sistem_pembakaran = '';

    // Properties for Step 2
    public $listGejala = [];
    public $gejalaDipilih = [];

    // Properties for Step 3
    public $hasil = [];

    // TUNTUTAN USER: Logika Pakar Manual Sementara (sebelum Mesin C4.5 selesai)
    private $rules = [
        'G01' => ['K01', 'K04', 'K05', 'K06', 'K07', 'K10', 'K12', 'K13', 'K17'],
        'G02' => ['K01', 'K03', 'K05', 'K07', 'K11', 'K12', 'K13', 'K17'],
        'G03' => ['K09', 'K10', 'K11', 'K15', 'K16'],
        'G04' => ['K10', 'K11'],
        'G05' => ['K10', 'K11'],
        'G06' => ['K03', 'K11', 'K14'],
        'G07' => ['K04', 'K05', 'K06', 'K09'],
        'G08' => ['K09'],
        'G09' => ['K05', 'K07', 'K08', 'K12', 'K17'],
        'G10' => ['K04', 'K12'],
        'G11' => ['K01', 'K04', 'K05', 'K06', 'K08', 'K12'],
        'G12' => ['K02'],
        'G13' => ['K02', 'K09'],
        'G14' => ['K03', 'K14']
    ];

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
            'sistem_pembakaran' => 'required'
        ], [
            'selectedMerk.required' => 'Merek pabrikan harus dipilih.',
            'selectedMotorId.required' => 'Tipe motor harus dipilih.',
            'sistem_pembakaran.required' => 'Sistem pembakaran harus ditentukan.'
        ]);

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

        $skor = [];

        // Manual Mapping Forward Chaining
        foreach ($this->gejalaDipilih as $kodeGejala) {
            if (isset($this->rules[$kodeGejala])) {
                foreach ($this->rules[$kodeGejala] as $kodeKerusakan) {
                    if (!isset($skor[$kodeKerusakan])) {
                        $skor[$kodeKerusakan] = 0;
                    }
                    $skor[$kodeKerusakan]++;
                }
            }
        }

        if (empty($skor)) {
            session()->flash('error', 'Tidak ditemukan potensi kerusakan spesifik dari gejala tersebut.');
            return;
        }

        arsort($skor);
        
        // Convert to array of detailed results mapping to percentage
        $totalGejala = count($this->gejalaDipilih);
        $hasilDenganPersen = [];
        foreach($skor as $kodeK => $nilai) {
            // Persentase Berdasarkan jumlah gejala dipilih. 
            // e.g. user pick 3 gejala (G01, G02, G09), 3 gejala ini merujuk ke K12, K12 punya skor 3.
            // 3/3 = 100%. The damage matched all the user reported symptoms.
            $persentase = ($nilai / $totalGejala) * 100;
            if($persentase > 100) $persentase = 100; // Safeguard
            
            $hasilDenganPersen[$kodeK] = [
                'skor' => $nilai,
                'persentase' => $persentase
            ];
        }

        // Ambil Kerusakan Teratas (Dominan)
        $topKodeKerusakan = array_key_first($skor);
        $topKerusakanDb = Kerusakan::where('kode', $topKodeKerusakan)->first();
        $topConfidence = $hasilDenganPersen[$topKodeKerusakan]['persentase'];

        if ($topKerusakanDb) {
            // Take Top 3
            $hasilArrayTiga = array_slice($hasilDenganPersen, 0, 3, true);

            $this->hasil = [
                'kerusakan' => $topKerusakanDb,
                'gejala_dipilih' => $this->gejalaDipilih,
                'top_3' => $hasilArrayTiga
            ];

            // Simpan Histori ke Riwayats
            if (Auth::check()) {
                Riwayat::create([
                    'user_id' => Auth::id(),
                    'kerusakan_id' => $topKerusakanDb->id,
                    'gejala_dipilih' => $this->gejalaDipilih,
                    'motor_id' => $this->selectedMotorId,
                    'sistem_pembakaran' => $this->sistem_pembakaran,
                    'confidence' => $topConfidence
                ]);
            }

            $this->step = 3; // Menuju Hasil
        }
    }

    public function ulangi()
    {
        $this->reset(['step', 'selectedMerk', 'selectedMotorId', 'sistem_pembakaran', 'gejalaDipilih', 'hasil', 'availableMotors']);
    }

    public function render()
    {
        return view('livewire.user.prediksi')
            ->layout('livewire.user.layouts.app');
    }
}
