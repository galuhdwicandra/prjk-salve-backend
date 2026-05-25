<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\AccountStoreRequest;
use App\Http\Requests\Accounting\AccountUpdateRequest;
use App\Models\AccountingAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', AccountingAccount::class);

        $user = $request->user();

        $query = AccountingAccount::query()
            ->with(['branch:id,name,code', 'parent:id,code,name'])
            ->orderBy('sort_order')
            ->orderBy('code');

        if ($user->hasRole('Superadmin')) {
            if ($branchId = $request->query('branch_id')) {
                $query->where(function ($q) use ($branchId) {
                    $q->whereNull('branch_id')
                        ->orWhere('branch_id', $branchId);
                });
            }
        } else {
            $query->where(function ($q) use ($user) {
                $q->whereNull('branch_id')
                    ->orWhere('branch_id', $user->branch_id);
            });
        }

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        if (($active = $request->query('is_active')) !== null) {
            $query->where('is_active', filter_var($active, FILTER_VALIDATE_BOOLEAN));
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate((int) $request->query('per_page', 20));

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

    public function show(AccountingAccount $account)
    {
        $this->authorize('view', $account);

        return response()->json([
            'data' => $account->load(['branch:id,name,code', 'parent:id,code,name']),
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(AccountStoreRequest $request)
    {
        $this->authorize('create', AccountingAccount::class);

        $payload = $this->payloadWithBranchScope($request->validated(), $request);

        $account = DB::transaction(function () use ($payload) {
            $account = new AccountingAccount($payload);
            $account->id = (string) Str::uuid();
            $account->save();

            return $account;
        });

        return response()->json([
            'data' => $account->load(['branch:id,name,code', 'parent:id,code,name']),
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function update(AccountUpdateRequest $request, AccountingAccount $account)
    {
        $this->authorize('update', $account);

        $payload = $this->payloadWithBranchScope($request->validated(), $request);

        DB::transaction(function () use ($account, $payload) {
            $account->fill($payload)->save();
        });

        return response()->json([
            'data' => $account->refresh()->load(['branch:id,name,code', 'parent:id,code,name']),
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(AccountingAccount $account)
    {
        $this->authorize('delete', $account);

        if ($account->children()->exists()) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Akun tidak dapat dihapus karena masih memiliki akun turunan.',
                'errors' => [
                    'account' => ['Akun masih memiliki akun turunan.'],
                ],
            ], 422);
        }

        if ($account->debitMappings()->exists() || $account->creditMappings()->exists()) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Akun tidak dapat dihapus karena masih digunakan pada mapping akun.',
                'errors' => [
                    'account' => ['Akun masih digunakan pada mapping akun.'],
                ],
            ], 422);
        }

        $account->delete();

        return response()->json([
            'data' => null,
            'meta' => [],
            'message' => 'Deleted',
            'errors' => null,
        ]);
    }

    private function payloadWithBranchScope(array $payload, Request $request): array
    {
        $user = $request->user();

        if ($user->hasRole('Superadmin')) {
            $payload['branch_id'] = $payload['branch_id'] ?? null;
            return $payload;
        }

        $payload['branch_id'] = $user->branch_id;

        return $payload;
    }
}
