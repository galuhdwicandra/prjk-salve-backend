<?php
namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleAccess
{
    public function handle(Request $request, Closure $next, string ...$modules): Response
    {
        /** @var User|null $user */
        $user = $request->user();

        foreach ($modules as $module) {
            if ($user?->canModule($module)) {
                return $next($request);
            }
        }

        abort(403, 'Kamu tidak punya akses ke menu itu.');
    }
}
