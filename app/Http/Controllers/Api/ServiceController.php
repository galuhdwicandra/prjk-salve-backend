<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceStoreRequest;
use App\Http\Requests\ServiceUpdateRequest;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Service::class);

        $tree = $request->boolean('tree');

        $q = Service::query()->with('category')->orderBy('name');

        $branchId = $request->query('branch_id');

        $scopePrices = function ($p) use ($branchId) {
            if ($branchId) {
                $p->where('branch_id', $branchId);
            }
        };

        $q->with(['prices' => $scopePrices]);

        if ($tree) {
            $q->whereNull('parent_id')->with(['variants' => function ($v) use ($branchId, $scopePrices) {
                $v->orderBy('name')->with(['prices' => $scopePrices]);
                if ($branchId) {
                    $v->whereHas('prices', fn($p) => $p->where('branch_id', $branchId));
                }
            }]);
        }

        if ($branchId) {
            $q->where(function ($w) use ($branchId, $tree) {
                $w->whereHas('prices', fn($p) => $p->where('branch_id', $branchId));

                if ($tree) {
                    $w->orWhereHas('variants.prices', fn($p) => $p->where('branch_id', $branchId));
                }
            });
        }

        if ($request->boolean('leaf')) {
            $q->whereDoesntHave('variants');
        }

        if ($request->boolean('root')) {
            $q->whereNull('parent_id');
        }

        if ($s = $request->query('q')) {
            $q->where(function ($w) use ($s, $tree) {
                $w->where('name', 'like', "%{$s}%")
                    ->orWhere('unit', 'like', "%{$s}%");
                if ($tree) {
                    $w->orWhereHas('variants', function ($v) use ($s) {
                        $v->where('name', 'like', "%{$s}%");
                    });
                }
            });
        }
        if ($cat = $request->query('category_id')) {
            $q->where('category_id', $cat);
        }
        if (($act = $request->query('is_active')) !== null) {
            $q->where('is_active', (bool) $act);
        }

        $items = $q->paginate((int) $request->query('per_page', 10));

        return response()->json([
            'data'    => $items->items(),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
                'last_page'    => $items->lastPage(),
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function show(Service $service)
    {
        $this->authorize('view', $service);

        return response()->json([
            'data'    => $service->load('category', 'prices'),
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function store(ServiceStoreRequest $request)
    {
        $payload = $request->validated();
        $this->authorize('create', Service::class);

        $service     = new Service($payload);
        $service->id = (string) Str::uuid();
        $service->save();

        // TODO: audit('SERVICE_CREATE', $service)

        return response()->json([
            'data'    => $service,
            'meta'    => [],
            'message' => 'Created',
            'errors'  => null,
        ], 201);
    }

    public function update(ServiceUpdateRequest $request, Service $service)
    {
        $payload = $request->validated();
        $this->authorize('update', $service);

        $service->fill($payload)->save();

        // TODO: audit('SERVICE_UPDATE', $service)

        return response()->json([
            'data'    => $service,
            'meta'    => [],
            'message' => 'Updated',
            'errors'  => null,
        ]);
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);

        if ($service->variants()->exists()) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Produk masih memiliki varian',
                'errors'  => ['variants' => ['has_variants']],
            ], 422);
        }

        if (\App\Models\OrderItem::query()->where('service_id', $service->getKey())->exists()) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Produk sudah pernah dipakai di order dan tidak bisa dihapus. Arsipkan produk ini agar tidak muncul di POS.',
                'errors'  => ['service' => ['has_order_items']],
            ], 422);
        }

        $service->delete();

        // TODO: audit('SERVICE_DELETE', ['id' => $service->id])

        return response()->json([
            'data'    => null,
            'meta'    => [],
            'message' => 'Deleted',
            'errors'  => null,
        ]);
    }
}
