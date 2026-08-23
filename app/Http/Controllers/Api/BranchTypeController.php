<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchTypeStoreRequest;
use App\Http\Requests\BranchTypeUpdateRequest;
use App\Models\BranchType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BranchTypeController extends Controller
{
    private const LOCKED_CODES = ['workshop'];

    public function index(Request $request)
    {
        $this->authorize('viewAny', BranchType::class);

        $items = BranchType::query()
            ->withCount('branches')
            ->orderBy('name')
            ->paginate((int) $request->query('per_page', 100));

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

    public function store(BranchTypeStoreRequest $request)
    {
        $this->authorize('create', BranchType::class);

        $name = trim($request->validated()['name']);

        $branchType       = new BranchType(['name' => $name]);
        $branchType->id   = (string) Str::uuid();
        $branchType->code = $this->generateCode($name);
        $branchType->save();

        return response()->json([
            'data'    => $branchType,
            'meta'    => [],
            'message' => 'Created',
            'errors'  => null,
        ], 201);
    }

    public function update(BranchTypeUpdateRequest $request, BranchType $branchType)
    {
        $this->authorize('update', $branchType);

        $branchType->fill(['name' => trim($request->validated()['name'])])->save();

        return response()->json([
            'data'    => $branchType,
            'meta'    => [],
            'message' => 'Updated',
            'errors'  => null,
        ]);
    }

    public function destroy(BranchType $branchType)
    {
        $this->authorize('delete', $branchType);

        if (in_array($branchType->code, self::LOCKED_CODES, true)) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Jenis ini dipakai alur sistem dan tidak bisa dihapus.',
                'errors'  => ['code' => ['locked']],
            ], 422);
        }

        if ($branchType->branches()->exists()) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Jenis masih dipakai outlet',
                'errors'  => ['branches' => ['has_branches']],
            ], 422);
        }

        $branchType->delete();

        return response()->json([
            'data'    => null,
            'meta'    => [],
            'message' => 'Deleted',
            'errors'  => null,
        ]);
    }

    private function generateCode(string $name): string
    {
        $base = Str::slug($name, '');

        if ($base === '') {
            $base = 'jenis';
        }

        $base      = substr($base, 0, 28);
        $candidate = $base;
        $suffix    = 2;

        while (BranchType::where('code', $candidate)->exists()) {
            $candidate = $base . $suffix;
            $suffix++;
        }

        return $candidate;
    }
}
