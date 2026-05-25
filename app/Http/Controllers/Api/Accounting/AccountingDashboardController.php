<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\AccountingReportFilterRequest;
use App\Services\Accounting\AccountingDashboardService;

class AccountingDashboardController extends Controller
{
    public function __construct(
        private AccountingDashboardService $dashboardService,
    ) {}

    public function index(AccountingReportFilterRequest $request)
    {
        $result = $this->dashboardService->build(
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'data' => $result['data'],
            'meta' => $result['meta'],
            'message' => 'OK',
            'errors' => null,
        ]);
    }
}
