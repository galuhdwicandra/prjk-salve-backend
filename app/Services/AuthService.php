<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(string $login, string $password): array
    {
        $login = trim($login);
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL) !== false;
        $query = User::query();
        if ($isEmail) {
            $query->where('email', strtolower($login));
        } else {
            $query->where('username', strtolower($login));
        }
        $user = $query->first();

        if (!$user || !Hash::check($password, (string) $user->password)) {
            return ['ok' => false, 'status' => 401, 'message' => 'Invalid credentials.'];
        }

        if ((bool) ($user->is_active ?? true) === false) {
            return ['ok' => false, 'status' => 403, 'message' => 'Account is inactive.'];
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'ok' => true,
            'status' => 200,
            'user' => $this->presentUser($user),
            'token' => $token,
        ];
    }

    public function me(User $user): array
    {
        return $this->presentUser($user);
    }

    private function presentUser(User $user): array
    {
        $user->loadMissing(['branch', 'roles']);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'branch' => $user->branch ? [
                'id' => $user->branch->id,
                'code' => $user->branch->code ?? null,
                'name' => $user->branch->name ?? null,
            ] : null,
            'is_active' => (bool) $user->is_active,
            'roles' => $user->getRoleNames()->values(),
        ];
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
