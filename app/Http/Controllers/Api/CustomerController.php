<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Requests\CustomerSearchWARequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Customer::class);

        $q = Customer::query()->orderByDesc('created_at');

        // Scope cabang
        if ($branchId = $this->branchScopeFor($request)) {
            $q->where('branch_id', $branchId);
        }

        if ($s = $request->query('q')) {
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%{$s}%")
                    ->orWhere('whatsapp', 'like', "%{$s}%")
                    ->orWhere('address', 'like', "%{$s}%");
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

    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);

        return response()->json([
            'data' => $customer,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(CustomerStoreRequest $request)
    {
        $payload = $request->validated();

        // Admin Cabang: paksa branch_id miliknya bila kosong
        if ($request->user()->hasRole('Admin Cabang') || $request->user()->hasRole('Kasir')) {
            $payload['branch_id'] = $payload['branch_id'] ?? $request->user()->branch_id;
        }

        $this->authorize('create', Customer::class);

        /** @var Customer $customer */
        $customer = DB::transaction(function () use ($payload) {
            $c = new Customer($payload);
            $c->id = (string) Str::uuid();
            $c->save();

            // TODO: audit('CUSTOMER_CREATE', $c)

            return $c;
        });

        return response()->json([
            'data' => $customer,
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        $payload = $request->validated();
        $this->authorize('update', $customer);

        DB::transaction(function () use ($customer, $payload) {
            $customer->fill($payload)->save();
            // TODO: audit('CUSTOMER_UPDATE', $customer)
        });

        return response()->json([
            'data' => $customer->refresh(),
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(Request $request, Customer $customer)
    {
        $this->authorize('delete', $customer);

        DB::transaction(function () use ($customer) {
            $customer->delete();
            // TODO: audit('CUSTOMER_DELETE', ['id' => $customer->id])
        });

        return response()->json([
            'data' => null,
            'meta' => [],
            'message' => 'Deleted',
            'errors' => null,
        ]);
    }

    /** GET /customers/search-wa?wa=... */
    public function searchByWhatsapp(CustomerSearchWARequest $request)
    {
        $this->authorize('viewAny', Customer::class);

        $wa = preg_replace('/\s+/', '', (string) $request->query('wa'));
        $branchId = $this->branchScopeFor($request);

        $q = Customer::query()->where('whatsapp', $wa);
        if ($branchId) {
            $q->where('branch_id', $branchId);
        }

        $found = $q->first();

        if (!$found) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Not found',
                'errors' => ['wa' => ['not_found']],
            ], 404);
        }

        return response()->json([
            'data' => $found,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    private function branchScopeFor(Request $request): ?string
    {
        $me = $request->user();
        if ($me->hasRole('Superadmin')) {
            return (string) $request->query('branch_id') ?: null;
        }
        if (($me->hasRole('Admin Cabang') || $me->hasRole('Kasir')) && $me->branch_id) {
            return (string) $me->branch_id;
        }
        return null;
    }
}
