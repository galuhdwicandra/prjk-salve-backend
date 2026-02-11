<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicReceiptController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/r/receipt/{order}', [PublicReceiptController::class, 'show'])
    ->name('public.receipts.show')
    ->middleware('signed');

// TAMBAHKAN DI SINI
Route::get('/login', function () {
    return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/login');
})->name('login');

require __DIR__ . '/auth.php';
