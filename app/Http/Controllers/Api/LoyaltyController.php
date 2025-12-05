<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LoyaltyLog;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function __construct(private LoyaltyService $svc) {}

    public function summary(Request $req, Customer $customer)
    {
        $this->authorize('viewLoyalty', $customer);

        $branchId = (string) ($req->query('branch_id') ?: $req->user()->branch_id ?: $customer->branch_id);
        $acc = $this->svc->getOrCreateAccount((string) $customer->getKey(), $branchId);

        return response()->json([
            'data' => [
                'stamps' => (int) $acc->stamps,
                'cycle'  => 10,
                'next'   => (($acc->stamps % 10) + 1),
            ],
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function history(Request $req, Customer $customer)
    {
        $this->authorize('viewLoyalty', $customer);

        $branchId = (string) ($req->query('branch_id') ?: $req->user()->branch_id ?: $customer->branch_id);
        $logs = LoyaltyLog::query()
            ->where('customer_id', (string) $customer->getKey())
            ->where('branch_id', $branchId)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'data' => $logs->items(),
            'meta' => ['current_page' => $logs->currentPage(), 'last_page' => $logs->lastPage()],
            'message' => 'OK',
            'errors' => null,
        ]);
    }
}
