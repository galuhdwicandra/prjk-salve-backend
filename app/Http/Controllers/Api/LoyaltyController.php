<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoyaltyManualAdjustRequest;
use App\Models\Customer;
use App\Models\LoyaltyLog;
use App\Services\LoyaltyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function __construct(private LoyaltyService $svc)
    {}

    public function summary(Request $req, Customer $customer): JsonResponse
    {
        $this->authorize('viewLoyalty', $customer);

        $branchId = (string) ($req->query('branch_id') ?: $req->user()->branch_id ?: $customer->branch_id);
        $acc      = $this->svc->getOrCreateAccount((string) $customer->getKey(), $branchId);

        $currentStamp = (int) $acc->stamps;

        return response()->json([
            'data'    => [
                'stamps' => $currentStamp,
                'cycle'  => LoyaltyService::CYCLE,
                'next'   => $currentStamp >= LoyaltyService::CYCLE
                    ? LoyaltyService::CYCLE
                    : $currentStamp,
            ],
            'meta'    => [],
            'message' => 'Loyalty stamp berhasil diubah secara manual.',
            'errors'  => null,
        ]);
    }

    public function history(Request $req, Customer $customer): JsonResponse
    {
        $this->authorize('viewLoyalty', $customer);

        $branchId = (string) ($req->query('branch_id') ?: $req->user()->branch_id ?: $customer->branch_id);

        $logs = LoyaltyLog::query()
            ->where('customer_id', (string) $customer->getKey())
            ->where('branch_id', $branchId)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'data'    => $logs->items(),
            'meta'    => [
                'current_page' => $logs->currentPage(),
                'last_page'    => $logs->lastPage(),
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function adjustManual(LoyaltyManualAdjustRequest $request, Customer $customer): JsonResponse
    {
        $this->authorize('manageManual', $customer);

        $user = $request->user();

        $branchId = $user->hasRole('Superadmin')
            ? (string) ($request->validated('branch_id') ?: $customer->branch_id)
            : (string) $user->branch_id;

        if ((string) $customer->branch_id !== (string) $branchId) {
            return response()->json([
                'data'    => null,
                'meta'    => null,
                'message' => 'Customer tidak berada di cabang yang dipilih.',
                'errors'  => [
                    'branch_id' => ['Customer tidak berada di cabang yang dipilih.'],
                ],
            ], 422);
        }

        $acc = $this->svc->adjustManual(
            (string) $customer->getKey(),
            $branchId,
            (string) $request->validated('type'),
            (int) $request->validated('amount'),
            $request->validated('note')
        );

        $currentStamp = (int) $acc->stamps;

        return response()->json([
            'data'    => [
                'stamps' => $currentStamp,
                'cycle'  => LoyaltyService::CYCLE,
                'next'   => $currentStamp >= LoyaltyService::CYCLE
                    ? LoyaltyService::CYCLE
                    : $currentStamp,
            ],
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }
}
