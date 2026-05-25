<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\BalanceSheetFilterRequest;
use App\Services\Accounting\BalanceSheetService;

class BalanceSheetController extends Controller
{
    public function __construct(
        private BalanceSheetService $balanceSheetService,
    ) {}

    public function index(BalanceSheetFilterRequest $request)
    {
        $result = $this->balanceSheetService->build(
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
