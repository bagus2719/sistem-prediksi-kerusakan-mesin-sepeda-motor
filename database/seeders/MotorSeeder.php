<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Motor;

class MotorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $honda_models = ['Vario 125', 'Vario 150', 'Vario 160', 'Beat Karbu', 'Beat FI', 'Scoopy', 'PCX', 'ADV'];
        foreach ($honda_models as $nama) {
            Motor::create(['merk' => 'HONDA', 'nama_motor' => $nama]);
        }

        $yamaha_models = ['Mio Karbu', 'Mio M3', 'NMAX', 'Aerox', 'Lexi', 'Fino'];
        foreach ($yamaha_models as $nama) {
            Motor::create(['merk' => 'YAMAHA', 'nama_motor' => $nama]);
        }
    }
}
