<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('dataset', function () {
    return view('dataset');
})->middleware(['auth']);

Route::get('/rules', function () {
    return view('rules');
})->middleware(['auth']);

require __DIR__ . '/auth.php';
