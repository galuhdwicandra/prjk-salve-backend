<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function ok(mixed $data = null, array $meta = [], string $message = 'OK', int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'meta' => $meta,
            'message' => $message,
            'errors' => null,
        ], $status);
    }

    protected function fail(array $errors, string $message = 'Unprocessable Entity', int $status = 422, array $meta = []): JsonResponse
    {
        return response()->json([
            'data' => null,
            'meta' => $meta,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
