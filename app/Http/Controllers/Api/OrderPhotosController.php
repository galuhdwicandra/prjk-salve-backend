<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderPhotosRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class OrderPhotosController extends Controller
{
    public function __construct(private OrderService $svc)
    {
        $this->middleware('auth:sanctum');
    }

    public function store(OrderPhotosRequest $request, Order $order): JsonResponse
    {
        $this->authorize('update', $order);

        $before = $request->file('photos.before', []);
        $after = $request->file('photos.after', []);

        if ((count($before) + count($after)) === 0) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'No files uploaded',
                'errors' => ['photos' => ['empty']],
            ], 422);
        }

        $dir = "uploads/orders/{$order->id}";
        $rows = [];

        foreach ($before as $f) {
            $p = $f->store($dir . '/before', 'public');
            $rows[] = ['kind' => 'before', 'path' => "storage/{$p}"];
        }
        foreach ($after as $f) {
            $p = $f->store($dir . '/after', 'public');
            $rows[] = ['kind' => 'after', 'path' => "storage/{$p}"];
        }

        $order = $this->svc->attachPhotos($order, $rows);

        return response()->json([
            'data' => $order,
            'meta' => [],
            'message' => 'Photos uploaded',
            'errors' => null,
        ]);
    }
}
