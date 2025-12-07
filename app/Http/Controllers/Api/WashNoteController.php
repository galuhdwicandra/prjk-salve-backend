<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WashNoteStoreRequest;
use App\Http\Requests\WashNoteUpdateRequest;
use App\Models\WashNote;
use App\Models\WashNoteItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WashNoteController extends Controller
{
    // GET /wash-notes
    public function index(Request $request)
    {
        $this->authorize('viewAny', WashNote::class);

        $me = $request->user();
        $q = WashNote::query()
            ->with(['user', 'branch'])
            ->orderByDesc('note_date')
            ->orderByDesc('created_at');

        // Scope role
        if ($me->hasRole('Petugas Cuci')) {
            $q->where('user_id', $me->id);
        } elseif ($me->hasRole('Admin Cabang') && $me->branch_id) {
            $q->where('branch_id', $me->branch_id);
        }

        $from = $request->query('date_from');
        $to   = $request->query('date_to');

        if ($from && $to) {
            $q->whereBetween('note_date', [$from, $to]);
        } elseif ($from) {
            $q->whereDate('note_date', '>=', $from);
        } elseif ($to) {
            $q->whereDate('note_date', '<=', $to);
        }

        $per  = (int) max(1, min(100, (int) $request->query('per_page', 20)));
        $rows = $q->paginate($per);

        $recap = [
            'orders_count' => (int) $rows->sum('orders_count'),
            'total_qty'    => (float) $rows->sum('total_qty'),
        ];

        return response()->json([
            'data' => $rows->items(),
            'meta' => [
                'page'  => $rows->currentPage(),
                'pages' => $rows->lastPage(),
                'total' => $rows->total(),
                'recap' => $recap,
            ],
            'message' => null,
            'errors'  => null,
        ]);
    }

    // GET /wash-notes/{id}
    public function show(WashNote $wash_note)
    {
        $this->authorize('view', $wash_note);
        $wash_note->load(['items.order.customer', 'user', 'branch']);

        return response()->json([
            'data' => $wash_note,
            'meta' => null,
            'message' => null,
            'errors' => null,
        ]);
    }

    // POST /wash-notes
    public function store(WashNoteStoreRequest $request)
    {
        $me = $request->user();
        $data = $request->validated();

        $existing = WashNote::query()
            ->where('user_id', $me->id)
            ->whereDate('note_date', $data['note_date'])
            ->first();

        if ($existing) {
            return response()->json([
                'data' => null,
                'meta' => ['existing_id' => (string) $existing->id],
                'message' => 'Catatan untuk tanggal tersebut sudah ada.',
                'errors' => ['duplicate' => ['note_date already exists for this user']],
            ], 422);
        }

        $note = DB::transaction(function () use ($me, $data) {
            $note = WashNote::create([
                'id' => Str::uuid()->toString(),
                'user_id' => $me->id,
                'branch_id' => $me->branch_id, // boleh null jika petugas di pusat
                'note_date' => $data['note_date'],
                'orders_count' => 0,
                'total_qty' => 0,
            ]);

            $ordersCount = 0;
            $totalQty = 0;

            foreach ($data['items'] as $it) {
                WashNoteItem::create([
                    'id' => Str::uuid()->toString(),
                    'wash_note_id' => $note->id,
                    'order_id' => $it['order_id'],
                    'qty' => number_format((float) ($it['qty'] ?? 0), 2, '.', ''),
                    'process_status' => $it['process_status'] ?? null,
                    'started_at' => $it['started_at'] ?? null,
                    'finished_at' => $it['finished_at'] ?? null,
                    'note' => $it['note'] ?? null,
                ]);
                $ordersCount++;
                $totalQty += (float)($it['qty'] ?? 0);
            }

            $note->update([
                'orders_count' => $ordersCount,
                'total_qty' => number_format((float) $totalQty, 2, '.', ''),
            ]);

            return $note->fresh(['items.order.customer']);
        });

        return response()->json([
            'data' => $note,
            'meta' => null,
            'message' => 'Catatan cuci dibuat.',
            'errors' => null,
        ], 201);
    }

    // PATCH /wash-notes/{wash_note}
    public function update(WashNoteUpdateRequest $request, WashNote $wash_note)
    {
        $data = $request->validated();

        $note = DB::transaction(function () use ($wash_note, $data) {
            // Optional: update header tanggal (tetap pastikan unik per user+date)
            if (isset($data['note_date']) && $data['note_date']) {
                $dup = WashNote::where('user_id', $wash_note->user_id)
                    ->whereDate('note_date', $data['note_date'])
                    ->where('id', '!=', $wash_note->id)
                    ->exists();
                if ($dup) {
                    abort(response()->json([
                        'data' => null,
                        'meta' => null,
                        'message' => 'Tanggal sudah dipakai.',
                        'errors' => ['duplicate' => ['note_date duplicate']],
                    ], 422));
                }
                $wash_note->note_date = $data['note_date'];
            }

            if (isset($data['items'])) {
                // sinkron sederhana: hapus semua lalu tulis ulang (aman & jelas)
                $wash_note->items()->delete();

                $ordersCount = 0;
                $totalQty = 0;
                foreach ($data['items'] as $it) {
                    $wash_note->items()->create([
                        'id' => Str::uuid()->toString(),
                        'order_id' => $it['order_id'],
                        'qty' => number_format((float) ($it['qty'] ?? 0), 2, '.', ''),
                        'process_status' => $it['process_status'] ?? null,
                        'started_at' => $it['started_at'] ?? null,
                        'finished_at' => $it['finished_at'] ?? null,
                        'note' => $it['note'] ?? null,
                    ]);
                    $ordersCount++;
                    $totalQty += (float)($it['qty'] ?? 0);
                }
                $wash_note->orders_count = $ordersCount;
                $wash_note->total_qty = number_format((float) $totalQty, 2, '.', '');
            }

            $wash_note->save();
            return $wash_note->fresh(['items.order.customer']);
        });

        return response()->json([
            'data' => $note,
            'meta' => null,
            'message' => 'Catatan cuci diperbarui.',
            'errors' => null,
        ]);
    }

    // DELETE /wash-notes/{wash_note}
    public function destroy(Request $request, WashNote $wash_note)
    {
        $this->authorize('delete', $wash_note);
        $wash_note->delete();

        return response()->json([
            'data' => null,
            'meta' => null,
            'message' => 'Catatan cuci dihapus.',
            'errors' => null,
        ]);
    }

    // GET /wash-notes/candidates?query=...&date=YYYY-MM-DD
    // pencarian order untuk form tambah
    public function candidates(Request $request)
    {
        $this->authorize('viewAny', WashNote::class);

        $me     = $request->user();
        $query  = trim((string) $request->query('query', ''));
        $from   = $request->query('date_from');
        $to     = $request->query('date_to');
        // ⬅️ baru: tanggal catatan aktif dari klien (fallback ke ?date=)
        $onDate = $request->query('on_date') ?: $request->query('date');

        $q = Order::query()
            ->with(['customer'])
            ->withSum('items as default_qty', 'qty')
            ->orderByDesc('created_at');

        // Petugas Cuci boleh lintas cabang; selain itu batasi cabang
        if (!$me->hasRole('Petugas Cuci') && $me->branch_id) {
            $q->where('branch_id', $me->branch_id);
        }

        if ($query !== '') {
            // Portabel: pgsql → ILIKE, selainnya pakai LOWER(...) LIKE untuk case-insensitive
            if (DB::getDriverName() === 'pgsql') {
                $q->where(function ($w) use ($query) {
                    $w->where('number', 'ilike', "%{$query}%")
                        ->orWhere('invoice_no', 'ilike', "%{$query}%")
                        ->orWhereHas('customer', fn($c) => $c->where('name', 'ilike', "%{$query}%"));
                });
            } else {
                $needle = '%' . mb_strtolower($query) . '%';
                $q->where(function ($w) use ($needle) {
                    $w->whereRaw('LOWER(number) LIKE ?', [$needle])
                        ->orWhereRaw('LOWER(COALESCE(invoice_no, "")) LIKE ?', [$needle])
                        ->orWhereHas('customer', fn($c) => $c->whereRaw('LOWER(name) LIKE ?', [$needle]));
                });
            }
        }

        if ($from && $to) {
            $q->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
        } elseif ($from) {
            $q->where('created_at', '>=', $from . ' 00:00:00');
        } elseif ($to) {
            $q->where('created_at', '<=', $to . ' 23:59:59');
        }


        if (empty($onDate)) {
            $onDate = now()->toDateString();
        }
        $q->whereNotExists(function ($sub) use ($onDate, $me) {
            $sub->from('wash_note_items as wni')
                ->join('wash_notes as wn', 'wn.id', '=', 'wni.wash_note_id')
                ->whereColumn('wni.order_id', 'orders.id')
                ->whereDate('wn.note_date', $onDate);
        });

        $rows = $q->limit(20)->get();

        $rows->transform(function ($o) {
            $o->default_qty = (float) ($o->default_qty ?? 0);
            return $o;
        });

        return response()->json([
            'data'    => $rows,
            'meta'    => [
                'count'   => $rows->count(),
                'on_date' => $onDate,
            ],
            'message' => null,
            'errors'  => null,
        ]);
    }
}
