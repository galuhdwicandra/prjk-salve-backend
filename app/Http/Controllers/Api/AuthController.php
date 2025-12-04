<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private AuthService $auth) {}

    public function login(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $res = $this->auth->login($payload['login'], $payload['password']);
        if (!$res['ok']) {
            return response()->json([
                'data' => null,
                'meta' => null,
                'message' => 'Unauthenticated',
                'errors' => ['auth' => [$res['message']]],
            ], 401);
        }

        return response()->json([
            'data' => ['user' => $res['user']],
            'meta' => ['token' => $res['token']],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'data' => ['user' => $this->auth->me($user)],
            'meta' => null,
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->auth->logout($request->user());

        return response()->json([
            'data' => null,
            'meta' => null,
            'message' => 'Logged out',
            'errors' => null,
        ]);
    }
}
