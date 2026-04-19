<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderPhotosRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderPhotosController extends Controller
{
    public function __construct(private OrderService $svc)
    {
        $this->middleware('auth:sanctum');
    }

    public function store(OrderPhotosRequest $request, Order $order): JsonResponse
    {
        $this->authorize('uploadPhotos', $order);

        $before = $request->file('photos.before', []);
        $after = $request->file('photos.after', []);

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
