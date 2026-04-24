<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashSessionCloseRequest;
use App\Http\Requests\CashSessionOpenRequest;
use App\Http\Requests\CashWithdrawalRequest;
use App\Http\Requests\CashSessionUpdateRequest;
use App\Models\CashSession;
use App\Services\CashLedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CashSessionController extends Controller
{
    public function __construct(private CashLedgerService $cash)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $this->authorizeManager($user);

        $q = CashSession::query()
            ->with(['branch:id,name', 'opener:id,name', 'closer:id,name'])
            ->orderByDesc('business_date')
            ->orderByDesc('created_at');

        if ($user->hasRole('Superadmin')) {
            if ($branchId = $request->query('branch_id')) {
                $q->where('branch_id', $branchId);
            }
        } else {
            $q->where('branch_id', $user->branch_id);
        }

        if ($status = $request->query('status')) {
            $q->where('status', $status);
        }

        if ($from = $request->query('date_from')) {
            $q->whereDate('business_date', '>=', $from);
        }
        if ($to = $request->query('date_to')) {
            $q->whereDate('business_date', '<=', $to);
        }

        $items = $q->paginate((int) $request->query('per_page', 20));

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

    public function show(CashSession $cashSession)
    {
        $this->authorizeSession($cashSession);

        $cashSession->load([
            'branch:id,name',
            'opener:id,name',
            'closer:id,name',
            'mutations' => fn ($q) => $q->orderByDesc('effective_at')->orderByDesc('created_at'),
            'mutations.creator:id,name',
        ]);

        return response()->json([
            'data' => $cashSession,
            'meta' => [
                'system_closing' => $this->cash->computeSystemClosing($cashSession->id),
            ],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function today(Request $request)
    {
        $user = $request->user();
        $this->authorizeCashTodayViewer($user);

        $q = CashSession::query()
            ->with([
                'branch:id,name',
                'opener:id,name',
                'closer:id,name',
                'mutations' => fn ($mq) => $mq->orderByDesc('effective_at')->orderByDesc('created_at'),
                'mutations.creator:id,name',
            ])
            ->orderByDesc('business_date')
            ->orderByDesc('created_at');

        if ($user->hasRole('Superadmin')) {
            if ($branchId = $request->query('branch_id')) {
                $q->where('branch_id', $branchId);
            }
        } else {
            if (!$user->branch_id) {
                return response()->json([
                    'data' => null,
                    'meta' => [
                        'system_closing' => 0,
                        'cash_in_total' => 0,
                        'cash_out_total' => 0,
                        'withdrawal_total' => 0,
                        'has_open_session' => false,
                    ],
                    'message' => 'User belum terikat ke cabang.',
                    'errors' => null,
                ], 200);
            }

            $q->where('branch_id', $user->branch_id);
        }

        $businessDate = $request->query('business_date')
            ? Carbon::parse((string) $request->query('business_date'))->toDateString()
            : now('Asia/Jakarta')->toDateString();

        $session = $q->whereDate('business_date', $businessDate)->first();

        if (!$session) {
            return response()->json([
                'data' => null,
                'meta' => [
                    'system_closing' => 0,
                    'cash_in_total' => 0,
                    'cash_out_total' => 0,
                    'withdrawal_total' => 0,
                    'has_open_session' => false,
                    'business_date' => $businessDate,
                ],
                'message' => 'Belum ada sesi kas untuk tanggal ini.',
                'errors' => null,
            ]);
        }

        $systemClosing = (float) $this->cash->computeSystemClosing($session->id);

        $mutations = $session->mutations ?? collect();

        $cashInTotal = (float) $mutations
            ->where('direction', 'IN')
            ->sum('amount');

        $cashOutTotal = (float) $mutations
            ->where('direction', 'OUT')
            ->sum('amount');

        $withdrawalTotal = (float) $mutations
            ->where('type', 'WITHDRAWAL')
            ->sum('amount');

        return response()->json([
            'data' => $session,
            'meta' => [
                'system_closing' => $systemClosing,
                'cash_in_total' => $cashInTotal,
                'cash_out_total' => $cashOutTotal,
                'withdrawal_total' => $withdrawalTotal,
                'has_open_session' => (string) $session->status === 'OPEN',
                'business_date' => $businessDate,
            ],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function open(CashSessionOpenRequest $request)
    {
        $user = $request->user();
        $this->authorizeManager($user);

        $branchId = $user->hasRole('Superadmin')
            ? (string) $request->validated('branch_id')
            : (string) $user->branch_id;

        $businessDate = Carbon::parse($request->validated('business_date'))->startOfDay();

        $session = $this->cash->openSession(
            $branchId,
            $businessDate,
            (float) $request->validated('opening_cash'),
            $user,
            $request->validated('notes')
        );

        return response()->json([
            'data' => $session,
            'meta' => null,
            'message' => 'Cash session opened',
            'errors' => null,
        ], 201);
    }

    public function update(CashSessionUpdateRequest $request, CashSession $cashSession)
    {
        $this->authorizeSession($cashSession);

        if ((string) $cashSession->status !== 'OPEN') {
            return response()->json([
                'data' => null,
                'meta' => null,
                'message' => 'Hanya sesi kas yang masih OPEN yang dapat diedit.',
                'errors' => [
                    'cash_session' => ['Hanya sesi kas yang masih OPEN yang dapat diedit.'],
                ],
            ], 422);
        }

        $session = $this->cash->updateSession(
            $cashSession,
            (float) $request->validated('opening_cash'),
            $request->user(),
            $request->validated('notes')
        );

        return response()->json([
            'data' => $session,
            'meta' => [
                'system_closing' => $this->cash->computeSystemClosing($session->id),
            ],
            'message' => 'Cash session updated',
            'errors' => null,
        ]);
    }
    public function close(CashSessionCloseRequest $request, CashSession $cashSession)
    {
        $this->authorizeSession($cashSession);

        $session = $this->cash->closeSession(
            $cashSession,
            (float) $request->validated('closing_cash_counted'),
            $request->user(),
            $request->validated('notes')
        );

        return response()->json([
            'data' => $session,
            'meta' => null,
            'message' => 'Cash session closed',
            'errors' => null,
        ]);
    }

        public function reopen(Request $request, CashSession $cashSession)
    {
        $this->authorizeSession($cashSession);

        $session = $this->cash->reopenSession(
            $cashSession,
            $request->user()
        );

        return response()->json([
            'data' => $session,
            'meta' => [
                'system_closing' => $this->cash->computeSystemClosing($session->id),
            ],
            'message' => 'Cash session reopened',
            'errors' => null,
        ]);
    }

    public function withdraw(CashWithdrawalRequest $request, CashSession $cashSession)
    {
        $this->authorizeSession($cashSession);

        $mutation = $this->cash->createWithdrawal(
            $cashSession,
            (float) $request->validated('amount'),
            $request->user(),
            $request->filled('effective_at') ? Carbon::parse($request->validated('effective_at')) : null,
            $request->validated('note')
        );

        return response()->json([
            'data' => $mutation,
            'meta' => null,
            'message' => 'Withdrawal recorded',
            'errors' => null,
        ], 201);
    }

    private function authorizeManager($user): void
    {
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin Cabang')) {
            abort(403, 'Anda tidak memiliki izin untuk mengelola cash box.');
        }
    }

    private function authorizeCashTodayViewer($user): void
    {
        if (
            !$user->hasRole('Superadmin')
            && !$user->hasRole('Admin Cabang')
            && !$user->hasRole('Kasir')
        ) {
            abort(403, 'Anda tidak memiliki izin untuk melihat ringkasan kas hari ini.');
        }
    }

    private function authorizeSession(CashSession $cashSession): void
    {
        $user = request()->user();
        $this->authorizeManager($user);

        if ($user->hasRole('Superadmin')) {
            return;
        }

        if ((string) $cashSession->branch_id !== (string) $user->branch_id) {
            abort(403, 'Anda tidak memiliki akses ke sesi kas cabang ini.');
        }
    }
}
