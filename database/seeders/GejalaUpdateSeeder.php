<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gejala;

class GejalaUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gejalas = [
            // Gejala Umum Mesin
            ['kode' => 'G01', 'nama_gejala' => 'Motor sulit dihidupkan (starter & kick)'],
            ['kode' => 'G02', 'nama_gejala' => 'Mesin mati mendadak saat berjalan'],
            ['kode' => 'G03', 'nama_gejala' => 'Suara kasar tek-tek dari area head mesin'],
            ['kode' => 'G04', 'nama_gejala' => 'Asap putih tebal dari knalpot'],
            ['kode' => 'G05', 'nama_gejala' => 'Tenaga mesin terasa lemas / ngempos'],
            ['kode' => 'G06', 'nama_gejala' => 'Mesin cepat panas / indikator suhu menyala'],
            ['kode' => 'G07', 'nama_gejala' => 'Tarikan awal berat dan bergetar (gredek)'],
            ['kode' => 'G08', 'nama_gejala' => 'Suara krecek dari area timing chain'],
            ['kode' => 'G09', 'nama_gejala' => 'Lampu MIL / Check Engine menyala'],
            ['kode' => 'G10', 'nama_gejala' => 'Konsumsi BBM sangat boros'],
            ['kode' => 'G11', 'nama_gejala' => 'Mesin mbrebet / tersendat saat digas'],
            // Gejala CVT
            ['kode' => 'G12', 'nama_gejala' => 'Suara berdecit dari area CVT'],
            ['kode' => 'G13', 'nama_gejala' => 'Suara kasar klotok dari area CVT'],
            // Gejala Pendinginan & Pelumasan
            ['kode' => 'G14', 'nama_gejala' => 'Cairan radiator cepat habis'],
            ['kode' => 'G15', 'nama_gejala' => 'Oli mesin cepat berkurang tanpa rembesan'],
            ['kode' => 'G16', 'nama_gejala' => 'Kick starter terasa ringan (tidak ada kompresi)'],
            ['kode' => 'G17', 'nama_gejala' => 'Mesin mati setelah dipakai lama (panas)'],
            ['kode' => 'G18', 'nama_gejala' => 'Kick starter macet tidak bisa diinjak'],
            ['kode' => 'G19', 'nama_gejala' => 'Putaran gas tidak kembali / nyangkut'],
            ['kode' => 'G20', 'nama_gejala' => 'Bau sangit / terbakar dari area mesin'],
            ['kode' => 'G21', 'nama_gejala' => 'Rembesan oli dari bodi mesin'],
            ['kode' => 'G22', 'nama_gejala' => 'Lampu indikator oli menyala'],
            ['kode' => 'G23', 'nama_gejala' => 'Motor hidup tapi tidak bergerak maju'],
            ['kode' => 'G24', 'nama_gejala' => 'RPM langsam naik turun sendiri (hunting)'],
            ['kode' => 'G25', 'nama_gejala' => 'Getaran berlebih pada mesin saat idle'],
            // Gejala ECU/CDI
            ['kode' => 'G26', 'nama_gejala' => 'Tidak ada percikan api di busi sama sekali'],
        ];

        // Menyimpan / mengupdate gejala baru
        foreach ($gejalas as $item) {
            Gejala::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama_gejala' => $item['nama_gejala'],
                    'sistem_pembakaran' => 'Keduanya', // Default
                    'keterangan' => '-'
                ]
            );
        }

        // Hapus kode yang sudah tidak ada di daftar terbaru (misal G34)
        $kodesToKeep = array_column($gejalas, 'kode');
        Gejala::whereNotIn('kode', $kodesToKeep)->delete();
    }
}
