<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderPhotosRequest;
use App\Models\Order;
use App\Models\OrderPhoto;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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
        $after  = $request->file('photos.after', []);

        $dir  = "uploads/orders/{$order->id}";
        $rows = [];

        foreach ($before as $f) {
            $p      = $f->store($dir . '/before', 'public');
            $rows[] = ['kind' => 'before', 'path' => "storage/{$p}"];
        }

        foreach ($after as $f) {
            $p      = $f->store($dir . '/after', 'public');
            $rows[] = ['kind' => 'after', 'path' => "storage/{$p}"];
        }

        $replaceExisting = (bool) $request->boolean('replace_existing');

        $order = $this->svc->attachPhotos($order, $rows, $replaceExisting);

        return response()->json([
            'data'    => $order,
            'meta'    => [],
            'message' => 'Photos uploaded',
            'errors'  => null,
        ]);
    }

    public function destroy(Order $order, OrderPhoto $photo): JsonResponse
    {
        $this->authorize('uploadPhotos', $order);

        abort_unless((string) $photo->order_id === (string) $order->getKey(), 404);

        $relative = preg_replace('#^storage/#', '', (string) $photo->path);

        if ($relative !== null && Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }

        $photo->delete();

        return response()->json([
            'data'    => $order->fresh(['customer', 'items.service', 'photos', 'receivable', 'payments']),
            'meta'    => [],
            'message' => 'Foto dihapus',
            'errors'  => null,
        ]);
    }
}
