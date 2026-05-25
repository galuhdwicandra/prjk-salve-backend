<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\LedgerFilterRequest;
use App\Services\Accounting\AccountingLedgerService;

class LedgerController extends Controller
{
    public function __construct(
        private AccountingLedgerService $ledgerService,
    ) {}

    public function index(LedgerFilterRequest $request)
    {
        $result = $this->ledgerService->build(
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
