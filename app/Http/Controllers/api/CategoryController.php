<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', ServiceCategory::class);

        $q = ServiceCategory::query()->orderBy('name');
        if ($s = $request->query('q')) {
            $q->where('name', 'like', "%{$s}%");
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

    public function show(ServiceCategory $category)
    {
        $this->authorize('view', $category);

        return response()->json([
            'data' => $category,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(CategoryStoreRequest $request)
    {
        $payload = $request->validated();
        $this->authorize('create', ServiceCategory::class);

        $category = new ServiceCategory($payload);
        $category->id = (string) Str::uuid();
        $category->save();

        // TODO: audit('CATEGORY_CREATE', $category)

        return response()->json([
            'data' => $category,
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function update(CategoryUpdateRequest $request, ServiceCategory $category)
    {
        $payload = $request->validated();
        $this->authorize('update', $category);

        $category->fill($payload)->save();

        // TODO: audit('CATEGORY_UPDATE', $category)

        return response()->json([
            'data' => $category,
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(ServiceCategory $category)
    {
        $this->authorize('delete', $category);
        $category->delete();

        // TODO: audit('CATEGORY_DELETE', ['id' => $category->id])

        return response()->json([
            'data' => null,
            'meta' => [],
            'message' => 'Deleted',
            'errors' => null,
        ]);
    }
}
