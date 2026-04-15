<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhatsappTemplateStoreRequest;
use App\Http\Requests\WhatsappTemplateUpdateRequest;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WhatsappTemplateController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', WhatsappTemplate::class);

        $user = $request->user();

        $q = WhatsappTemplate::query()
            ->with('branch:id,name')
            ->orderBy('key')
            ->orderByDesc('created_at');

        if ($request->filled('key')) {
            $q->where('key', $request->query('key'));
        }

        if ($request->filled('is_active')) {
            $q->where('is_active', (bool) $request->query('is_active'));
        }

        if ($user->hasRole('Superadmin')) {
            if ($request->filled('branch_id')) {
                $branchId = $request->query('branch_id');
                if ($branchId === 'global') {
                    $q->whereNull('branch_id');
                } else {
                    $q->where('branch_id', $branchId);
                }
            }
        } else {
            $q->where('branch_id', $user->branch_id);
        }

        $items = $q->paginate((int) $request->query('per_page', 20));

        return response()->json([
            'data'    => $items->items(),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
                'last_page'    => $items->lastPage(),
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function show(WhatsappTemplate $whatsappTemplate)
    {
        $this->authorize('view', $whatsappTemplate);

        return response()->json([
            'data'    => $whatsappTemplate->load('branch:id,name'),
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function store(WhatsappTemplateStoreRequest $request)
    {
        $this->authorize('create', WhatsappTemplate::class);

        $payload = $request->validated();
        $user    = $request->user();

        if ($user->hasRole('Admin Cabang')) {
            $payload['branch_id'] = (string) $user->branch_id;
        }

        $exists = WhatsappTemplate::query()
            ->where('branch_id', $payload['branch_id'] ?? null)
            ->where('key', $payload['key'])
            ->exists();

        if ($exists) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Template untuk key ini sudah ada.',
                'errors'  => ['key' => ['unique_for_scope']],
            ], 422);
        }

        $row = WhatsappTemplate::query()->create([
            'id'        => (string) Str::uuid(),
            'branch_id' => $payload['branch_id'] ?? null,
            'key'       => $payload['key'],
            'name'      => $payload['name'],
            'content'   => $payload['content'],
            'is_active' => $payload['is_active'] ?? true,
        ]);

        return response()->json([
            'data'    => $row->load('branch:id,name'),
            'meta'    => [],
            'message' => 'Created',
            'errors'  => null,
        ], 201);
    }

    public function update(WhatsappTemplateUpdateRequest $request, WhatsappTemplate $whatsappTemplate)
    {
        $this->authorize('update', $whatsappTemplate);

        $payload = $request->validated();

        $branchId = $payload['branch_id'] ?? $whatsappTemplate->branch_id;
        $key      = $payload['key'] ?? $whatsappTemplate->key;

        $exists = WhatsappTemplate::query()
            ->where('id', '!=', $whatsappTemplate->id)
            ->where('branch_id', $branchId)
            ->where('key', $key)
            ->exists();

        if ($exists) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Template untuk key ini sudah ada.',
                'errors'  => ['key' => ['unique_for_scope']],
            ], 422);
        }

        $whatsappTemplate->fill($payload)->save();

        return response()->json([
            'data'    => $whatsappTemplate->fresh()->load('branch:id,name'),
            'meta'    => [],
            'message' => 'Updated',
            'errors'  => null,
        ]);
    }

    public function destroy(WhatsappTemplate $whatsappTemplate)
    {
        $this->authorize('delete', $whatsappTemplate);

        $whatsappTemplate->delete();

        return response()->json([
            'data'    => null,
            'meta'    => [],
            'message' => 'Deleted',
            'errors'  => null,
        ]);
    }

    /**
     * GET /whatsapp-templates/resolve?key=receipt_paid&branch_id=...
     * Fallback: branch template -> global template
     */
    public function resolve(Request $request)
    {
        $this->authorize('viewAny', WhatsappTemplate::class);

        $request->validate([
            'key'       => ['required', 'string', 'in:receipt_pending,receipt_paid'],
            'branch_id' => ['nullable', 'uuid'],
        ]);

        $user     = $request->user();
        $key      = (string) $request->query('key');
        $branchId = null;

        if ($user->hasRole('Superadmin')) {
            $branchId = $request->query('branch_id');
        } else {
            $branchId = $user->branch_id;
        }

        $template = null;

        if ($branchId) {
            $template = WhatsappTemplate::query()
                ->where('branch_id', $branchId)
                ->where('key', $key)
                ->where('is_active', true)
                ->first();
        }

        if (! $template) {
            $template = WhatsappTemplate::query()
                ->whereNull('branch_id')
                ->where('key', $key)
                ->where('is_active', true)
                ->first();
        }

        return response()->json([
            'data'    => $template,
            'meta'    => [
                'resolved_branch_id' => $branchId,
                'fallback_global'    => $template ? $template->branch_id === null : false,
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }
}
