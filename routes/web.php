<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// TAMBAHKAN DI SINI
Route::get('/login', function () {
    return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/login');
})->name('login');

require __DIR__ . '/auth.php';
