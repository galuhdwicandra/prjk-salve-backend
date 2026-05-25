<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\AccountingReportFilterRequest;
use App\Services\Accounting\CashFlowService;

class CashFlowController extends Controller
{
    public function __construct(
        private CashFlowService $cashFlowService,
    ) {}

    public function index(AccountingReportFilterRequest $request)
    {
        $result = $this->cashFlowService->build(
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
