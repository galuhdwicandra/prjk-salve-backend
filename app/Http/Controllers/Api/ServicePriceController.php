<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicePriceSetRequest;
use App\Models\Branch;
use App\Models\Service;
use App\Models\ServicePrice;
use Illuminate\Http\Request;

class ServicePriceController extends Controller
{
    /**
     * Set atau update harga override per cabang (idempotent).
     * Body: { service_id, branch_id, price, sla_days? }
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
                    'data'    => null,
                    'meta'    => [],
                    'message' => 'Forbidden',
                    'errors'  => ['branch_id' => ['restricted_to_own_branch']],
                ], 403);
            }
        }

        $attributes = ['price' => $payload['price']];
        if (array_key_exists('sla_days', $payload)) {
            $attributes['sla_days'] = $payload['sla_days'];
        }

        $row = ServicePrice::updateOrCreate(
            [
                'service_id' => $payload['service_id'],
                'branch_id'  => $payload['branch_id'],
            ],
            $attributes
        );

        $row->load(['service', 'branch']);
        return response()->json([
            'data'    => $row,
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function unset(Request $request)
    {
        $serviceId = (string) $request->query('service_id');
        $branchId  = (string) $request->query('branch_id');

        if (! $serviceId || ! $branchId) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'service_id dan branch_id wajib diisi',
                'errors'  => ['service_id' => ['required'], 'branch_id' => ['required']],
            ], 422);
        }

        $service = Service::query()->findOrFail($serviceId);
        $this->authorize('update', $service);

        if ($request->user()->hasRole('Admin Cabang')) {
            if ((string) $request->user()->branch_id !== $branchId) {
                return response()->json([
                    'data'    => null,
                    'meta'    => [],
                    'message' => 'Forbidden',
                    'errors'  => ['branch_id' => ['restricted_to_own_branch']],
                ], 403);
            }
        }

        ServicePrice::query()
            ->where('service_id', $serviceId)
            ->where('branch_id', $branchId)
            ->delete();

        return response()->json([
            'data'    => null,
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    /**
     * Opsional: lihat daftar harga sebuah service di seluruh cabang.
     * Query: ?service_id=... (wajib)
     */
    public function listByService(Request $request)
    {
        $serviceId = (string) $request->query('service_id');
        if (! $serviceId) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'service_id is required',
                'errors'  => ['service_id' => ['required']],
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
            'data'    => $items,
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }
}
