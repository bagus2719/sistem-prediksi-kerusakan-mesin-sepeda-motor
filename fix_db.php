<?php

use App\Models\Kerusakan;
use App\Models\Training;
use App\Models\Riwayat;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// 1. Mapping duplikat (K18-K28) ke K01-K17
$mapping = [
    'K18' => 'K07',
    'K19' => 'K02',
    'K20' => 'K11',
    'K21' => 'K13',
    'K22' => 'K16',
    'K23' => 'K09',
    'K24' => 'K05',
    'K25' => 'K03',
    'K26' => 'K08',
    'K27' => 'K17',
    'K28' => 'K14'
];

foreach ($mapping as $dupKode => $targetKode) {
    $dupKerusakan = Kerusakan::where('kode', $dupKode)->first();
    $targetKerusakan = Kerusakan::where('kode', $targetKode)->first();

    if ($dupKerusakan && $targetKerusakan) {
        // Update Trainings
        Training::where('kerusakan_id', $dupKerusakan->id)->update(['kerusakan_id' => $targetKerusakan->id]);
        
        // Update Riwayats
        Riwayat::where('kerusakan_id', $dupKerusakan->id)->update(['kerusakan_id' => $targetKerusakan->id]);

        // Delete duplicate
        $dupKerusakan->delete();
    }
}

// 2. Update Solusi untuk K01 - K17
$solusiMap = [
    'K01' => "Ganti busi dengan yang baru, pastikan celah busi sesuai standar pabrik.",
    'K02' => "Ganti V-Belt, bersihkan ruang CVT dari debu dan kotoran.",
    'K03' => "Periksa sistem pendingin (coolant), pastikan kipas radiator berputar. Istirahatkan mesin sejenak.",
    'K04' => "Bongkar dan bersihkan karburator menggunakan karburator cleaner, setting ulang spuyer.",
    'K05' => "Periksa tekanan fuel pump, ganti rotak/fuel pump assembly jika tekanan di bawah standar.",
    'K06' => "Lakukan pembersihan injektor (injector cleaning) atau ganti injektor jika semprotan tidak mengabut.",
    'K07' => "Periksa soket CKP dari karat/kabel putus. Jika sensor rusak, ganti dengan yang baru.",
    'K08' => "Lakukan reset TPS (Throttle Position Sensor) menggunakan scanner, atau ganti jika pembacaan sudah melompat-lompat.",
    'K09' => "Ganti set roller, periksa kondisi kampas ganda dan mangkok CVT. Ganti jika aus.",
    'K10' => "Lakukan over size (korter) blok silinder dan ganti piston set baru.",
    'K11' => "Segera tambahkan atau ganti oli mesin. Periksa apakah ada kebocoran pada seal atau ring piston.",
    'K12' => "Periksa jalur kabel kelistrikan sistem injeksi dan pastikan ECU/ECM merespon dengan baik.",
    'K13' => "Lakukan sekir klep (katup) ulang atau ganti payung klep jika sudah aus.",
    'K14' => "Ganti seal water pump atau paking head (gasket) jika air radiator bercampur dengan oli mesin.",
    'K15' => "Ganti bearing noken as dengan yang baru. Pastikan sirkulasi oli ke head silinder lancar.",
    'K16' => "Ganti tonjokan tensioner dan rantai keteng (timing chain) agar suara mesin kembali halus.",
    'K17' => "Periksa dan ganti kabel ACG starter yang terbakar, hindari penggunaan beban kelistrikan berlebih."
];

foreach ($solusiMap as $kode => $solusi) {
    Kerusakan::where('kode', $kode)->update(['solusi' => $solusi]);
}

echo "Database diperbarui: Duplikat dihapus & solusi diisi.\n";
