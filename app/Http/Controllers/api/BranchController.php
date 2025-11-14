<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchStoreRequest;
use App\Http\Requests\BranchUpdateRequest;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Branch::class);

        $q = Branch::query()->orderBy('created_at', 'desc');

        if ($search = $request->query('q')) {
            $q->where(function ($w) use ($search) {
                $w->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
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

    public function show(Branch $branch)
    {
        $this->authorize('view', $branch);

        return response()->json([
            'data' => $branch,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(BranchStoreRequest $request)
    {
        $payload = $request->validated();

        $this->authorize('create', Branch::class);

        $branch = new Branch($payload);
        $branch->id = (string) Str::uuid();
        $branch->save();

        return response()->json([
            'data' => $branch,
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function update(BranchUpdateRequest $request, Branch $branch)
    {
        $payload = $request->validated();

        $this->authorize('update', $branch);

        $branch->fill($payload)->save();

        return response()->json([
            'data' => $branch,
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(Branch $branch)
    {
        $this->authorize('delete', $branch);

        $branch->delete();

        return response()->json([
            'data' => null,
            'meta' => [],
            'message' => 'Deleted',
            'errors' => null,
        ]);
    }
}
