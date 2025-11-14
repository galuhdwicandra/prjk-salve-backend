<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceCounterStoreRequest;
use App\Http\Requests\InvoiceCounterUpdateRequest;
use App\Models\Branch;
use App\Models\InvoiceCounter;
use Illuminate\Http\Request;

class InvoiceCounterController extends Controller
{
    public function index(Request $request)
    {
        $branchId = (string) $request->query('branch_id');
        if (!$branchId) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'branch_id is required',
                'errors' => ['branch_id' => ['required']],
            ], 422);
        }

        $branch = Branch::query()->find($branchId);
        if (!$branch) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Branch not found',
                'errors' => ['branch_id' => ['not_found']],
            ], 404);
        }

        // Otorisasi: admin_cabang hanya boleh melihat cabangnya
        $this->authorize('view', $branch);

        $items = InvoiceCounter::query()
            ->where('branch_id', $branch->id)
            ->orderBy('prefix')
            ->get();

        return response()->json([
            'data' => $items,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(InvoiceCounterStoreRequest $request)
    {
        $payload = $request->validated();
        $branch = Branch::query()->find($payload['branch_id']);
        if (!$branch) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Branch not found',
                'errors' => ['branch_id' => ['not_found']],
            ], 404);
        }

        // Otorisasi: hanya yang bisa update/manajemen branch yang boleh menambah counter
        $this->authorize('update', $branch);

        // Unik per (branch_id, prefix)
        $exists = InvoiceCounter::query()
            ->where('branch_id', $payload['branch_id'])
            ->where('prefix', $payload['prefix'])
            ->exists();
        if ($exists) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Prefix already exists for this branch',
                'errors' => ['prefix' => ['unique_for_branch']],
            ], 422);
        }

        $counter = InvoiceCounter::query()->create([
            'branch_id' => $payload['branch_id'],
            'prefix' => strtoupper($payload['prefix']),
            'seq' => 0,            // mulai dari 0; akan naik saat dipakai
            'reset_policy' => $payload['reset_policy'],
            'last_reset_month' => null,         // biar generator yang set saat first use/monthly reset
        ]);

        return response()->json([
            'data' => $counter,
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }


    public function update(InvoiceCounterUpdateRequest $request, string $id)
    {
        $counter = InvoiceCounter::query()->find($id);
        if (!$counter) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Counter not found',
                'errors' => ['id' => ['not_found']],
            ], 404);
        }

        // Otorisasi via cabang
        $branch = $counter->branch;
        $this->authorize('update', $branch);

        $payload = $request->validated();

        // Pastikan unique (branch_id, prefix) bila prefix berubah
        if (isset($payload['prefix']) && $payload['prefix'] !== $counter->prefix) {
            $exists = InvoiceCounter::query()
                ->where('branch_id', $counter->branch_id)
                ->where('prefix', $payload['prefix'])
                ->exists();
            if ($exists) {
                return response()->json([
                    'data' => null,
                    'meta' => [],
                    'message' => 'Prefix already exists for this branch',
                    'errors' => ['prefix' => ['unique_for_branch']],
                ], 422);
            }
        }

        $counter->fill($payload)->save();

        return response()->json([
            'data' => $counter,
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(string $id)
    {
        $counter = InvoiceCounter::query()->find($id);
        if (!$counter) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Counter not found',
                'errors' => ['id' => ['not_found']],
            ], 404);
        }
        $this->authorize('update', $counter->branch);

        // Tambahkan guard jika sudah terpakai di invoice (optional)
        // if ($counter->invoices()->exists()) { return 409 … }

        $counter->delete();
        return response()->json([
            'data' => null,
            'meta' => [],
            'message' => 'Deleted',
            'errors' => null,
        ]);
    }
}
