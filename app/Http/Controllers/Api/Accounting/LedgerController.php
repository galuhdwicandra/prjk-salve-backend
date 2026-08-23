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
        $filters = $request->validated();

        $result = empty($filters['account_id'])
            ? $this->ledgerService->buildAll($filters, $request->user())
            : $this->ledgerService->build($filters, $request->user());

        if (! $request->user()->show_balance) {
            $result = $this->stripBalances($result);
        }

        return response()->json([
            'data'    => $result['data'],
            'meta'    => $result['meta'],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    private function stripBalances(array $result): array
    {
        if (! empty($result['meta']['grouped'])) {
            $result['data'] = array_map(function (array $group) {
                unset($group['opening_balance'], $group['ending_balance']);

                $group['rows'] = array_map(function (array $row) {
                    unset($row['balance']);
                    return $row;
                }, $group['rows']);

                return $group;
            }, $result['data']);

            return $result;
        }

        $result['data'] = array_map(function (array $row) {
            unset($row['balance']);
            return $row;
        }, $result['data']);

        unset($result['meta']['opening_balance'], $result['meta']['ending_balance']);

        return $result;
    }

}
