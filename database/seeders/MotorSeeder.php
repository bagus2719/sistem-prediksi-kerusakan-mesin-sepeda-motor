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
        // ── HONDA INJEKSI ──
        $honda_injeksi = [
            'Beat FI', 'Beat Street', 'Vario 125', 'Vario 150',
            'Scoopy FI', 'Genio', 'PCX 160', 'ADV 160',
        ];
        foreach ($honda_injeksi as $nama) {
            Motor::updateOrCreate(
                ['nama_motor' => $nama],
                ['merk' => 'HONDA', 'sistem_pembakaran' => 'Injeksi']
            );
        }

        // ── HONDA KARBURATOR ──
        $honda_karbu = ['Beat Karbu', 'Vario 110 Karbu', 'Scoopy Karbu'];
        foreach ($honda_karbu as $nama) {
            Motor::updateOrCreate(
                ['nama_motor' => $nama],
                ['merk' => 'HONDA', 'sistem_pembakaran' => 'Karburator']
            );
        }

        // ── YAMAHA INJEKSI ──
        $yamaha_injeksi = [
            'Mio M3', 'Mio S', 'Soul GT FI', 'NMAX 155',
            'Aerox 155', 'Lexi', 'Freego', 'Fino FI',
        ];
        foreach ($yamaha_injeksi as $nama) {
            Motor::updateOrCreate(
                ['nama_motor' => $nama],
                ['merk' => 'YAMAHA', 'sistem_pembakaran' => 'Injeksi']
            );
        }

        // ── YAMAHA KARBURATOR ──
        $yamaha_karbu = ['Mio Sporty', 'Mio Soul Karbu', 'Fino Karbu', 'Xeon Karbu'];
        foreach ($yamaha_karbu as $nama) {
            Motor::updateOrCreate(
                ['nama_motor' => $nama],
                ['merk' => 'YAMAHA', 'sistem_pembakaran' => 'Karburator']
            );
        }
    }
}
