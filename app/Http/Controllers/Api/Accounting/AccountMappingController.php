<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\AccountMappingStoreRequest;
use App\Http\Requests\Accounting\AccountMappingUpdateRequest;
use App\Models\AccountingAccountMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccountMappingController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', AccountingAccountMapping::class);

        $user = $request->user();

        $query = AccountingAccountMapping::query()
            ->with([
                'branch:id,name,code',
                'debitAccount:id,code,name,type,normal_balance',
                'creditAccount:id,code,name,type,normal_balance',
            ])
            ->orderBy('event_key');

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

        if ($eventKey = $request->query('event_key')) {
            $query->where('event_key', $eventKey);
        }

        if (($active = $request->query('is_active')) !== null) {
            $query->where('is_active', filter_var($active, FILTER_VALIDATE_BOOLEAN));
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('event_key', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhere('expense_category', 'like', "%{$search}%");
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

    public function show(AccountingAccountMapping $accountMapping)
    {
        $this->authorize('view', $accountMapping);

        return response()->json([
            'data' => $accountMapping->load([
                'branch:id,name,code',
                'debitAccount:id,code,name,type,normal_balance',
                'creditAccount:id,code,name,type,normal_balance',
            ]),
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(AccountMappingStoreRequest $request)
    {
        $this->authorize('create', AccountingAccountMapping::class);

        $payload = $this->payloadWithBranchScope($request->validated(), $request);

        $duplicate = $this->duplicateExists($payload);

        if ($duplicate) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Mapping akun dengan kombinasi event, metode bayar, kategori, dan cabang tersebut sudah ada.',
                'errors' => [
                    'event_key' => ['Mapping akun sudah ada.'],
                ],
            ], 422);
        }

        $mapping = DB::transaction(function () use ($payload) {
            $mapping = new AccountingAccountMapping($payload);
            $mapping->id = (string) Str::uuid();
            $mapping->save();

            return $mapping;
        });

        return response()->json([
            'data' => $mapping->load([
                'branch:id,name,code',
                'debitAccount:id,code,name,type,normal_balance',
                'creditAccount:id,code,name,type,normal_balance',
            ]),
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function update(AccountMappingUpdateRequest $request, AccountingAccountMapping $accountMapping)
    {
        $this->authorize('update', $accountMapping);

        $payload = $this->payloadWithBranchScope($request->validated(), $request);

        $duplicate = $this->duplicateExists($payload, (string) $accountMapping->id);

        if ($duplicate) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Mapping akun dengan kombinasi event, metode bayar, kategori, dan cabang tersebut sudah ada.',
                'errors' => [
                    'event_key' => ['Mapping akun sudah ada.'],
                ],
            ], 422);
        }

        DB::transaction(function () use ($accountMapping, $payload) {
            $accountMapping->fill($payload)->save();
        });

        return response()->json([
            'data' => $accountMapping->refresh()->load([
                'branch:id,name,code',
                'debitAccount:id,code,name,type,normal_balance',
                'creditAccount:id,code,name,type,normal_balance',
            ]),
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(AccountingAccountMapping $accountMapping)
    {
        $this->authorize('delete', $accountMapping);

        $accountMapping->delete();

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

    private function duplicateExists(array $payload, ?string $ignoreId = null): bool
    {
        $query = AccountingAccountMapping::query()
            ->where('event_key', $payload['event_key'])
            ->where('branch_id', $payload['branch_id'] ?? null)
            ->where('payment_method', $payload['payment_method'] ?? null)
            ->where('expense_category', $payload['expense_category'] ?? null);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
