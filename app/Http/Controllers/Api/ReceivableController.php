<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use App\Models\Receivable;
use App\Models\Order;
use App\Http\Requests\Receivables\ReceivableSettleRequest;
use App\Services\ReceivableService;

class ReceivableController extends Controller
{
    public function index(Request $req)
    {
        $this->authorize('viewAny', Receivable::class);

        $user = $req->user();
        $status = strtoupper((string) $req->query('status', ''));

        $q = Receivable::query()
            ->with(['order' => function ($q) {
                $q->select(['id', 'branch_id', 'customer_id', 'invoice_no', 'grand_total', 'paid_amount', 'due_amount', 'status', 'payment_status', 'created_at']);
            }, 'order.customer:id,name,whatsapp'])
            ->join('orders', 'orders.id', '=', 'receivables.order_id')
            ->select('receivables.*');

        // Filter per cabang (kecuali Superadmin)
        if ($user->branch_id) {
            $q->where('orders.branch_id', $user->branch_id);
        }

        // Filter status
        if (in_array($status, ['OPEN', 'PARTIAL', 'SETTLED', 'CANCELLED'], true)) {
            $q->where('receivables.status', $status);
        } elseif ($status === 'OVERDUE') {
            $today = Carbon::today();
            $q->whereNotNull('receivables.due_date')
                ->where('receivables.due_date', '<', $today->toDateString())
                ->where('receivables.remaining_amount', '>', 0);
        }

        $q->orderByRaw('CASE WHEN receivables.due_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('receivables.due_date', 'asc')
            ->orderBy('receivables.created_at', 'desc');

        $data = $q->paginate((int) $req->query('per_page', 15));

        return response()->json([
            'data' => $data,
            'meta' => (object) [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function settle(ReceivableSettleRequest $req, string $id, ReceivableService $svc)
    {
        $rcv = Receivable::query()->with('order')->findOrFail($id);
        $this->authorize('settle', $rcv);

        $method = $req->input('method');     // CASH|QRIS|TRANSFER
        $amount = (float) $req->input('amount');
        $paidAt = $req->date('paid_at');
        $note   = $req->input('note');

        $result = $svc->settle($rcv->order, $method, $amount, $paidAt, $note);

        $ord = $result['order'];
        $orderId = is_array($ord)
            ? (string) data_get($ord, 'order.id', data_get($ord, 'id'))
            : (string) data_get($ord, 'id');
        // Link internal (butuh auth) untuk staff
        $receiptUrl = $orderId ? url("/api/v1/orders/{$orderId}/receipt") : null;
        // Link publik bertanda tangan untuk dibagikan ke pelanggan (berlaku 120 menit)
        $shareUrl = $orderId
            ? URL::temporarySignedRoute(
                'public.receipts.show',
                now()->addMinutes(120),
                ['order' => $orderId]
            )
            : null;

        return response()->json([
            'data' => [
                'order' => $result['order'],
                'receivable' => $result['receivable'],
                'order_id' => $orderId,
                'receipt_url' => $receiptUrl,
                'share_url' => $shareUrl,
            ],
            'meta' => (object) [],
            'message' => 'Pelunasan berhasil.',
            'errors' => null,
        ]);
    }
}
