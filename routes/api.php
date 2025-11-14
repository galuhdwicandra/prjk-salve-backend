<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\InvoiceCounterController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ServicePriceController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderPaymentsController;
use App\Http\Controllers\Api\OrderPhotosController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\api\ReceivableController;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        // User routes
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);   // was {id}
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{user}', [UserController::class, 'update']); // was {id}
        Route::delete('/users/{user}', [UserController::class, 'destroy']); // was {id}

        // Aksi khusus sudah benar (sudah {user})
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword']);
        Route::post('/users/{user}/active', [UserController::class, 'setActive']);
        Route::post('/users/{user}/roles', [UserController::class, 'setRoles']);

        // Branches (CRUD)
        Route::get('/branches', [BranchController::class, 'index']);
        Route::post('/branches', [BranchController::class, 'store']);
        Route::get('/branches/{branch}', [BranchController::class, 'show']);
        Route::put('/branches/{branch}', [BranchController::class, 'update']);
        Route::delete('/branches/{branch}', [BranchController::class, 'destroy']);

        // Invoice Counters (get list per branch + update per id)
        Route::get('/invoice-counters', [InvoiceCounterController::class, 'index']);
        Route::post('/invoice-counters', [InvoiceCounterController::class, 'store']);
        Route::delete('/invoice-counters/{id}', [InvoiceCounterController::class, 'destroy']);
        Route::put('/invoice-counters/{id}', [InvoiceCounterController::class, 'update']);

        Route::get('/service-categories', [CategoryController::class, 'index']);
        Route::post('/service-categories', [CategoryController::class, 'store']);
        Route::get('/service-categories/{category}', [CategoryController::class, 'show']);
        Route::put('/service-categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/service-categories/{category}', [CategoryController::class, 'destroy']);

        Route::get('/services', [ServiceController::class, 'index']);
        Route::post('/services', [ServiceController::class, 'store']);
        Route::get('/services/{service}', [ServiceController::class, 'show']);
        Route::put('/services/{service}', [ServiceController::class, 'update']);
        Route::delete('/services/{service}', [ServiceController::class, 'destroy']);

        Route::post('/service-prices/set', [ServicePriceController::class, 'set']);
        Route::get('/service-prices/by-service', [ServicePriceController::class, 'listByService']);

        Route::get('/customers', [CustomerController::class, 'index']);
        Route::get('/customers/search-wa', [CustomerController::class, 'searchByWhatsapp']);
        Route::get('/customers/{customer}', [CustomerController::class, 'show']);
        Route::post('/customers', [CustomerController::class, 'store']);
        Route::put('/customers/{customer}', [CustomerController::class, 'update']);
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy']);

        // Receipt (HTML)
        Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt']);

        // Payments
        Route::post('/orders/{order}/payments', [OrderPaymentsController::class, 'store']);

        // Apply voucher ke order
        Route::post('/orders/{order}/apply-voucher', [\App\Http\Controllers\Api\VoucherController::class, 'applyToOrder']);

        // Orders
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::put('/orders/{order}', [OrderController::class, 'update']);
        Route::post('/orders/{order}/status', [OrderController::class, 'transitionStatus']);
        Route::post('/orders/{order}/photos', [OrderPhotosController::class, 'store']);

        // Deliveries
        Route::get('/deliveries', [DeliveryController::class, 'index']);
        Route::get('/deliveries/{delivery}', [DeliveryController::class, 'show']);
        Route::post('/deliveries', [DeliveryController::class, 'store']);
        Route::put('/deliveries/{delivery}/assign', [DeliveryController::class, 'assign']);
        Route::put('/deliveries/{delivery}/status', [DeliveryController::class, 'updateStatus']);

        Route::get('/vouchers', [\App\Http\Controllers\Api\VoucherController::class, 'index']);
        Route::post('/vouchers', [\App\Http\Controllers\Api\VoucherController::class, 'store']);
        Route::get('/vouchers/{voucher}', [\App\Http\Controllers\Api\VoucherController::class, 'show']);
        Route::put('/vouchers/{voucher}', [\App\Http\Controllers\Api\VoucherController::class, 'update']);
        Route::delete('/vouchers/{voucher}', [\App\Http\Controllers\Api\VoucherController::class, 'destroy']);

        Route::get('/receivables', [ReceivableController::class, 'index']);
        Route::post('/receivables/{id}/settle', [ReceivableController::class, 'settle']);

        // Tambahkan route lain di sini sesuai kebutuhan
    });
});
