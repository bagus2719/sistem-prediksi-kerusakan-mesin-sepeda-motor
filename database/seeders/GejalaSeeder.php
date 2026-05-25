<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gejala;

class GejalaSeeder extends Seeder
{
    /**
     * Seed data gejala untuk C4.5 Enhanced Diagnostic Engine.
     * Dijalankan otomatis saat migrate:fresh --seed.
     */
    public function run(): void
    {
        $gejalas = [
            // ═══ GEJALA UMUM MESIN ═══
            ['kode' => 'G01', 'nama_gejala' => 'Masalah menghidupkan mesin (Starter/Kick ngadat)'],
            ['kode' => 'G02', 'nama_gejala' => 'Mesin sering mati mendadak (Mogok)'],
            ['kode' => 'G03', 'nama_gejala' => 'Terdengar bunyi kasar di head mesin (Tek-tek/Klotok)'],
            ['kode' => 'G04', 'nama_gejala' => 'Keluar asap putih tebal dari knalpot'],
            ['kode' => 'G05', 'nama_gejala' => 'Tenaga motor terasa ngempos / loyo'],
            ['kode' => 'G06', 'nama_gejala' => 'Mesin terasa sangat panas (Overheat)'],
            ['kode' => 'G07', 'nama_gejala' => 'Tarikan awal terasa bergetar parah (Gredek)'],
            ['kode' => 'G08', 'nama_gejala' => 'Suara krecek dari area timing chain'],
            ['kode' => 'G09', 'nama_gejala' => 'Lampu MIL / Check Engine menyala'],
            ['kode' => 'G10', 'nama_gejala' => 'BBM terasa sangat boros'],
            ['kode' => 'G11', 'nama_gejala' => 'Tarikan mesin mbrebet / tersendat'],

            // ═══ GEJALA CVT ═══
            ['kode' => 'G12', 'nama_gejala' => 'Terdengar bunyi berdecit/cicit di CVT'],
            ['kode' => 'G13', 'nama_gejala' => 'Terdengar bunyi kasar/klotok di area CVT'],

            // ═══ GEJALA PENDINGINAN & PELUMASAN ═══
            ['kode' => 'G14', 'nama_gejala' => 'Air radiator sangat cepat habis'],
            ['kode' => 'G15', 'nama_gejala' => 'Oli mesin cepat habis / susut drastis'],
            ['kode' => 'G16', 'nama_gejala' => 'Kompresi mesin terasa lemah (tes kompresi rendah)'],
            ['kode' => 'G17', 'nama_gejala' => 'Mesin mati setelah dipakai lama (panas)'],
            ['kode' => 'G18', 'nama_gejala' => 'Kick starter macet tidak bisa diinjak'],
            ['kode' => 'G19', 'nama_gejala' => 'Putaran gas tidak kembali / nyangkut'],
            ['kode' => 'G20', 'nama_gejala' => 'Tercium bau sangit / hangus dari mesin'],
            ['kode' => 'G21', 'nama_gejala' => 'Terlihat rembesan / tetesan oli di mesin'],
            ['kode' => 'G22', 'nama_gejala' => 'Lampu indikator oli menyala'],
            ['kode' => 'G23', 'nama_gejala' => 'Motor hidup tapi tidak jalan'],
            ['kode' => 'G24', 'nama_gejala' => 'RPM langsam naik turun sendiri (hunting)'],
            ['kode' => 'G25', 'nama_gejala' => 'Getaran berlebih pada mesin saat idle'],
            ['kode' => 'G26', 'nama_gejala' => 'Sistem pengapian mati (Busi tidak memercikkan api)'],

            // ═══ GEJALA DETAIL CVT ═══
            ['kode' => 'G27', 'nama_gejala' => 'Slip: RPM naik tapi kecepatan tidak bertambah'],
            ['kode' => 'G28', 'nama_gejala' => 'Suara dengung konstan dari puli CVT'],
            ['kode' => 'G29', 'nama_gejala' => 'Getaran di area gardan belakang saat jalan'],

            // ═══ GEJALA DETAIL RADIATOR ═══
            ['kode' => 'G30', 'nama_gejala' => 'Warna oli berubah susu (tercampur air)'],
            ['kode' => 'G31', 'nama_gejala' => 'Tetesan air di bawah radiator / mesin'],
            ['kode' => 'G32', 'nama_gejala' => 'Kipas radiator tidak berputar saat mesin panas'],
            ['kode' => 'G33', 'nama_gejala' => 'Tekanan berlebih saat buka tutup radiator'],

            // ═══ GEJALA DETAIL OLI ═══
            ['kode' => 'G34', 'nama_gejala' => 'Oli menetes dari baut tap bawah mesin'],
            ['kode' => 'G35', 'nama_gejala' => 'Rembesan oli di sambungan blok/head mesin'],
            ['kode' => 'G36', 'nama_gejala' => 'Asap kebiruan dari knalpot saat gas ditarik'],

            // ═══ GEJALA DETAIL MEKANIKAL ═══
            ['kode' => 'G37', 'nama_gejala' => 'Suara ngelitik saat mesin dingin lalu hilang'],

            // ═══ GEJALA DETAIL BAHAN BAKAR ═══
            ['kode' => 'G38', 'nama_gejala' => 'Motor brebet saat tangki hampir kosong'],
            ['kode' => 'G39', 'nama_gejala' => 'Bau bensin menyengat dari area mesin'],
            ['kode' => 'G40', 'nama_gejala' => 'Tidak ada suara fuel pump saat kontak ON'],

            // ═══ GEJALA DETAIL PENGAPIAN ═══
            ['kode' => 'G41', 'nama_gejala' => 'Percikan api busi lemah / berwarna orange'],
            ['kode' => 'G42', 'nama_gejala' => 'Motor mati saat panas, hidup lagi setelah dingin'],
            ['kode' => 'G43', 'nama_gejala' => 'Motor mati saat hujan / area busi basah'],
            
            // ═══ GEJALA DETAIL TAMBAHAN (G44-G60) ═══
            ['kode' => 'G44', 'nama_gejala' => 'Asap putih muncul terus menerus saat mesin panas'],
            ['kode' => 'G45', 'nama_gejala' => 'Suara tek-tek makin keras saat RPM naik'],
            ['kode' => 'G46', 'nama_gejala' => 'Mesin mbrebet di RPM rendah (saat mau jalan)'],
            ['kode' => 'G47', 'nama_gejala' => 'Mesin mbrebet di RPM tinggi (saat ngebut)'],
            ['kode' => 'G48', 'nama_gejala' => 'Tarikan gas terasa berat (respons lambat)'],
            ['kode' => 'G49', 'nama_gejala' => 'Knalpot sering nembak (backfire) saat gas dilepas'],
            ['kode' => 'G50', 'nama_gejala' => 'Lampu depan sering putus atau redup saat digas'],
            ['kode' => 'G51', 'nama_gejala' => 'Motor sulit distarter pagi hari (saat mesin dingin)'],
            ['kode' => 'G52', 'nama_gejala' => 'RPM tinggi tidak mau turun ke langsam'],
            ['kode' => 'G53', 'nama_gejala' => 'Terdengar suara ngorok dari area filter udara'],
            ['kode' => 'G54', 'nama_gejala' => 'Aki sering tekor meskipun sudah diganti baru'],
            ['kode' => 'G55', 'nama_gejala' => 'Handle gas terasa kaku / berat saat diputar'],
            ['kode' => 'G56', 'nama_gejala' => 'Bunyi jeduk saat melewati jalan berlubang (area mesin)'],
            ['kode' => 'G57', 'nama_gejala' => 'Tarikan motor tertahan di kecepatan tertentu (mentok)'],
            ['kode' => 'G58', 'nama_gejala' => 'Bau coolant / cairan radiator menyengat di sekitar motor'],
            ['kode' => 'G59', 'nama_gejala' => 'Terdapat serpihan logam/bram pada saat tap oli mesin'],
            ['kode' => 'G60', 'nama_gejala' => 'Suara mendesing tajam dari CVT saat deselerasi (turun gas)'],
        ];

        $rootSymptoms = [
            'G01', 'G02', 'G03', 'G04', 'G05', 'G06', 'G07', 
            'G10', 'G11', 'G12', 'G13', 'G14', 'G15', 'G20', 
            'G21', 'G26'
        ];

        // Peta branch untuk gejala detail (mengarah ke kode ROOT induknya)
        // Beberapa gejala bisa memiliki lebih dari 1 induk root jika relevan
        $branchMapping = [
            // Head Mesin (G03)
            'G08' => ['G03'],              // Suara krecek timing chain → bunyi kasar head
            'G25' => ['G03', 'G07'],       // Getaran berlebih idle → bunyi kasar head + gredek CVT
            'G37' => ['G03'],              // Suara ngelitik dingin → bunyi kasar head
            'G45' => ['G03'],              // Tek-tek makin keras RPM naik → bunyi kasar head
            'G56' => ['G03'],              // Bunyi jeduk area mesin → bunyi kasar head
            'G59' => ['G03', 'G15'],       // Serpihan logam di tap oli → bunyi kasar head + oli habis

            // Asap/Kompresi (G04)
            'G16' => ['G04', 'G05'],       // Kick ringan (no kompresi) → asap putih + tenaga loyo
            'G18' => ['G04'],              // Kick macet → asap/kompresi
            'G36' => ['G04', 'G15'],       // Asap kebiruan → asap putih + oli habis
            'G44' => ['G04', 'G06'],       // Asap putih terus saat panas → asap putih + overheat

            // Tenaga Lemas (G05)
            'G48' => ['G05', 'G11'],       // Tarikan gas berat → tenaga loyo + mbrebet
            'G53' => ['G05'],              // Suara ngorok filter udara → tenaga loyo
            'G55' => ['G05', 'G11'],       // Handle gas kaku → tenaga loyo + mbrebet
            'G57' => ['G05', 'G07'],       // Tarikan mentok di kecepatan tertentu → tenaga loyo + gredek CVT

            // Panas/Radiator (G06)
            'G30' => ['G06'],              // Oli berubah susu → overheat
            'G31' => ['G06'],              // Tetesan air radiator → overheat
            'G32' => ['G06'],              // Kipas radiator mati → overheat
            'G33' => ['G06'],              // Tekanan berlebih tutup radiator → overheat
            'G58' => ['G06'],              // Bau coolant → overheat
            'G14' => ['G06'],              // Air radiator cepat habis → overheat

            // Tarikan Gredek / CVT (G07)
            'G27' => ['G07'],              // Slip RPM naik tapi gak jalan → gredek CVT
            'G28' => ['G07'],              // Suara dengung puli → gredek CVT
            'G29' => ['G07', 'G12'],       // Getaran gardan → gredek CVT + berdecit CVT
            'G13' => ['G07'],              // Bunyi kasar CVT → gredek CVT

            // BBM Boros (G10)
            'G39' => ['G10'],              // Bau bensin → BBM boros
            'G46' => ['G10', 'G11'],       // Mbrebet RPM rendah → BBM boros + mbrebet
            'G47' => ['G10', 'G11'],       // Mbrebet RPM tinggi → BBM boros + mbrebet

            // Mbrebet (G11)
            'G09' => ['G11'],              // Lampu MIL → mbrebet
            'G19' => ['G11'],              // Gas tidak kembali → mbrebet
            'G24' => ['G11'],              // RPM hunting → mbrebet
            'G49' => ['G11', 'G10'],       // Knalpot backfire → mbrebet + BBM boros
            'G52' => ['G11'],              // RPM tinggi gak turun → mbrebet

            // Berdecit CVT (G12)
            'G23' => ['G12'],              // Motor hidup tapi gak jalan → berdecit CVT
            'G60' => ['G12'],              // Suara mendesing CVT → berdecit CVT

            // Oli Habis (G15)
            'G22' => ['G15'],              // Indikator oli → oli habis
            'G34' => ['G15'],              // Oli menetes dari baut tap → oli habis
            'G35' => ['G15'],              // Rembesan oli head → oli habis
            'G21' => ['G15'],              // Rembesan/tetesan oli → oli habis

            // Tidak Ada Api / Pengapian (G26)
            'G41' => ['G26', 'G01'],       // Api busi lemah → pengapian + sulit starter
            'G43' => ['G26'],              // Motor mati saat hujan → pengapian
            'G50' => ['G26', 'G01'],       // Lampu putus/redup → pengapian + sulit starter
            'G54' => ['G26'],              // Aki tekor → pengapian

            // Sulit Dihidupkan (G01)
            'G38' => ['G01'],              // Brebet tangki kosong → sulit starter
            'G40' => ['G01'],              // Tidak ada suara fuel pump → sulit starter
            'G51' => ['G01', 'G26'],       // Sulit starter pagi → sulit starter + pengapian

            // Mati Mendadak (G02)
            'G17' => ['G02', 'G06'],       // Mesin mati setelah lama panas → mati mendadak + overheat
            'G42' => ['G02'],              // Motor mati panas, hidup setelah dingin → mati mendadak
        ];

        // Menyimpan / mengupdate gejala
        foreach ($gejalas as $item) {
            $sistemPembakaran = 'Keduanya';
            if (in_array($item['kode'], ['G09', 'G40'])) {
                $sistemPembakaran = 'Injeksi';
            }

            $isRoot = in_array($item['kode'], $rootSymptoms);
            $branch = $isRoot ? null : ($branchMapping[$item['kode']] ?? null);

            Gejala::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama_gejala' => $item['nama_gejala'],
                    'sistem_pembakaran' => $sistemPembakaran,
                    'is_root' => $isRoot,
                    'branch' => $branch,
                ]
            );
        }

        // Hapus kode yang sudah tidak ada di daftar terbaru
        $kodesToKeep = array_column($gejalas, 'kode');
        Gejala::whereNotIn('kode', $kodesToKeep)->delete();
    }
}
