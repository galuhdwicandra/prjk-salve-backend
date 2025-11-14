<?php


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
    }

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            if ($e instanceof ValidationException) {
                return response()->json([
                    'data' => null,
                    'meta' => [],
                    'message' => 'Validation Error',
                    'errors' => $e->errors(),
                ], 422);
            }
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'data' => null,
                    'meta' => [],
                    'message' => 'Unauthenticated',
                    'errors' => ['auth' => ['Unauthenticated']],
                ], 401);
            }
            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'data' => null,
                    'meta' => [],
                    'message' => 'Forbidden',
                    'errors' => ['auth' => ['Forbidden']],
                ], 403);
            }
            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'data' => null,
                    'meta' => [],
                    'message' => 'Not Found',
                    'errors' => ['route' => ['Not Found']],
                ], 404);
            }
        }
        return parent::render($request, $e);
    }
}
