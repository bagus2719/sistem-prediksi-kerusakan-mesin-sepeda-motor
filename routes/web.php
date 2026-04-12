<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\User\Dashboard as UserDashboard;
use App\Livewire\User\Prediksi;
use App\Livewire\Admin\Dashboard as AdminDashboard;

//Public Route
Route::get('/', UserDashboard::class);
Route::get('/dashboard', UserDashboard::class)->name('dashboard');

//User Route (LOGIN
Route::middleware(['auth'])->group(function () {
    Route::get('/prediksi', Prediksi::class)->name('prediksi');
});

//Admin Route
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
});

//Auth Route
require __DIR__ . '/auth.php';