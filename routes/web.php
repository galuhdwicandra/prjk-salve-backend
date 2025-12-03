<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/r/receipt/{order}', [OrderController::class, 'receipt'])
    ->name('public.receipts.show')
    ->middleware('signed');

require __DIR__ . '/auth.php';
