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

        $q = Branch::query()->withCount('orders')->orderBy('code');

        if ($type = $request->query('type')) {
            $q->where('type', $type);
        }

        if (($active = $request->query('is_active')) !== null) {
            $q->where('is_active', filter_var($active, FILTER_VALIDATE_BOOLEAN));
        }

        if ($search = $request->query('q')) {
            $q->where(function ($w) use ($search) {
                $w->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
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

    public function show(Branch $branch)
    {
        $this->authorize('view', $branch);

        return response()->json([
            'data'    => $branch,
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function store(BranchStoreRequest $request)
    {
        $payload = $request->validated();

        $this->authorize('create', Branch::class);

        $code                      = $payload['code'] ?? null;
        $payload['code']           = $code ? strtoupper(trim($code)) : $this->generateCode($payload['name']);
        $payload['invoice_prefix'] = $payload['invoice_prefix'] ?? $this->prefixFromCode($payload['code']);

        $branch     = new Branch($payload);
        $branch->id = (string) Str::uuid();
        $branch->save();

        return response()->json([
            'data'    => $branch,
            'meta'    => [],
            'message' => 'Created',
            'errors'  => null,
        ], 201);
    }

    public function update(BranchUpdateRequest $request, Branch $branch)
    {
        $payload = $request->validated();

        $this->authorize('update', $branch);

        $branch->fill($payload)->save();

        return response()->json([
            'data'    => $branch,
            'meta'    => [],
            'message' => 'Updated',
            'errors'  => null,
        ]);
    }

    public function destroy(Branch $branch)
    {
        $this->authorize('delete', $branch);

        if ($branch->orders()->exists()) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Outlet masih dipakai order. Arsipkan saja.',
                'errors'  => ['orders' => ['has_orders']],
            ], 422);
        }

        $branch->delete();

        return response()->json([
            'data'    => null,
            'meta'    => [],
            'message' => 'Deleted',
            'errors'  => null,
        ]);
    }

    private function generateCode(string $name): string
    {
        $initials = collect(preg_split('/\s+/', trim($name)))
            ->filter()
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->implode('');

        $base = preg_replace('/[^A-Z0-9]/', '', $initials);

        if (strlen($base) < 2) {
            $base = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name) . 'XX', 0, 2));
        }

        $base      = substr($base, 0, 4);
        $candidate = $base;
        $suffix    = 2;
        while (Branch::where('code', $candidate)->exists()) {
            $candidate = $base . $suffix;
            $suffix++;
        }

        return $candidate;
    }

    private function prefixFromCode(string $code): string
    {
        $prefix = preg_replace('/[^A-Z]/', '', strtoupper($code));

        return strlen($prefix) >= 2 ? substr($prefix, 0, 8) : 'SLV';
    }
}
