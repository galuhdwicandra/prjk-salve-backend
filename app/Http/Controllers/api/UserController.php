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
            'search' => (string) $request->query('q', ''),
            'branch_id' => $this->branchScopeFor($request),
        ];
        $perPage = (int) $request->integer('per_page', 15);

        /** @var \Illuminate\Pagination\LengthAwarePaginator $page */
        $page = $this->svc->paginate($filters, $perPage);

        return response()->json([
            'data' => $page->items(),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
                'last_page' => $page->lastPage(),
                // opsional tambahkan:
                // 'from' => $page->firstItem(),
                // 'to'   => $page->lastItem(),
            ],
            'message' => 'OK',
            'errors' => [],
        ]);
    }

    public function show(Request $request, User $user): JsonResponse
    {
        $this->authorize('view', $user);

        $user->load('roles:id,name');

        return response()->json([
            'data' => $user,
            'meta' => null,
            'message' => 'OK',
            'errors' => [],
        ]);
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        $payload = $request->validated();

        // Admin Cabang: paksa branch_id sesuai miliknya bila tidak diisi
        if ($request->user()->hasRole('Admin Cabang') && empty($payload['branch_id'])) {
            $payload['branch_id'] = $request->user()->branch_id;
        }

        $user = $this->svc->create($payload);

        return response()->json([
            'data' => $user,
            'meta' => null,
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $payload = $request->validated();

        if ($request->user()->hasRole('Admin Cabang') && array_key_exists('branch_id', $payload)) {
            $payload['branch_id'] = $request->user()->branch_id;
        }

        $updated = $this->svc->update($user, $payload);

        return response()->json([
            'data' => $updated,
            'meta' => null,
            'message' => 'Updated',
            'errors' => [],
        ]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $this->svc->delete($user);

        return response()->json([
            'data' => null,
            'meta' => null,
            'message' => 'Deleted',
            'errors' => [],
        ]);
    }

    private function branchScopeFor(Request $request): ?string
    {
        $me = $request->user();

        if ($me->hasRole('Superadmin')) {
            // bebas: boleh query('branch_id') atau null (semua)
            return $request->query('branch_id');
        }

        // Admin Cabang & Kasir dibatasi ke cabangnya
        return $me->branch_id ? (string) $me->branch_id : null;
    }

    public function resetPassword(Request $request, User $user): JsonResponse
    {
        $this->authorize('resetPassword', $user);

        $data = $request->validate([
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
        ]);

        $this->svc->resetPassword($user, $data['password']);

        return response()->json([
            'data' => null,
            'meta' => null,
            'message' => 'Password reset successful',
            'errors' => [],
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
            'data' => ['id' => $updated->id, 'is_active' => $updated->is_active],
            'meta' => null,
            'message' => 'User activity toggled',
            'errors' => [],
        ]);
    }

    public function setRoles(Request $request, User $user): JsonResponse
    {
        $this->authorize('setRoles', $user);

        $data = $request->validate([
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $updated = $this->svc->setRoles($user, $data['roles']);

        return response()->json([
            'data' => $updated->load('roles:id,name'),
            'meta' => null,
            'message' => 'Roles updated',
            'errors' => [],
        ]);
    }
}
