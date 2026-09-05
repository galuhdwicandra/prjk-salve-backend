<?php

use App\Http\Controllers\Api\Accounting\AccountController;
use App\Http\Controllers\Api\Accounting\AccountingDashboardController;
use App\Http\Controllers\Api\Accounting\AccountMappingController;
use App\Http\Controllers\Api\Accounting\BalanceSheetController;
use App\Http\Controllers\Api\Accounting\CashFlowController;
use App\Http\Controllers\Api\Accounting\JournalEntryController;
use App\Http\Controllers\Api\Accounting\LedgerController;
use App\Http\Controllers\Api\Accounting\ProfitLossController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\BranchTypeController;
use App\Http\Controllers\Api\CashTransactionController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactCategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CustomerLabelController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\DeliveryNoteController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\InvoiceCounterController;
use App\Http\Controllers\Api\LoyaltyController;
use App\Http\Controllers\Api\LoyaltySettingController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderPaymentsController;
use App\Http\Controllers\Api\OrderPhotosController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\ProductionBoardController;
use App\Http\Controllers\Api\ProductionCorrectionRequestController;
use App\Http\Controllers\Api\ReceivableController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ServicePriceController;
use App\Http\Controllers\Api\SortingController;
use App\Http\Controllers\Api\TrackerController;
use App\Http\Controllers\Api\TransactionCategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WashNoteController;
use App\Http\Controllers\Api\WhatsappTemplateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use Illuminate\Support\Facades\URL;

Route::get('/r/receipt/{order}', [OrderController::class, 'receipt'])
    ->name('public.receipts.show');

Route::get('/receipt/{order}', function (Request $request, \App\Models\Order $order) {
    if (! $request->hasValidSignature()) {
        abort(403);
    }

    return redirect()->to(route('public.receipts.show', [
        'order' => (string) $order->getKey(),
    ]));
})->name('public.receipts.legacy');

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
        });
    });

    // Public order tracker — tanpa login, hanya lewat token rahasia
    Route::get('/track/t/{token}', [TrackerController::class, 'publicShow']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users', [UserController::class, 'index'])
            ->middleware('module:set-user,ops-kirim,ops-proses,laporan');

        Route::middleware('module:set-user')->group(function () {
            Route::get('/users/{user}', [UserController::class, 'show']);
            Route::post('/users', [UserController::class, 'store']);
            Route::put('/users/{user}', [UserController::class, 'update']);
            Route::delete('/users/{user}', [UserController::class, 'destroy']);

            Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword']);
            Route::post('/users/{user}/active', [UserController::class, 'setActive']);
            Route::post('/users/{user}/roles', [UserController::class, 'setRoles']);
        });

        // Branches (CRUD)
        Route::get('/branches', [BranchController::class, 'index']);
        Route::get('/branches/{branch}', [BranchController::class, 'show']);
        Route::middleware('module:set-outlet')->group(function () {
            Route::post('/branches', [BranchController::class, 'store']);
            Route::put('/branches/{branch}', [BranchController::class, 'update']);
            Route::delete('/branches/{branch}', [BranchController::class, 'destroy']);

            Route::get('/branch-types', [BranchTypeController::class, 'index']);
            Route::post('/branch-types', [BranchTypeController::class, 'store']);
            Route::put('/branch-types/{branchType}', [BranchTypeController::class, 'update']);
            Route::delete('/branch-types/{branchType}', [BranchTypeController::class, 'destroy']);
        });

        Route::middleware('module:set-num')->group(function () {
            Route::get('/invoice-counters', [InvoiceCounterController::class, 'index']);
            Route::post('/invoice-counters', [InvoiceCounterController::class, 'store']);
            Route::delete('/invoice-counters/{id}', [InvoiceCounterController::class, 'destroy']);
            Route::put('/invoice-counters/{id}', [InvoiceCounterController::class, 'update']);

            Route::get('/invoice-counters/preview', [InvoiceCounterController::class, 'preview']);
            Route::post('/invoice-counters/{id}/reset-now', [InvoiceCounterController::class, 'resetNow']);
        });

        Route::middleware('module:set-master')->group(function () {
            Route::get('/service-categories', [CategoryController::class, 'index']);
            Route::get('/service-categories/{category}', [CategoryController::class, 'show']);
            Route::post('/service-categories', [CategoryController::class, 'store']);
            Route::put('/service-categories/{category}', [CategoryController::class, 'update']);
            Route::delete('/service-categories/{category}', [CategoryController::class, 'destroy']);
            Route::post('/services', [ServiceController::class, 'store']);
            Route::put('/services/{service}', [ServiceController::class, 'update']);
            Route::delete('/services/{service}', [ServiceController::class, 'destroy']);
            Route::post('/service-prices/set', [ServicePriceController::class, 'set']);
            Route::delete('/service-prices', [ServicePriceController::class, 'unset']);
        });

        Route::middleware('module:set-master,kasir-pos,kasir-receipt,set-jurnal')->group(function () {
            Route::get('/services', [ServiceController::class, 'index']);
            Route::get('/services/{service}', [ServiceController::class, 'show']);
            Route::get('/service-prices/by-service', [ServicePriceController::class, 'listByService']);
        });

        Route::middleware('module:kasir-customer,kasir-pos,kasir-receipt')->group(function () {
            Route::get('/customers', [CustomerController::class, 'index']);
            Route::get('/customers/search-wa', [CustomerController::class, 'searchByWhatsapp']);
            Route::post('/customers', [CustomerController::class, 'store']);
        });

        Route::middleware('module:kasir-customer')->group(function () {
            Route::get('/customers/{customer}', [CustomerController::class, 'show']);
            Route::put('/customers/{customer}', [CustomerController::class, 'update']);
            Route::delete('/customers/{customer}', [CustomerController::class, 'destroy']);
        });

        Route::get('/customer-labels', [CustomerLabelController::class, 'index'])
            ->middleware('module:set-labels,kasir-pos,kasir-customer');

        Route::middleware('module:set-labels')->group(function () {
            Route::post('/customer-labels', [CustomerLabelController::class, 'store']);
            Route::put('/customer-labels/{customerLabel}', [CustomerLabelController::class, 'update']);
            Route::delete('/customer-labels/{customerLabel}', [CustomerLabelController::class, 'destroy']);
        });

        Route::get('/transaction-categories', [TransactionCategoryController::class, 'index'])
            ->middleware('module:set-coa,fin-transaksi,fin-kas,set-jurnal');

        Route::middleware('module:set-coa')->group(function () {
            Route::post('/transaction-categories', [TransactionCategoryController::class, 'store']);
            Route::put('/transaction-categories/{transactionCategory}', [TransactionCategoryController::class, 'update']);
            Route::delete('/transaction-categories/{transactionCategory}', [TransactionCategoryController::class, 'destroy']);
        });

        Route::post('/transaction-categories/{transactionCategory}/default', [TransactionCategoryController::class, 'setDefault'])
            ->middleware('module:set-jurnal');

        // Wash Notes
        Route::middleware('module:ops-proses')->group(function () {
            Route::get('/wash-notes/candidates', [WashNoteController::class, 'candidates']);
            Route::apiResource('wash-notes', WashNoteController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
        });

        Route::get('/production-board/reports/work-recap', [ProductionBoardController::class, 'workRecap'])
            ->middleware('module:laporan');

        Route::middleware('module:ops-proses')->group(function () {
            Route::get('/production-board', [ProductionBoardController::class, 'index']);

            Route::get('/production-board/correction-requests', [ProductionCorrectionRequestController::class, 'index']);
            Route::post('/production-board/{order}/correction-requests', [ProductionCorrectionRequestController::class, 'store']);
            Route::post('/production-board/correction-requests/{correctionRequest}/approve', [ProductionCorrectionRequestController::class, 'approve']);
            Route::post('/production-board/correction-requests/{correctionRequest}/reject', [ProductionCorrectionRequestController::class, 'reject']);

            Route::post('/production-board/{order}/start', [ProductionBoardController::class, 'start']);
            Route::post('/production-board/{order}/move', [ProductionBoardController::class, 'move']);
            Route::post('/production-board/{order}/finish', [ProductionBoardController::class, 'finish']);
        });

        // Reports
        Route::middleware('module:laporan')->group(function () {
            Route::get('/reports/{kind}', [ReportController::class, 'preview']);
            Route::get('/reports/{kind}/export', [ReportController::class, 'export']);
        });

        // Loyalty (Stamp) — butuh login & scope cabang
        Route::get('/loyalty/{customer}', [LoyaltyController::class, 'summary'])
            ->middleware('module:kasir-pos,kasir-customer');
        Route::middleware('module:kasir-customer')->group(function () {
            Route::get('/loyalty/{customer}/history', [LoyaltyController::class, 'history']);
            Route::post('/loyalty/{customer}/adjust-manual', [LoyaltyController::class, 'adjustManual']);
        });

        // Receipt (HTML)
        Route::middleware('module:kasir-receipt')->group(function () {
            Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt']);
            Route::post('/orders/{order}/share-link', [OrderController::class, 'shareLink']);
        });

        // Payments
        Route::post('/orders/{order}/payments', [OrderPaymentsController::class, 'store'])
            ->middleware('module:kasir-pos,kasir-receipt');
        Route::post('/orders/{order}/payments/reset-to-pending', [OrderPaymentsController::class, 'resetToPending'])
            ->middleware('module:kasir-receipt');
        Route::put('/orders/{order}/payments/{payment}', [OrderPaymentsController::class, 'update'])
            ->middleware('module:kasir-receipt');
        Route::delete('/orders/{order}/payments/{payment}', [OrderPaymentsController::class, 'destroy'])
            ->middleware('module:kasir-receipt');

        // Apply voucher ke order
        Route::post('/orders/{order}/apply-voucher', [\App\Http\Controllers\Api\VoucherController::class, 'applyToOrder'])
            ->middleware('module:kasir-pos');

        // Orders
        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->middleware('module:kasir-receipt,kasir-pos,ops-kirim,ops-sorting,ops-proses');
        Route::post('/orders', [OrderController::class, 'store'])
            ->middleware('module:kasir-pos');
        Route::post('/orders/{order}/photos', [OrderPhotosController::class, 'store'])
            ->middleware('module:kasir-pos,kasir-receipt,ops-sorting,ops-proses');
        Route::delete('/orders/{order}/photos/{photo}', [OrderPhotosController::class, 'destroy'])
            ->middleware('module:kasir-pos,kasir-receipt,ops-sorting,ops-proses');

        Route::get('/orders', [OrderController::class, 'index'])
            ->middleware('module:kasir-receipt,kasir-customer');

        Route::middleware('module:kasir-receipt')->group(function () {
            Route::post('/orders/bulk-void', [OrderController::class, 'bulkVoid']);
            Route::put('/orders/{order}', [OrderController::class, 'update']);
            Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
            Route::post('/orders/{order}/void', [OrderController::class, 'void']);
            Route::post('/orders/{order}/status', [OrderController::class, 'transitionStatus']);
            Route::post('/orders/{order}/loyalty-correction', [OrderController::class, 'applyLoyaltyCorrection']);
        });

        // Tracker (read-only)
        Route::middleware('module:ops-tracker')->group(function () {
            Route::get('/tracker/search', [TrackerController::class, 'search']);
            Route::get('/tracker/orders/{order}', [TrackerController::class, 'show']);
            Route::post('/tracker/orders/{order}/link', [TrackerController::class, 'issueLink']);
            Route::delete('/tracker/orders/{order}/link', [TrackerController::class, 'revokeLink']);
        });

        // Sorting List
        Route::middleware('module:ops-sorting')->group(function () {
            Route::get('/sorting/{tab}', [SortingController::class, 'index'])
                ->whereIn('tab', ['incoming', 'delivery-note', 'at-subcon', 'ready']);
            Route::post('/sorting/sort', [SortingController::class, 'sort']);
            Route::post('/sorting/handover', [SortingController::class, 'handover']);
            Route::post('/sorting/courier', [SortingController::class, 'courier']);
        });

        Route::middleware('module:ops-sorting,ops-kirim')->group(function () {
            Route::get('/delivery-notes', [DeliveryNoteController::class, 'index']);
            Route::get('/delivery-notes/{deliveryNote}', [DeliveryNoteController::class, 'show']);
            Route::post('/delivery-notes', [DeliveryNoteController::class, 'store']);
            Route::post('/delivery-notes/pickup', [DeliveryNoteController::class, 'storePickup']);
            Route::post('/delivery-notes/{deliveryNote}/complete', [DeliveryNoteController::class, 'complete']);
            Route::post('/delivery-notes/{deliveryNote}/pick', [DeliveryNoteController::class, 'pick']);
            Route::post('/delivery-notes/{deliveryNote}/arrive', [DeliveryNoteController::class, 'arrive']);
        });

        // Deliveries
        Route::middleware('module:ops-kirim')->group(function () {
            Route::get('/deliveries', [DeliveryController::class, 'index']);
            Route::get('/deliveries/{delivery}', [DeliveryController::class, 'show']);
            Route::post('/deliveries', [DeliveryController::class, 'store']);
            Route::put('/deliveries/{delivery}/assign', [DeliveryController::class, 'assign']);
            Route::put('/deliveries/{delivery}/status', [DeliveryController::class, 'updateStatus']);
        });

        Route::middleware('module:kasir-promo')->group(function () {
            Route::get('/loyalty-settings', [LoyaltySettingController::class, 'show']);
            Route::put('/loyalty-settings', [LoyaltySettingController::class, 'update']);

            Route::get('/vouchers', [\App\Http\Controllers\Api\VoucherController::class, 'index']);
            Route::post('/vouchers', [\App\Http\Controllers\Api\VoucherController::class, 'store']);
            Route::get('/vouchers/{voucher}', [\App\Http\Controllers\Api\VoucherController::class, 'show']);
            Route::put('/vouchers/{voucher}', [\App\Http\Controllers\Api\VoucherController::class, 'update']);
            Route::delete('/vouchers/{voucher}', [\App\Http\Controllers\Api\VoucherController::class, 'destroy']);
        });

        Route::middleware('module:kasir-receipt')->group(function () {
            Route::get('/receivables', [ReceivableController::class, 'index']);
            Route::post('/receivables/{id}/settle', [ReceivableController::class, 'settle']);
        });

        Route::apiResource('expenses', ExpenseController::class)
            ->only(['index', 'store', 'show', 'update', 'destroy'])
            ->middleware('module:fin-transaksi');

        Route::middleware('module:fin-transaksi')->group(function () {
            Route::get('/cash-transactions', [CashTransactionController::class, 'index']);
            Route::get('/cash-transactions/{cashTransaction}', [CashTransactionController::class, 'show']);
            Route::post('/cash-transactions', [CashTransactionController::class, 'store']);
            Route::put('/cash-transactions/{cashTransaction}', [CashTransactionController::class, 'update']);
            Route::delete('/cash-transactions/{cashTransaction}', [CashTransactionController::class, 'destroy']);
            Route::post('/cash-transactions/transfer', [CashTransactionController::class, 'transfer']);
        });

        Route::middleware('module:fin-kontak,fin-transaksi')->group(function () {
            Route::get('/contacts', [ContactController::class, 'index']);
            Route::get('/contacts/{contact}', [ContactController::class, 'show']);
            Route::post('/contacts', [ContactController::class, 'store']);

            Route::get('/contact-categories', [ContactCategoryController::class, 'index']);
        });

        Route::middleware('module:fin-kontak')->group(function () {
            Route::put('/contacts/{contact}', [ContactController::class, 'update']);
            Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);
            Route::get('/contact-categories/{contactCategory}', [ContactCategoryController::class, 'show']);
            Route::post('/contact-categories', [ContactCategoryController::class, 'store']);
            Route::put('/contact-categories/{contactCategory}', [ContactCategoryController::class, 'update']);
            Route::delete('/contact-categories/{contactCategory}', [ContactCategoryController::class, 'destroy']);
        });

        Route::get('dashboard/summary', [DashboardController::class, 'summary'])
            ->middleware('module:dashboard');

        // WhatsApp Templates
        Route::get('/whatsapp-templates/resolve', [WhatsappTemplateController::class, 'resolve'])
            ->middleware('module:set-wa,kasir-receipt');

        Route::middleware('module:set-wa')->group(function () {
            Route::get('/whatsapp-templates', [WhatsappTemplateController::class, 'index']);
            Route::post('/whatsapp-templates', [WhatsappTemplateController::class, 'store']);
            Route::get('/whatsapp-templates/{whatsappTemplate}', [WhatsappTemplateController::class, 'show']);
            Route::put('/whatsapp-templates/{whatsappTemplate}', [WhatsappTemplateController::class, 'update']);
            Route::delete('/whatsapp-templates/{whatsappTemplate}', [WhatsappTemplateController::class, 'destroy']);
        });

        Route::get('/payment-methods', [PaymentMethodController::class, 'index'])
            ->middleware('module:set-paymethod,kasir-pos,kasir-receipt');

        Route::middleware('module:set-paymethod')->group(function () {
            Route::post('/payment-methods', [PaymentMethodController::class, 'store']);
            Route::get('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'show']);
            Route::put('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update']);
            Route::delete('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy']);
            Route::post('/payment-methods/{paymentMethod}/account', [PaymentMethodController::class, 'setAccount']);
        });

        // Accounting
        Route::prefix('accounting')->group(function () {
            Route::get('accounts', [AccountController::class, 'index'])
                ->middleware('module:set-coa,set-jurnal,laporan,fin-kas,fin-transaksi,set-paymethod');

            Route::post('accounts/bulk-destroy', [AccountController::class, 'bulkDestroy'])
                ->middleware('module:set-coa,fin-kas,set-jurnal');

            Route::apiResource('accounts', AccountController::class)
                ->parameters(['accounts' => 'account'])
                ->except(['index'])
                ->middleware('module:set-coa,fin-kas');

            Route::middleware('module:set-jurnal')->group(function () {
                Route::apiResource('account-mappings', AccountMappingController::class)
                    ->parameters(['account-mappings' => 'accountMapping']);

                Route::get('/journals', [JournalEntryController::class, 'index']);
                Route::post('/journals', [JournalEntryController::class, 'store']);
                Route::post('/journals/transfer', [JournalEntryController::class, 'transfer']);
                Route::get('/journals/{journal}', [JournalEntryController::class, 'show']);
                Route::put('/journals/{journal}', [JournalEntryController::class, 'update']);
                Route::post('/journals/{journal}/post', [JournalEntryController::class, 'post']);
                Route::post('/journals/{journal}/void', [JournalEntryController::class, 'void']);
            });

            Route::get('/ledger', [LedgerController::class, 'index'])
                ->middleware('module:laporan,fin-kas');

            Route::middleware('module:laporan')->group(function () {
                Route::get('/dashboard', [AccountingDashboardController::class, 'index']);
                Route::get('/reports/profit-loss', [ProfitLossController::class, 'index']);
                Route::get('/reports/balance-sheet', [BalanceSheetController::class, 'index']);
                Route::get('/reports/cash-flow', [CashFlowController::class, 'index']);
            });
        });

    });
});
