<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(string $email, string $password): array
    {
        $user = User::query()->where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return ['ok' => false, 'status' => 401, 'message' => 'Invalid credentials.'];
        }

        if ((bool) ($user->is_active ?? true) === false) {
            return ['ok' => false, 'status' => 403, 'message' => 'Account is inactive.'];
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'ok' => true,
            'status' => 200,
            'user' => $user->loadMissing('roles'),
            'token' => $token,
        ];
    }

    public function me(User $user): array
    {
        return $this->presentUser($user);
    }

    private function presentUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'branch_id' => $user->branch_id,
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
