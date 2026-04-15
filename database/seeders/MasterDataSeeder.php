<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gejala;
use App\Models\Kerusakan;

class MasterDataSeeder extends Seeder
{
    public function run()
    {
        $gejalas = [
            ['kode' => 'G01', 'nama_gejala' => 'Sulit Hidup Saat Mesin Dingin', 'keterangan' => '-'],
            ['kode' => 'G02', 'nama_gejala' => 'Mesin Mati Mendadak Saat Jalan', 'keterangan' => '-'],
            ['kode' => 'G03', 'nama_gejala' => 'Suara Kasar dari Mesin', 'keterangan' => '-'],
            ['kode' => 'G04', 'nama_gejala' => 'Asap Tebal dari Knalpot', 'keterangan' => '-'],
            ['kode' => 'G05', 'nama_gejala' => 'Oli Mesin Cepat Berkurang', 'keterangan' => '-'],
            ['kode' => 'G06', 'nama_gejala' => 'Mesin Cepat Panas (Overheat)', 'keterangan' => '-'],
            ['kode' => 'G07', 'nama_gejala' => 'Tarikan Lemah Saat Akselerasi', 'keterangan' => '-'],
            ['kode' => 'G08', 'nama_gejala' => 'Getaran Mesin Berlebih', 'keterangan' => '-'],
            ['kode' => 'G09', 'nama_gejala' => 'Lampu Check Engine Menyala', 'keterangan' => '-'],
            ['kode' => 'G10', 'nama_gejala' => 'Konsumsi BBM Boros', 'keterangan' => '-'],
            ['kode' => 'G11', 'nama_gejala' => 'Mesin Brebet Saat Digas', 'keterangan' => '-'],
            ['kode' => 'G12', 'nama_gejala' => 'Mesin Nyala Tapi Tidak Bergerak', 'keterangan' => '-'],
            ['kode' => 'G13', 'nama_gejala' => 'Suara Kasar pada CVT', 'keterangan' => '-'],
            ['kode' => 'G14', 'nama_gejala' => 'Air Radiator Berkurang', 'keterangan' => '-'],
        ];

        foreach ($gejalas as $item) {
            Gejala::updateOrCreate(['kode' => $item['kode']], $item);
        }

        $kerusakans = [
            ['kode' => 'K01', 'nama_kerusakan' => 'Busi Bermasalah', 'solusi' => '-'],
            ['kode' => 'K02', 'nama_kerusakan' => 'V-Belt (Fanbelt) Putus', 'solusi' => '-'],
            ['kode' => 'K03', 'nama_kerusakan' => 'Overheat (Mesin Terlalu Panas)', 'solusi' => '-'],
            ['kode' => 'K04', 'nama_kerusakan' => 'Karburator Kotor', 'solusi' => '-'],
            ['kode' => 'K05', 'nama_kerusakan' => 'Fuel Pump Lemah atau Tidak Berfungsi', 'solusi' => '-'],
            ['kode' => 'K06', 'nama_kerusakan' => 'Injektor Kotor', 'solusi' => '-'],
            ['kode' => 'K07', 'nama_kerusakan' => 'Sensor CKP Tidak Berfungsi', 'solusi' => '-'],
            ['kode' => 'K08', 'nama_kerusakan' => 'Sensor TPS Tidak Optimal', 'solusi' => '-'],
            ['kode' => 'K09', 'nama_kerusakan' => 'Kerusakan Roller / Pulley / Kampas CVT', 'solusi' => '-'],
            ['kode' => 'K10', 'nama_kerusakan' => 'Piston Aus', 'solusi' => '-'],
            ['kode' => 'K11', 'nama_kerusakan' => 'Oli Mesin Habis atau Berkurang', 'solusi' => '-'],
            ['kode' => 'K12', 'nama_kerusakan' => 'Kerusakan Sistem Injeksi', 'solusi' => '-'],
            ['kode' => 'K13', 'nama_kerusakan' => 'Katup (Valve) Bocor', 'solusi' => '-'],
            ['kode' => 'K14', 'nama_kerusakan' => 'Kerusakan Water Pump atau Paking Head', 'solusi' => '-'],
            ['kode' => 'K15', 'nama_kerusakan' => 'Bearing Noken As Aus', 'solusi' => '-'],
            ['kode' => 'K16', 'nama_kerusakan' => 'Tensioner Rantai Timing Bermasalah', 'solusi' => '-'],
            ['kode' => 'K17', 'nama_kerusakan' => 'Kabel ACG Starter Terbakar', 'solusi' => '-'],
        ];

        foreach ($kerusakans as $item) {
            Kerusakan::updateOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
