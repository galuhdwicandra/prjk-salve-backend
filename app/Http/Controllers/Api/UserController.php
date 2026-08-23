<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    public function __construct(private UserService $svc)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $filters = [
            'search'     => (string) $request->query('q', ''),
            'branch_ids' => $this->branchScopeFor($request),
            'role'       => (string) $request->query('role', ''),
            'is_active'  => $request->has('is_active') ? $request->boolean('is_active') : null,
        ];
        $perPage = (int) $request->integer('per_page', 15);

        /** @var LengthAwarePaginator $page */
        $page = $this->svc->paginate($filters, $perPage);

        // Normalisasi: roles -> string[]
        $items = collect($page->items())->map(function (User $u) {
            return [
                'id'         => $u->id,
                'name'       => $u->name,
                'email'      => $u->email,
                'username'   => $u->username,
                'branch_id'  => $u->branch_id,
                'is_active'  => (bool) $u->is_active,
                'roles'      => $u->getRoleNames()->values(),
                'role_label' => $u->role_label,
                'manager'    => $u->isManager(),
                'branches'   => $u->branches->map(fn($b) => [
                    'id' => $b->id, 'code' => $b->code, 'name' => $b->name,
                ])->values(),
            ];
        })->values();

        return response()->json([
            'data'    => $items,
            'meta'    => [
                'current_page' => $page->currentPage(),
                'per_page'     => $page->perPage(),
                'total'        => $page->total(),
                'last_page'    => $page->lastPage(),
                // opsional tambahkan:
                // 'from' => $page->firstItem(),
                // 'to'   => $page->lastItem(),
            ],
            'message' => 'OK',
            'errors'  => [],
        ]);
    }

    public function show(Request $request, User $user): JsonResponse
    {
        $this->authorize('view', $user);

        $user->load(['roles:id,name', 'branches:id,code,name']);
        $data = [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'username'     => $user->username,
            'branch_id'    => $user->branch_id,
            'is_active'    => (bool) $user->is_active,
            'roles'        => $user->getRoleNames()->values(),
            'role_label'   => $user->role_label,
            'modules'      => $user->modules ?? [],
            'manager'      => $user->isManager(),
            'show_balance' => (bool) $user->show_balance,
            'custom_price' => (bool) $user->custom_price,
            'branches'     => $user->branches->map(fn($b) => [
                'id' => $b->id, 'code' => $b->code, 'name' => $b->name,
            ])->values(),
        ];

        return response()->json([
            'data'    => $data,
            'meta'    => null,
            'message' => 'OK',
            'errors'  => [],
        ]);
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $user = $this->svc->create($payload);

        return response()->json([
            'data'    => $user,
            'meta'    => null,
            'message' => 'Created',
            'errors'  => null,
        ], 201);
    }

    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $payload = $request->validated();

        $updated = $this->svc->update($user, $payload);

        return response()->json([
            'data'    => $updated,
            'meta'    => null,
            'message' => 'Updated',
            'errors'  => [],
        ]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $this->svc->delete($user);

        return response()->json([
            'data'    => null,
            'meta'    => null,
            'message' => 'Deleted',
            'errors'  => [],
        ]);
    }

    private function branchScopeFor(Request $request): ?array
    {
        $me = $request->user();

        if ($me->all_branches) {
            $branchId = $request->query('branch_id');

            return $branchId ? [(string) $branchId] : null;
        }

        return $me->branchIds() ?: null;
    }

    public function resetPassword(Request $request, User $user): JsonResponse
    {
        $this->authorize('resetPassword', $user);

        $data = $request->validate([
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
        ]);

        $this->svc->resetPassword($user, $data['password']);

        return response()->json([
            'data'    => null,
            'meta'    => null,
            'message' => 'Password reset successful',
            'errors'  => [],
        ]);
    }

    public function setActive(Request $request, User $user): JsonResponse
    {
        $this->authorize('setActive', $user);

        $data = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $updated = $this->svc->setActive($user, (bool) $data['is_active']);

        return response()->json([
            'data'    => ['id' => $updated->id, 'is_active' => $updated->is_active],
            'meta'    => null,
            'message' => 'User activity toggled',
            'errors'  => [],
        ]);
    }

    public function setRoles(Request $request, User $user): JsonResponse
    {
        $this->authorize('setRoles', $user);

        $data = $request->validate([
            'roles'   => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $updated = $this->svc->setRoles($user, $data['roles']);

        return response()->json([
            'data'    => $updated->load('roles:id,name'),
            'meta'    => null,
            'message' => 'Roles updated',
            'errors'  => [],
        ]);
    }
}
