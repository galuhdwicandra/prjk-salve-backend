<?php
namespace App\Services;

use App\Models\Order;
use App\Models\ProductionTask;
use App\Models\ProductionTaskCorrectionRequest;
use App\Models\ProductionTaskLog;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductionTaskService
{
    public const STATUSES = [
        'QUEUE',
        'WASHING',
        'DRYING',
        'IRONING',
        'READY',
        'PICKED_UP',
        'CANCELED',
    ];

    public const BOARD_STATUSES = [
        'QUEUE',
        'WASHING',
        'DRYING',
        'IRONING',
        'READY',
    ];

    public const CORRECTION_TYPES = [
        'REWASH',
        'ROLLBACK',
    ];

    public const CORRECTION_STATUSES = [
        'PENDING',
        'APPROVED',
        'REJECTED',
    ];

    public function syncOpenOrdersToTasks(?string $branchId = null): void
    {
        Order::query()
            ->with('items:id,order_id,qty')
            ->whereIn('status', self::STATUSES)
            ->when($branchId, fn($query) => $query->where('branch_id', $branchId))
            ->whereDoesntHave('productionTask')
            ->chunkById(100, function (Collection $orders) {
                foreach ($orders as $order) {
                    $status = in_array((string) $order->status, self::STATUSES, true)
                        ? (string) $order->status
                        : 'QUEUE';

                    ProductionTask::query()->create([
                        'order_id'       => (string) $order->id,
                        'branch_id'      => (string) $order->branch_id,
                        'assigned_to'    => null,
                        'current_status' => $status,
                        'qty'            => $this->calculateOrderQty($order),
                        'started_date'   => null,
                        'finished_date'  => in_array($status, ['READY', 'PICKED_UP'], true)
                            ? now('Asia/Jakarta')->toDateString()
                            : null,
                        'note'           => null,
                    ]);
                }
            });
    }

    public function start(Order $order, User $user, ?string $note = null): ProductionTask
    {
        return DB::transaction(function () use ($order, $user, $note) {
            $task = $this->findOrCreateTask($order);
            $this->ensureTaskBranchAllowed($task, $user);

            if ($task->assigned_to !== null && (string) $task->assigned_to !== (string) $user->id && ! $this->isManager($user)) {
                throw ValidationException::withMessages([
                    'assigned_to' => ['Cucian ini sudah diambil oleh petugas lain.'],
                ]);
            }

            $fromStatus = $task->current_status;
            $today      = now('Asia/Jakarta')->toDateString();

            $task->fill([
                'assigned_to'    => $task->assigned_to ?: $user->id,
                'current_status' => 'WASHING',
                'started_date'   => $task->started_date ?: $today,
                'finished_date'  => null,
                'qty'            => $this->calculateOrderQty($order),
                'note'           => $note,
            ])->save();

            $this->syncOrderStatusFromProduction($order, 'WASHING');

            $this->writeLog($task, $user, $fromStatus, 'WASHING', $note);

            return $task->refresh()->load($this->taskRelations());
        });
    }

    public function move(Order $order, User $user, string $toStatus, ?string $note = null): ProductionTask
    {
        if (! in_array($toStatus, self::STATUSES, true)) {
            throw ValidationException::withMessages([
                'to_status' => ['Status tujuan tidak valid.'],
            ]);
        }

        return DB::transaction(function () use ($order, $user, $toStatus, $note) {
            $task = $this->findOrCreateTask($order);
            $this->ensureTaskBranchAllowed($task, $user);
            $this->ensureAssignedOrManager($task, $user);

            $fromStatus = $task->current_status;
            $today      = now('Asia/Jakarta')->toDateString();

            $payload = [
                'current_status' => $toStatus,
                'qty'            => $this->calculateOrderQty($order),
                'note'           => $note,
            ];

            if ($task->started_date === null && $toStatus !== 'QUEUE') {
                $payload['started_date'] = $today;
            }

            if (in_array($toStatus, ['READY', 'PICKED_UP'], true)) {
                $payload['finished_date'] = $task->finished_date ?: $today;
            }

            if (! in_array($toStatus, ['READY', 'PICKED_UP'], true)) {
                $payload['finished_date'] = null;
            }

            $task->fill($payload)->save();

            $this->syncOrderStatusFromProduction($order, $toStatus);

            $this->writeLog($task, $user, $fromStatus, $toStatus, $note);

            return $task->refresh()->load($this->taskRelations());
        });
    }

    public function finish(Order $order, User $user, ?string $note = null): ProductionTask
    {
        return $this->move($order, $user, 'READY', $note);
    }

    public function createCorrectionRequest(
        Order $order,
        User $user,
        string $type,
        string $reason,
        bool $direct = false
    ): ProductionTask | ProductionTaskCorrectionRequest {
        return DB::transaction(function () use ($order, $user, $type, $reason, $direct) {
            $task = $this->findOrCreateTask($order);
            $this->ensureTaskBranchAllowed($task, $user);

            $type = strtoupper($type);

            if (! in_array($type, self::CORRECTION_TYPES, true)) {
                throw ValidationException::withMessages([
                    'type' => ['Jenis koreksi tidak valid.'],
                ]);
            }

            if (trim($reason) === '') {
                throw ValidationException::withMessages([
                    'reason' => ['Catatan alasan wajib diisi.'],
                ]);
            }

            $fromStatus = (string) $task->current_status;
            $toStatus   = $this->targetStatusForCorrection($type, $fromStatus);

            if ($direct) {
                if (! $this->isManager($user)) {
                    abort(403, 'Hanya Superadmin atau Admin Cabang yang dapat menjalankan koreksi langsung.');
                }

                return $this->applyCorrection(
                    $task,
                    $user,
                    $toStatus,
                    "[{$type}] {$reason}"
                );
            }

            if (! $this->isManager($user)) {
                $this->ensureAssignedOrManager($task, $user);
            }

            $exists = ProductionTaskCorrectionRequest::query()
                ->where('production_task_id', (string) $task->id)
                ->where('status', 'PENDING')
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'request' => ['Masih ada pengajuan koreksi yang menunggu approval untuk cucian ini.'],
                ]);
            }

            return ProductionTaskCorrectionRequest::query()->create([
                'production_task_id' => (string) $task->id,
                'order_id'           => (string) $task->order_id,
                'branch_id'          => (string) $task->branch_id,
                'requested_by'       => $user->id,
                'reviewed_by'        => null,
                'type'               => $type,
                'from_status'        => $fromStatus,
                'to_status'          => $toStatus,
                'reason'             => $reason,
                'status'             => 'PENDING',
                'review_note'        => null,
                'requested_date'     => now('Asia/Jakarta')->toDateString(),
                'reviewed_date'      => null,
            ])->load([
                'task',
                'order:id,branch_id,customer_id,number,invoice_no,status,received_at,ready_at',
                'order.customer:id,name,whatsapp',
                'requester:id,name',
                'reviewer:id,name',
            ]);
        });
    }

    public function approveCorrectionRequest(
        ProductionTaskCorrectionRequest $request,
        User $reviewer,
        ?string $reviewNote = null
    ): ProductionTaskCorrectionRequest {
        return DB::transaction(function () use ($request, $reviewer, $reviewNote) {
            $request = ProductionTaskCorrectionRequest::query()
                ->whereKey((string) $request->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureCorrectionReviewAllowed($request, $reviewer);

            if ((string) $request->status !== 'PENDING') {
                throw ValidationException::withMessages([
                    'status' => ['Pengajuan ini sudah diproses.'],
                ]);
            }

            $task = ProductionTask::query()
                ->whereKey((string) $request->production_task_id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureTaskBranchAllowed($task, $reviewer);

            $this->applyCorrection(
                $task,
                $reviewer,
                (string) $request->to_status,
                '[APPROVED ' . $request->type . '] ' . $request->reason
            );

            $request->fill([
                'status'        => 'APPROVED',
                'reviewed_by'   => $reviewer->id,
                'review_note'   => $reviewNote,
                'reviewed_date' => now('Asia/Jakarta')->toDateString(),
            ])->save();

            return $request->fresh([
                'task',
                'order:id,branch_id,customer_id,number,invoice_no,status,received_at,ready_at',
                'order.customer:id,name,whatsapp',
                'requester:id,name',
                'reviewer:id,name',
            ]);
        });
    }

    public function rejectCorrectionRequest(
        ProductionTaskCorrectionRequest $request,
        User $reviewer,
        ?string $reviewNote = null
    ): ProductionTaskCorrectionRequest {
        return DB::transaction(function () use ($request, $reviewer, $reviewNote) {
            $request = ProductionTaskCorrectionRequest::query()
                ->whereKey((string) $request->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureCorrectionReviewAllowed($request, $reviewer);

            if ((string) $request->status !== 'PENDING') {
                throw ValidationException::withMessages([
                    'status' => ['Pengajuan ini sudah diproses.'],
                ]);
            }

            $request->fill([
                'status'        => 'REJECTED',
                'reviewed_by'   => $reviewer->id,
                'review_note'   => $reviewNote,
                'reviewed_date' => now('Asia/Jakarta')->toDateString(),
            ])->save();

            return $request->fresh([
                'task',
                'order:id,branch_id,customer_id,number,invoice_no,status,received_at,ready_at',
                'order.customer:id,name,whatsapp',
                'requester:id,name',
                'reviewer:id,name',
            ]);
        });
    }

    public function calculateOrderQty(Order $order): float
    {
        $order->loadMissing('items:id,order_id,qty');

        $items = $order->getRelation('items');

        return (float) $items->sum('qty');
    }

    private function syncOrderStatusFromProduction(Order $order, string $status): void
    {
        if (! in_array($status, self::STATUSES, true)) {
            throw ValidationException::withMessages([
                'status' => ['Status order tidak valid.'],
            ]);
        }

        $order->forceFill([
            'status' => $status,
        ])->save();

        $order->refresh();
    }

    public function taskRelations(): array
    {
        return [
            'order:id,branch_id,customer_id,number,invoice_no,status,received_at,ready_at',
            'order.customer:id,name,whatsapp',
            'assignee:id,name',
            'branch:id,name',
        ];
    }

    private function findOrCreateTask(Order $order): ProductionTask
    {
        $order->loadMissing('items:id,order_id,qty');

        return ProductionTask::query()->firstOrCreate(
            ['order_id' => (string) $order->id],
            [
                'branch_id'      => (string) $order->branch_id,
                'assigned_to'    => null,
                'current_status' => in_array((string) $order->status, self::STATUSES, true)
                    ? (string) $order->status
                    : 'QUEUE',
                'qty'            => $this->calculateOrderQty($order),
                'started_date'   => null,
                'finished_date'  => null,
                'note'           => null,
            ]
        );
    }

    private function ensureTaskBranchAllowed(ProductionTask $task, User $user): void
    {
        if ($user->hasRole('Superadmin')) {
            return;
        }

        if ((string) $task->branch_id !== (string) $user->branch_id) {
            abort(403, 'Anda tidak memiliki akses ke cucian cabang ini.');
        }
    }

    private function ensureAssignedOrManager(ProductionTask $task, User $user): void
    {
        if ($this->isManager($user)) {
            return;
        }

        if ((string) $task->assigned_to !== (string) $user->id) {
            throw ValidationException::withMessages([
                'assigned_to' => ['Cucian ini belum Anda ambil atau sedang dikerjakan petugas lain.'],
            ]);
        }
    }

    private function isManager(User $user): bool
    {
        return $user->hasRole('Superadmin') || $user->hasRole('Admin Cabang');
    }

    private function targetStatusForCorrection(string $type, string $fromStatus): string
    {
        if (! in_array($fromStatus, self::BOARD_STATUSES, true)) {
            throw ValidationException::withMessages([
                'from_status' => ['Status ini tidak dapat dikoreksi dari modul production.'],
            ]);
        }

        if ($fromStatus === 'QUEUE') {
            throw ValidationException::withMessages([
                'from_status' => ['Status antrian belum dapat dicuci ulang atau dikembalikan tahap.'],
            ]);
        }

        if ($type === 'REWASH') {
            if ($fromStatus === 'WASHING') {
                throw ValidationException::withMessages([
                    'from_status' => ['Cucian masih berada di tahap cuci.'],
                ]);
            }

            return 'WASHING';
        }

        $rollbackMap = [
            'WASHING' => 'QUEUE',
            'DRYING'  => 'WASHING',
            'IRONING' => 'DRYING',
            'READY'   => 'IRONING',
        ];

        if (! isset($rollbackMap[$fromStatus])) {
            throw ValidationException::withMessages([
                'from_status' => ['Status ini tidak memiliki tahap sebelumnya.'],
            ]);
        }

        return $rollbackMap[$fromStatus];
    }

    private function applyCorrection(
        ProductionTask $task,
        User $user,
        string $toStatus,
        ?string $note = null
    ): ProductionTask {
        if (! in_array($toStatus, self::BOARD_STATUSES, true)) {
            throw ValidationException::withMessages([
                'to_status' => ['Target status koreksi tidak valid.'],
            ]);
        }

        $fromStatus = (string) $task->current_status;
        $today      = now('Asia/Jakarta')->toDateString();

        if ($fromStatus === $toStatus) {
            throw ValidationException::withMessages([
                'to_status' => ['Status tujuan sama dengan status saat ini.'],
            ]);
        }

        $task->fill([
            'current_status' => $toStatus,
            'started_date'   => $task->started_date ?: $today,
            'finished_date'  => $toStatus === 'READY' ? $today : null,
            'note'           => $note,
        ])->save();

        $order = Order::query()->findOrFail((string) $task->order_id);
        $this->syncOrderStatusFromProduction($order, $toStatus);

        $this->writeLog($task, $user, $fromStatus, $toStatus, $note);

        return $task->fresh($this->taskRelations());
    }

    private function ensureCorrectionReviewAllowed(ProductionTaskCorrectionRequest $request, User $user): void
    {
        if (! $this->isManager($user)) {
            abort(403, 'Hanya Superadmin atau Admin Cabang yang dapat memproses pengajuan koreksi.');
        }

        if ($user->hasRole('Superadmin')) {
            return;
        }

        if ((string) $request->branch_id !== (string) $user->branch_id) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan koreksi cabang ini.');
        }
    }

    private function writeLog(
        ProductionTask $task,
        User $user,
        ?string $fromStatus,
        string $toStatus,
        ?string $note = null
    ): void {
        ProductionTaskLog::query()->create([
            'production_task_id' => (string) $task->id,
            'order_id'           => (string) $task->order_id,
            'branch_id'          => (string) $task->branch_id,
            'user_id'            => $user->id,
            'from_status'        => $fromStatus,
            'to_status'          => $toStatus,
            'qty'                => $task->qty,
            'process_date'       => Carbon::now('Asia/Jakarta')->toDateString(),
            'started_date'       => $task->started_date,
            'finished_date'      => $task->finished_date,
            'note'               => $note,
        ]);
    }
}
