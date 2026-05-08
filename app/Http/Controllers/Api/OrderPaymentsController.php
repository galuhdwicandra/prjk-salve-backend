<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\PaymentCorrectionRequest;
use App\Http\Requests\Payments\PaymentRequest;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class OrderPaymentsController extends Controller
{
    public function __construct(private PaymentService $svc)
    {
        $this->middleware('auth:sanctum');
    }

    public function store(PaymentRequest $request, Order $order): JsonResponse
    {
        $this->authorize('settlePayment', $order);

        $payload = $request->validated();
        $res     = $this->svc->apply(
            $order,
            $payload['method'],
            (float) $payload['amount'],
            $payload['paid_at'] ?? null,
            $payload['note'] ?? null
        );

        return response()->json([
            'data'    => [
                'order'   => $res['order'],
                'payment' => $res['payment'],
            ],
            'meta'    => ['idempotent' => $res['idempotent']],
            'message' => 'Payment applied',
            'errors'  => null,
        ], 201);
    }

    public function resetToPending(PaymentCorrectionRequest $request, Order $order): JsonResponse
    {
        $this->authorizePaymentCorrection($request, $order);

        $payload = $request->validated();

        $res = $this->svc->resetToPending(
            $order,
            $request->user(),
            (string) $payload['reason']
        );

        return response()->json([
            'data'    => [
                'order' => $res['order'],
            ],
            'meta'    => $res['meta'],
            'message' => 'Payment corrected to pending',
            'errors'  => null,
        ]);
    }

    private function authorizePaymentCorrection(PaymentCorrectionRequest $request, Order $order): void
    {
        $user = $request->user();

        if (! $user->hasRole('Superadmin') && ! $user->hasRole('Admin Cabang')) {
            abort(403, 'Hanya Superadmin atau Admin Cabang yang dapat melakukan koreksi pembayaran.');
        }

        if ($user->hasRole('Superadmin')) {
            return;
        }

        if ((string) $order->branch_id !== (string) $user->branch_id) {
            abort(403, 'Anda tidak memiliki akses ke order cabang ini.');
        }
    }
}
