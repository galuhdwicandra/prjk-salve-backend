<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceCounterStoreRequest;
use App\Http\Requests\InvoiceCounterUpdateRequest;
use App\Models\Branch;
use App\Models\InvoiceCounter;
use App\Services\DocumentNumberService;
use Illuminate\Http\Request;

class InvoiceCounterController extends Controller
{
    public function index(Request $request)
    {
        $branchId = (string) $request->query('branch_id');
        if (! $branchId) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'branch_id is required',
                'errors'  => ['branch_id' => ['required']],
            ], 422);
        }

        $branch = Branch::query()->find($branchId);
        if (! $branch) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Branch not found',
                'errors'  => ['branch_id' => ['not_found']],
            ], 404);
        }

        // Otorisasi: admin_cabang hanya boleh melihat cabangnya
        $this->authorize('view', $branch);

        $items = InvoiceCounter::query()
            ->where('branch_id', $branch->id)
            ->orderBy('prefix')
            ->get();

        return response()->json([
            'data'    => $items,
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function store(InvoiceCounterStoreRequest $request, DocumentNumberService $numbers)
    {
        $payload = $request->validated();
        $branch  = Branch::query()->find($payload['branch_id']);
        if (! $branch) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Branch not found',
                'errors'  => ['branch_id' => ['not_found']],
            ], 404);
        }

        // Otorisasi: hanya yang bisa update/manajemen branch yang boleh menambah counter
        $this->authorize('update', $branch);

        $exists = InvoiceCounter::query()
            ->where('branch_id', $payload['branch_id'])
            ->where('doc_key', $payload['doc_key'])
            ->exists();
        if ($exists) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Document already configured for this branch',
                'errors'  => ['doc_key' => ['unique_for_branch']],
            ], 422);
        }

        $counter = InvoiceCounter::query()->create([
            'branch_id'        => $branch->id,
            'doc_key'          => $payload['doc_key'],
            'prefix'           => $branch->invoice_prefix ?? 'SLV',
            'format'           => $payload['format'],
            'seq'              => $payload['seq'],
            'reset_policy'     => $payload['reset_policy'],
            'last_reset_month' => $numbers->periodKey($payload['reset_policy'], now('Asia/Jakarta')),
        ]);

        return response()->json([
            'data'    => $counter,
            'meta'    => [],
            'message' => 'Created',
            'errors'  => null,
        ], 201);
    }

    public function update(InvoiceCounterUpdateRequest $request, string $id, DocumentNumberService $numbers)
    {
        $counter = InvoiceCounter::query()->find($id);
        if (! $counter) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Counter not found',
                'errors'  => ['id' => ['not_found']],
            ], 404);
        }

        // Otorisasi via cabang
        $branch = $counter->branch;
        $this->authorize('update', $branch);

        $payload = $request->validated();

        $counter->fill($payload);
        $counter->last_reset_month = $numbers->periodKey($counter->reset_policy, now('Asia/Jakarta'));
        $counter->save();

        return response()->json([
            'data'    => $counter,
            'meta'    => [],
            'message' => 'Updated',
            'errors'  => null,
        ]);
    }

    public function destroy(string $id)
    {
        $counter = InvoiceCounter::query()->find($id);
        if (! $counter) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Counter not found',
                'errors'  => ['id' => ['not_found']],
            ], 404);
        }
        $this->authorize('update', $counter->branch);

        // Tambahkan guard jika sudah terpakai di invoice (optional)
        // if ($counter->invoices()->exists()) { return 409 … }

        $counter->delete();
        return response()->json([
            'data'    => null,
            'meta'    => [],
            'message' => 'Deleted',
            'errors'  => null,
        ]);
    }

    public function preview(Request $request, DocumentNumberService $numbers)
    {
        $branchId = (string) $request->query('branch_id');
        if (! $branchId) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'branch_id is required',
                'errors'  => ['branch_id' => ['required']],
            ], 422);
        }

        $branch = Branch::query()->find($branchId);
        if (! $branch) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Branch not found',
                'errors'  => ['branch_id' => ['not_found']],
            ], 404);
        }

        $this->authorize('update', $branch);

        return response()->json([
            'data'    => $numbers->preview($branch),
            'meta'    => ['outlet' => $branch->invoice_prefix ?? 'SLV'],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    /**
     * POST /invoice-counters/{id}/reset-now
     * Reset seq ke 0 dan set last_reset_month ke bulan berjalan.
     * Otorisasi: update pada Branch terkait counter.
     */
    public function resetNow(string $id, DocumentNumberService $numbers)
    {
        /** @var InvoiceCounter|null $counter */
        $counter = InvoiceCounter::query()->find($id);
        if (! $counter) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'InvoiceCounter not found',
                'errors'  => ['id' => ['not_found']],
            ], 404);
        }

        $branch = $counter->branch;
        $this->authorize('update', $branch);

        $counter->seq              = 0;
        $counter->last_reset_month = $numbers->periodKey($counter->reset_policy, now('Asia/Jakarta'));
        $counter->save();

        return response()->json([
            'data'    => $counter->fresh(),
            'meta'    => [],
            'message' => 'Reset OK',
            'errors'  => null,
        ]);
    }
}
