<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerSearchWARequest;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyLog;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Customer::class);

        $notVoid = fn($o) => $o->where('status', '!=', 'CANCELED');

        $q = Customer::query()
            ->with('branch:id,code,name')
            ->withCount(['orders as visits_count' => $notVoid])
            ->withSum(['orders as spend_total' => $notVoid], 'grand_total')
            ->withMax(['orders as last_order_at' => $notVoid], 'created_at');

        $branchId = $this->branchScopeFor($request);
        if ($branchId) {
            $q->where('branch_id', $branchId);
        }

        $q->where('is_active', filter_var($request->query('is_active', true), FILTER_VALIDATE_BOOLEAN));

        if ($s = $request->query('q')) {
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%{$s}%")
                    ->orWhere('whatsapp', 'like', "%{$s}%")
                    ->orWhere('address', 'like', "%{$s}%")
                    ->orWhere('notes', 'like', "%{$s}%")
                    ->orWhereJsonContains('tags', $s);
            });
        }

        $visitsOp = (string) $request->query('visits_op');
        $visits   = $request->query('visits');
        if (is_numeric($visits) && in_array($visitsOp, ['gte', 'lte'], true)) {
            $q->whereHas('orders', $notVoid, $visitsOp === 'gte' ? '>=' : '<=', (int) $visits);
        }

        $sortMap = [
            'name'       => 'name',
            'wa'         => 'whatsapp',
            'created_at' => 'created_at',
            'visits'     => 'visits_count',
            'spend'      => 'spend_total',
            'last_order' => 'last_order_at',
        ];
        $sortBy  = $sortMap[(string) $request->query('sort_by')] ?? 'created_at';
        $sortDir = strtolower((string) $request->query('sort_dir')) === 'asc' ? 'asc' : 'desc';
        if ((string) $request->query('sort_by') === 'branch') {
            $q->orderBy(Branch::select('code')->whereColumn('branches.id', 'customers.branch_id'), $sortDir);
        } else {
            $q->orderBy($sortBy, $sortDir);
        }

        $items = $q->paginate((int) $request->query('per_page', 10));

        $activeTotal = Customer::query()
            ->when($branchId, fn($w) => $w->where('branch_id', $branchId))
            ->where('is_active', true)
            ->count();

        return response()->json([
            'data'    => $items->items(),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
                'last_page'    => $items->lastPage(),
                'active_total' => $activeTotal,
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);

        $notVoid = fn($o) => $o->where('status', '!=', 'CANCELED');

        $customer->load('branch')
            ->loadCount(['orders as visits_count' => $notVoid])
            ->loadSum(['orders as spend_total' => $notVoid], 'grand_total')
            ->loadMax(['orders as last_order_at' => $notVoid], 'created_at');

        $vouchers = DB::table('order_vouchers')
            ->join('orders', 'orders.id', '=', 'order_vouchers.order_id')
            ->join('vouchers', 'vouchers.id', '=', 'order_vouchers.voucher_id')
            ->where('orders.customer_id', (string) $customer->getKey())
            ->orderByDesc('order_vouchers.applied_at')
            ->get([
                'vouchers.code as code',
                'order_vouchers.applied_amount as applied_amount',
                'order_vouchers.applied_at as applied_at',
                'orders.number as number',
            ]);

        return response()->json([
            'data'    => $customer,
            'meta'    => ['vouchers' => $vouchers],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function store(CustomerStoreRequest $request)
    {
        $payload = $request->validated();

        if ($request->user()->hasRole('Admin Cabang') || $request->user()->hasRole('Kasir')) {
            $payload['branch_id'] = (string) $request->user()->branch_id;
        }

        $this->authorize('create', Customer::class);

        if (! empty($payload['branch_id'])) {
            $archived = Customer::query()
                ->where('branch_id', $payload['branch_id'])
                ->where('whatsapp', $payload['whatsapp'])
                ->where('is_active', false)
                ->first();

            if ($archived) {
                $archived->fill($payload)->forceFill(['is_active' => true])->save();

                return response()->json([
                    'data'    => $archived->load('branch'),
                    'meta'    => [],
                    'message' => 'Restored',
                    'errors'  => null,
                ]);
            }
        }

        $customer = DB::transaction(function () use ($payload) {
            $c     = new Customer($payload);
            $c->id = (string) Str::uuid();
            $c->save();
            return $c;
        });

        return response()->json([
            'data'    => $customer->load('branch'),
            'meta'    => [],
            'message' => 'Created',
            'errors'  => null,
        ], 201);
    }

    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        $payload = $request->validated();
        $this->authorize('update', $customer);

        DB::transaction(function () use ($customer, $payload, $request) {
            if ($request->user()->hasRole('Admin Cabang') || $request->user()->hasRole('Kasir')) {
                $payload['branch_id'] = (string) $request->user()->branch_id;
            }

            $customer->fill($payload)->save();

            if (array_key_exists('name', $payload)) {
                Order::query()
                    ->where('customer_id', (string) $customer->getKey())
                    ->update(['customer_name' => $customer->name]);
            }
        });

        return response()->json([
            'data'    => $customer->refresh()->load('branch'),
            'meta'    => [],
            'message' => 'Updated',
            'errors'  => null,
        ]);
    }

    public function destroy(Request $request, Customer $customer)
    {
        $this->authorize('delete', $customer);

        DB::transaction(function () use ($customer) {
            $customerId = (string) $customer->getKey();

            LoyaltyLog::query()
                ->where('customer_id', $customerId)
                ->delete();

            LoyaltyAccount::query()
                ->where('customer_id', $customerId)
                ->delete();

            $customer->delete();
        });

        return response()->json([
            'data'    => null,
            'meta'    => [],
            'message' => 'Deleted',
            'errors'  => null,
        ]);
    }

    /** GET /customers/search-wa?wa=... */
    public function searchByWhatsapp(CustomerSearchWARequest $request)
    {
        $this->authorize('viewAny', Customer::class);

        $wa       = preg_replace('/\D+/', '', (string) $request->query('wa'));
        $branchId = $this->branchScopeFor($request);

        $q = Customer::query()->where('whatsapp', $wa);
        $q->where('is_active', true);
        if ($branchId) {
            $q->where('branch_id', $branchId);
        }

        $found = $q->first();

        if (! $found) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Not found',
                'errors'  => ['wa' => ['not_found']],
            ], 404);
        }

        return response()->json([
            'data'    => $found,
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
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
