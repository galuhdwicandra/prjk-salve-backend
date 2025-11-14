<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Matikan wrapping agar data rapih di resource
        JsonResource::withoutWrapping();

        // Macro respons baku
        Response::macro('api', function ($data = null, $meta = [], $message = 'OK', $status = 200) {
            return response()->json(compact('data', 'meta', 'message') + ['errors' => null], $status);
        });

        Response::macro('fail', function ($errors = [], $message = 'Unprocessable Entity', $status = 422, $meta = []) {
            return response()->json(['data' => null, 'meta' => $meta, 'message' => $message, 'errors' => $errors], $status);
        });

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
