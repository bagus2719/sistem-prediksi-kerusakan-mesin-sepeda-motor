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
        $honda_models = [
            'Vario 125' => 'Injeksi', 
            'Vario 150' => 'Injeksi', 
            'Vario 160' => 'Injeksi', 
            'Beat Karbu' => 'Karburator', 
            'Beat FI' => 'Injeksi', 
            'Scoopy' => 'Injeksi', 
            'PCX' => 'Injeksi', 
            'ADV' => 'Injeksi'
        ];
        
        foreach ($honda_models as $nama => $sistem) {
            Motor::create(['merk' => 'HONDA', 'nama_motor' => $nama, 'sistem_pembakaran' => $sistem]);
        }

        $yamaha_models = [
            'Mio Karbu' => 'Karburator', 
            'Mio M3' => 'Injeksi', 
            'NMAX' => 'Injeksi', 
            'Aerox' => 'Injeksi', 
            'Lexi' => 'Injeksi', 
            'Fino' => 'Injeksi'
        ];
        
        foreach ($yamaha_models as $nama => $sistem) {
            Motor::create(['merk' => 'YAMAHA', 'nama_motor' => $nama, 'sistem_pembakaran' => $sistem]);
        }
    }
}
