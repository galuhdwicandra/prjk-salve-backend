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

        $q = Service::query()->with('category')->orderBy('name');

        if ($s = $request->query('q')) {
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%{$s}%")
                    ->orWhere('unit', 'like', "%{$s}%");
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
            'data' => $items->items(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'last_page' => $items->lastPage(),
            ],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function show(Service $service)
    {
        $this->authorize('view', $service);

        return response()->json([
            'data' => $service->load('category', 'prices'),
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(ServiceStoreRequest $request)
    {
        $payload = $request->validated();
        $this->authorize('create', Service::class);

        $service = new Service($payload);
        $service->id = (string) Str::uuid();
        $service->save();

        // TODO: audit('SERVICE_CREATE', $service)

        return response()->json([
            'data' => $service,
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function update(ServiceUpdateRequest $request, Service $service)
    {
        $payload = $request->validated();
        $this->authorize('update', $service);

        $service->fill($payload)->save();

        // TODO: audit('SERVICE_UPDATE', $service)

        return response()->json([
            'data' => $service,
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);
        $service->delete();

        // TODO: audit('SERVICE_DELETE', ['id' => $service->id])

        return response()->json([
            'data' => null,
            'meta' => [],
            'message' => 'Deleted',
            'errors' => null,
        ]);
    }
}
