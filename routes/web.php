<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\User\Dashboard;
use App\Livewire\User\Prediksi;
use App\Livewire\User\Riwayat;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Gejala\Index as GejalaIndex;
use App\Livewire\Admin\Kerusakan\Index as KerusakanIndex;
use App\Livewire\Admin\Training\Index as TrainingIndex;
use App\Livewire\Admin\Riwayat\Index as RiwayatAdmin;

Route::get('/', Dashboard::class)->name('home');

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/prediksi', Prediksi::class)->name('prediksi');
    Route::get('/riwayat', Riwayat::class)->name('riwayat');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');

    // Gejala CRUD
    Route::get('/admin/gejala', GejalaIndex::class)->name('admin.gejala');
    Route::get('/admin/gejala/create', \App\Livewire\Admin\Gejala\Create::class)->name('admin.gejala.create');
    Route::get('/admin/gejala/{id}/edit', \App\Livewire\Admin\Gejala\Edit::class)->name('admin.gejala.edit');

    // Kerusakan CRUD
    Route::get('/admin/kerusakan', KerusakanIndex::class)->name('admin.kerusakan');
    Route::get('/admin/kerusakan/create', \App\Livewire\Admin\Kerusakan\Create::class)->name('admin.kerusakan.create');
    Route::get('/admin/kerusakan/{id}/edit', \App\Livewire\Admin\Kerusakan\Edit::class)->name('admin.kerusakan.edit');

    // Training CRUD
    Route::get('/admin/training', TrainingIndex::class)->name('admin.training');
    Route::get('/admin/training/create', \App\Livewire\Admin\Training\Create::class)->name('admin.training.create');
    Route::get('/admin/training/{id}/edit', \App\Livewire\Admin\Training\Edit::class)->name('admin.training.edit');

    // Riwayat
    Route::get('/admin/riwayat', RiwayatAdmin::class)->name('admin.riwayat');
});

require __DIR__.'/auth.php';
