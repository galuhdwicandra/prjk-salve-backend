<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethods\PaymentMethodAccountSetRequest;
use App\Http\Requests\PaymentMethods\PaymentMethodRequest;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentMethod::query()
            ->with($this->accountsRelation($request))
            ->orderBy('sort_order')
            ->orderBy('code');

        if (($active = $request->query('is_active')) !== null) {
            $query->where('is_active', filter_var($active, FILTER_VALIDATE_BOOLEAN));
        }

        return response()->json([
            'data'    => $query->get(),
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function show(Request $request, PaymentMethod $paymentMethod)
    {
        return response()->json([
            'data'    => $paymentMethod->load($this->accountsRelation($request)),
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function store(PaymentMethodRequest $request)
    {
        $payload         = $request->validated();
        $payload['code'] = $payload['code'] ?? $this->generateCode($payload['name']);

        $paymentMethod = PaymentMethod::create($payload);

        return response()->json([
            'data'    => $paymentMethod,
            'meta'    => [],
            'message' => 'Created',
            'errors'  => null,
        ], 201);
    }

    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->fill($request->validated())->save();

        return response()->json([
            'data'    => $paymentMethod->refresh()->load($this->accountsRelation($request)),
            'meta'    => [],
            'message' => 'Updated',
            'errors'  => null,
        ]);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if (Payment::query()->where('method', $paymentMethod->code)->exists()) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Metode pembayaran tidak dapat dihapus karena sudah dipakai pada transaksi.',
                'errors'  => [
                    'code' => ['Metode pembayaran sudah dipakai pada transaksi.'],
                ],
            ], 422);
        }

        $paymentMethod->delete();

        return response()->json([
            'data'    => null,
            'meta'    => [],
            'message' => 'Deleted',
            'errors'  => null,
        ]);
    }

    public function setAccount(PaymentMethodAccountSetRequest $request, PaymentMethod $paymentMethod)
    {
        $payload = $request->validated();
        $user    = $request->user();

        if (! $user->hasAnyRole(['Superadmin', 'Akuntansi'])
            && (string) $user->branch_id !== (string) $payload['branch_id']) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Forbidden',
                'errors'  => ['branch_id' => ['restricted_to_own_branch']],
            ], 403);
        }

        if (empty($payload['account_id'])) {
            PaymentMethodAccount::query()
                ->where('payment_method_id', $paymentMethod->id)
                ->where('branch_id', $payload['branch_id'])
                ->delete();

            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'OK',
                'errors'  => null,
            ]);
        }

        $row = PaymentMethodAccount::updateOrCreate(
            [
                'payment_method_id' => $paymentMethod->id,
                'branch_id'         => $payload['branch_id'],
            ],
            ['account_id' => $payload['account_id']]
        );

        return response()->json([
            'data'    => $row->load(['branch:id,name,code', 'account:id,code,name,type,is_cash_account']),
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

        private function generateCode(string $name): string
    {
        $base = substr(strtoupper(Str::slug($name, '_')), 0, 26) ?: 'METODE';
        $code = $base;
        $suffix = 1;
        while (PaymentMethod::query()->where('code', $code)->exists()) {
           $code = $base.'_'.(++$suffix);
       }
       return $code;
   }

    private function accountsRelation(Request $request): array
    {
        $user     = $request->user();
        $branchId = $request->query('branch_id');

        if (! $user->hasAnyRole(['Superadmin', 'Akuntansi'])) {
            $branchId = $user->branch_id;
        }

        return [
            'accounts' => function ($query) use ($branchId) {
                $query->with([
                    'branch:id,name,code',
                    'account:id,code,name,type,is_cash_account',
                ]);

                if ($branchId) {
                    $query->where('branch_id', $branchId);
                }
            },
        ];
    }
}
