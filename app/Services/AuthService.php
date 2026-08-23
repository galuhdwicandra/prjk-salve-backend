<?php
namespace App\Services;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(string $login, string $password): array
    {
        $login   = trim($login);
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL) !== false;
        $query   = User::query();
        if ($isEmail) {
            $query->where('email', strtolower($login));
        } else {
            $query->where('username', strtolower($login));
        }
        $user = $query->first();

        if (! $user || ! Hash::check($password, (string) $user->password)) {
            return ['ok' => false, 'status' => 401, 'message' => 'Invalid credentials.'];
        }

        if ((bool) ($user->is_active ?? true) === false) {
            return ['ok' => false, 'status' => 403, 'message' => 'Account is inactive.'];
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'ok'     => true,
            'status' => 200,
            'user'   => $this->presentUser($user),
            'token'  => $token,
        ];
    }

    public function me(User $user): array
    {
        return $this->presentUser($user);
    }

    private function presentUser(User $user): array
    {
        $user->loadMissing(['branches', 'roles']);

        return [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'branch_id'    => $user->branch_id,
            'branches'     => $this->visibleBranches($user)->map(fn($b) => [
                'id'   => $b->id,
                'code' => $b->code,
                'name' => $b->name,
            ])->values(),
            'is_active'    => (bool) $user->is_active,
            'role_label'   => $user->role_label ?: $user->getRoleNames()->first(),
            'roles'        => $user->getRoleNames()->values(),
            'modules'      => $user->effectiveModules(),
            'manager'      => $user->isManager(),
            'show_balance' => (bool) $user->show_balance,
            'custom_price' => (bool) $user->custom_price,
        ];
    }

    private function visibleBranches(User $user): Collection
    {
        if ($user->all_branches) {
            return Branch::query()->orderBy('name')->get();
        }

        return Branch::query()->whereIn('id', $user->branchIds())->orderBy('name')->get();
    }

    public function logout(User $user): array
    {
        $token = request()->user()?->currentAccessToken();
        if ($token) {
            $token->delete();
        }
        return ['ok' => true, 'status' => 200, 'message' => 'Logged out.'];
    }
}
