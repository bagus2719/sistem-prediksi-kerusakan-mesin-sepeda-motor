<div align="center">
  <br>
  <h1>🏍️ Sistem Pakar Prediksi Kerusakan Sepeda Motor</h1>
  <p><strong>Deteksi Dini Kerusakan Motor Kesayangan Anda Menggunakan Algoritma Decision Tree C4.5</strong></p>
  <br>
</div>

Sistem Pakar ini dirancang untuk mendiagnosa potensi kerusakan pada sepeda motor (khususnya untuk sistem Injeksi dan Karburator) berdasarkan gejala-gejala yang dialami oleh pengguna. Menggunakan **Algoritma Machine Learning C4.5 (Pohon Keputusan)**, sistem mampu memproses ratusan data historis kerusakan untuk memberikan tebakan yang akurat beserta solusi penanganannya.

---

## ✨ Fitur Unggulan

### 👨‍🔧 Halaman Pengguna (End-User)
- **Diagnosa Cepat & Akurat**: Pengguna cukup memilih gejala yang dialami motor, dan sistem akan langsung memberikan persentase tingkat kerusakan.
- **Top 3 Prediksi**: Menampilkan 3 kemungkinan kerusakan tertinggi, bukan hanya satu, untuk mencegah salah penanganan.
- **Solusi Cerdas**: Setiap kerusakan yang terdeteksi dilengkapi dengan saran perbaikan/solusi yang spesifik.
- **Riwayat Diagnosa**: Pengguna yang sudah *login* dapat melihat kembali histori pengecekan motor mereka sebelumnya.

### 🛡️ Halaman Admin
- **Dashboard Analitik**: Pantauan instan jumlah riwayat tes, model akurasi, dan data latih.
- **Manajemen Basis Pengetahuan**: 
  - Kelola Data Motor (Merek & Sistem Pembakaran).
  - Kelola Data Kerusakan (Kode, Nama, dan Solusi).
  - Kelola Data Gejala (Kode dan Keterangan).
- **Import Massal CSV**: Tidak perlu input satu per satu! Anda bisa langsung mengunggah file `.csv` berisi ratusan data *training* sekaligus. Algoritma otomatis memetakan relasinya.
- **Data Preprocessing Terintegrasi**: Fitur sekali klik untuk membuang baris data yang kosong (*missing values*) dan membuang duplikat (*data reduction*) agar mesin pembelajar tidak *overfit*.
- **Generate Model C4.5**: Sistem membangun Pohon Keputusan (*Decision Tree*) murni secara instan berdasarkan data latih, lengkap dengan visualisasi cabang pohon di antarmuka Admin.

---

## 🛠️ Teknologi yang Digunakan

Proyek ini dikembangkan menggunakan *stack* teknologi modern untuk menjamin kecepatan dan kemudahan *maintenance*:
- **Backend Framework**: [Laravel 11](https://laravel.com/) (PHP 8.2+)
- **Frontend Reactive**: [Livewire 3](https://livewire.laravel.com/) (Tidak perlu tulis JavaScript manual untuk reaktivitas!)
- **CSS Framework**: [Tailwind CSS 3](https://tailwindcss.com/)
- **Database**: MySQL / MariaDB

---

## 🚀 Panduan Instalasi (Local Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan *project* ini di komputer Anda menggunakan **Laragon**, **XAMPP**, atau sistem sejenis.

### Prasyarat:
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL / MariaDB

### Langkah Instalasi:

1. **Clone repositori ini:**
   ```bash
   git clone https://github.com/bagus2719/sistem-prediksi-kerusakan-mesin-sepeda-motor.git
   cd sistem-prediksi-kerusakan-mesin-sepeda-motor
   ```

2. **Install dependensi PHP via Composer:**
   ```bash
   composer install
   ```

3. **Install dependensi Node (Tailwind dkk) dan *build* aset CSS:**
   ```bash
   npm install
   npm run build
   ```

4. **Siapkan Konfigurasi Lingkungan (`.env`):**
   Salin file konfigurasi bawaan dan *generate* kunci aplikasi.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Atur Database di `.env`:**
   Buat database kosong di MySQL Anda (misalnya dengan nama `sistem_c45`), lalu sesuaikan baris ini di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistem_c45
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Migrasi Database:**
   Bangun struktur tabel ke dalam *database* yang telah Anda buat.
   ```bash
   php artisan migrate
   ```

7. **Jalankan Aplikasi:**
   Buka terminal baru dan jalankan server bawaan Laravel.
   ```bash
   php artisan serve
   ```
   Aplikasi kini bisa diakses melalui browser di: `http://localhost:8000`

---

## 🧠 Alur Penggunaan Algoritma (Untuk Admin)

Untuk membuat sistem pakar ini bisa "menebak" kerusakan, Admin harus memberinya data latih.
1. **Login sebagai Admin** (Buat akun secara manual melalui register jika belum ada, lalu set `role` menjadi `admin` di *database*).
2. Pergi ke menu **Master Data > Gejala**. Pastikan Anda sudah mendaftarkan kode-kode gejala utama (G01 - G14 dsb).
3. Pergi ke menu **Data Latih (Training)**. Unggah data CSV historis Anda. Sistem akan mengintegrasikannya otomatis.
4. Di halaman yang sama, klik **"Jalankan Preprocessing"** untuk membersihkan data dari duplikasi.
5. Pergi ke menu **Algoritma C4.5**, lalu klik **"Generate Model C4.5"**.
6. Selesai! Pohon Keputusan telah terbentuk dan *User* sudah bisa menggunakan halaman **Mulai Prediksi**.

---