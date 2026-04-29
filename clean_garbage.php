<?php

use App\Models\Kerusakan;
use App\Models\Training;
use App\Models\Riwayat;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Ambil semua kerusakan ID > 17
$garbageKerusakans = Kerusakan::where('id', '>', 17)->get();
$count = 0;
$trainingCount = 0;

foreach ($garbageKerusakans as $kerusakan) {
    // Hapus trainings terkait
    $deletedTrainings = Training::where('kerusakan_id', $kerusakan->id)->delete();
    $trainingCount += $deletedTrainings;
    
    // Hapus riwayats terkait
    Riwayat::where('kerusakan_id', $kerusakan->id)->delete();
    
    // Hapus kerusakan
    $kerusakan->delete();
    $count++;
}

echo "Berhasil menghapus {$count} data Kerusakan sampah dan {$trainingCount} baris data Training terkait.\n";
