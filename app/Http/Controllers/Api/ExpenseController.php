<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expenses\ExpenseStoreRequest;
use App\Http\Requests\Expenses\ExpenseUpdateRequest;
use App\Models\Expense;
use App\Services\CashLedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function __construct(
        private CashLedgerService $cashLedger,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Expense::class);

        $user = $request->user();

        $query = Expense::query()
            ->with('branch')
            ->orderByDesc('created_at');

        if ($user->hasRole('Superadmin')) {
            if ($branchId = $request->query('branch_id')) {
                $query->where('branch_id', $branchId);
            }
        } else {
            if ($user->branch_id !== null) {
                $query->where('branch_id', $user->branch_id);
            }
        }

        if ($dateFrom = $request->query('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->query('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $perPage = min($request->integer('per_page', 50), 100);

        return $query->paginate($perPage);
    }

    public function store(ExpenseStoreRequest $request)
    {
        $this->authorize('create', Expense::class);

        $user = $request->user();
        $branchId = null;

        if ($user->hasRole('Superadmin')) {
            $branchId = $request->input('branch_id');

            if (!$branchId) {
                return response()->json(['message' => 'branch_id wajib diisi untuk Superadmin.'], 422);
            }
        } else {
            $branchId = $user->branch_id;

            if (!$branchId) {
                return response()->json(['message' => 'User tidak memiliki cabang yang terasosiasi.'], 422);
            }
        }

        $expense = DB::transaction(function () use ($request, $branchId, $user) {
            $data = $request->validated();
            $data['branch_id'] = $branchId;
            $data['payment_source'] = $data['payment_source'] ?? 'NON_CASH';

            if ($request->hasFile('proof')) {
                $storedPath = $request->file('proof')->store('uploads/expenses', 'public');
                $data['proof_path'] = 'storage/' . $storedPath;
            }

            $expense = Expense::create($data);

            $this->cashLedger->syncExpense($expense, $user);

            return $expense;
        });

        return response()->json([
            'data' => $expense->load('branch'),
        ], 201);
    }

    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);

        return response()->json([
            'data' => $expense->load('branch'),
        ]);
    }

    public function update(ExpenseUpdateRequest $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $expense = DB::transaction(function () use ($request, $expense) {
            $data = $request->validated();
            unset($data['branch_id']);

            if ($request->hasFile('proof')) {
                if ($expense->proof_path) {
                    $this->deleteProofFile($expense->proof_path);
                }

                $storedPath = $request->file('proof')->store('uploads/expenses', 'public');
                $data['proof_path'] = 'storage/' . $storedPath;
            }

            $expense->update($data);
            $this->cashLedger->syncExpense($expense->fresh(), $request->user());

            return $expense->fresh();
        });

        return response()->json([
            'data' => $expense->load('branch'),
        ]);
    }

    public function destroy(Request $request, Expense $expense)
    {
        $this->authorize('delete', $expense);

        DB::transaction(function () use ($expense) {
            if ($expense->proof_path) {
                $this->deleteProofFile($expense->proof_path);
            }

            $this->cashLedger->deleteExpenseMutation($expense->id);
            $expense->delete();
        });

        return response()->json([], 204);
    }

    private function deleteProofFile(string $storedPath): void
    {
        $relativePath = preg_replace('#^storage/#', '', $storedPath);
        Storage::disk('public')->delete($relativePath);
    }
}