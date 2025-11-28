<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicePriceSetRequest;
use App\Models\Branch;
use App\Models\Service;
use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServicePriceController extends Controller
{
    /**
     * Set atau update harga override per cabang (idempotent).
     * Body: { service_id, branch_id, price }
     */
    public function set(ServicePriceSetRequest $request)
    {
        $payload = $request->validated();

        // Otorisasi: hanya Superadmin/Admin Cabang; Admin Cabang dibatasi ke cabangnya
        $service = Service::query()->findOrFail($payload['service_id']);
        $this->authorize('update', $service);

        $branch = Branch::query()->findOrFail($payload['branch_id']);
        if ($request->user()->hasRole('Admin Cabang')) {
            if ((string) $request->user()->branch_id !== (string) $branch->id) {
                return response()->json([
                    'data' => null,
                    'meta' => [],
                    'message' => 'Forbidden',
                    'errors' => ['branch_id' => ['restricted_to_own_branch']],
                ], 403);
            }
        }

        $row = DB::transaction(function () use ($payload) {
            /** @var \App\Models\ServicePrice $sp */
            $sp = ServicePrice::query()
                ->where('service_id', $payload['service_id'])
                ->where('branch_id', $payload['branch_id'])
                ->lockForUpdate()
                ->first();

            if (!$sp) {
                $sp = new ServicePrice([
                    'id' => (string) Str::uuid(),
                    'service_id' => $payload['service_id'],
                    'branch_id' => $payload['branch_id'],
                    'price' => $payload['price'],
                ]);
                $sp->save();
            } else {
                $sp->price = $payload['price'];
                $sp->save();
            }

            // TODO: audit('SERVICE_PRICE_SET', $sp)

            return $sp->fresh(['service', 'branch']);
        });

        return response()->json([
            'data' => $row,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    /**
     * Opsional: lihat daftar harga sebuah service di seluruh cabang.
     * Query: ?service_id=... (wajib)
     */
    public function listByService(Request $request)
    {
        $serviceId = (string) $request->query('service_id');
        if (!$serviceId) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'service_id is required',
                'errors' => ['service_id' => ['required']],
            ], 422);
        }

        $service = Service::query()->findOrFail($serviceId);
        $this->authorize('view', $service);

        $items = ServicePrice::query()
            ->where('service_id', $serviceId)
            ->with('branch')
            ->orderBy('branch_id')
            ->get();

        return response()->json([
            'data' => $items,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }
}
