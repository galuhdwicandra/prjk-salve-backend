<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $res = $this->svc->apply(
            $order,
            $payload['method'],
            (float) $payload['amount'],
            $payload['paid_at'] ?? null,
            $payload['note'] ?? null
        );

        return response()->json([
            'data' => [
                'order' => $res['order'],
                'payment' => $res['payment'],
            ],
            'meta' => ['idempotent' => $res['idempotent']],
            'message' => 'Payment applied',
            'errors' => null,
        ], 201);
    }
}
