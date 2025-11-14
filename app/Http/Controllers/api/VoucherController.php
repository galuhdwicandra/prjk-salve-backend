<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vouchers\VoucherStoreRequest;
use App\Http\Requests\Vouchers\VoucherUpdateRequest;
use App\Http\Requests\Orders\OrderApplyVoucherRequest;
use App\Models\Order;
use App\Models\Voucher;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function index(Request $req)
    {
        $this->authorize('viewAny', Voucher::class);

        $q = Voucher::query();

        // Filter opsional: code, active
        if ($code = $req->query('code')) {
            $q->where('code', 'like', '%' . strtoupper(trim($code)) . '%');
        }
        if ($req->filled('active')) {
            $q->where('active', (bool) $req->query('active'));
        }

        // Admin cabang hanya melihat miliknya + global
        $u = $req->user();
        if ($u && method_exists($u, 'hasRole') && $u->hasRole('admin')) {
            $q->where(function ($w) use ($u) {
                $w->whereNull('branch_id')->orWhere('branch_id', $u->branch_id);
            });
        }

        $data = $q->orderByDesc('created_at')->paginate((int) ($req->query('per_page', 20)));

        return response()->json([
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(VoucherStoreRequest $req)
    {
        $this->authorize('create', Voucher::class);

        $payload = $req->validated();

        $voucher = Voucher::query()->create([
            'id' => (string) Str::uuid(),
            'branch_id' => $payload['branch_id'] ?? null,
            'code' => $payload['code'],
            'type' => $payload['type'],
            'value' => $payload['value'],
            'start_at' => $payload['start_at'] ?? null,
            'end_at' => $payload['end_at'] ?? null,
            'min_total' => $payload['min_total'] ?? 0,
            'usage_limit' => $payload['usage_limit'] ?? null,
            'active' => $payload['active'] ?? true,
        ]);

        // TODO: audit('VOUCHER_CREATE', ['voucher_id' => $voucher->id, 'actor' => $req->user()?->id]);

        return response()->json([
            'data' => $voucher,
            'meta' => (object) [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function show(Voucher $voucher)
    {
        $this->authorize('view', $voucher);

        return response()->json([
            'data' => $voucher,
            'meta' => (object) [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function update(VoucherUpdateRequest $req, Voucher $voucher)
    {
        $this->authorize('update', $voucher);

        $payload = $req->validated();
        $voucher->fill($payload)->save();

        // TODO: audit('VOUCHER_UPDATE', ['voucher_id' => $voucher->id, 'actor' => $req->user()?->id]);

        return response()->json([
            'data' => $voucher,
            'meta' => (object) [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(Request $req, Voucher $voucher)
    {
        $this->authorize('delete', $voucher);

        $voucher->delete();

        // TODO: audit('VOUCHER_DELETE', ['voucher_id' => $voucher->id, 'actor' => $req->user()?->id]);

        return response()->json([
            'data' => (object) [],
            'meta' => (object) [],
            'message' => 'Deleted',
            'errors' => null,
        ]);
    }

    public function applyToOrder(OrderApplyVoucherRequest $req, Order $order, VoucherService $svc)
    {
        // Otorisasi update order mengikuti pola existing
        $this->authorize('update', $order);

        $code = strtoupper(trim($req->input('code')));
        $voucher = Voucher::query()->where('code', $code)->first();

        if (!$voucher) {
            return response()->json([
                'data' => (object) [],
                'meta' => (object) [],
                'message' => 'Voucher tidak ditemukan.',
                'errors' => ['code' => ['Voucher tidak ditemukan.']],
            ], 422);
        }

        $order = $svc->apply($order, $voucher, $req->user());

        return response()->json([
            'data' => $order,
            'meta' => (object) [],
            'message' => 'Voucher diterapkan.',
            'errors' => null,
        ]);
    }
}
