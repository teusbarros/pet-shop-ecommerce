<?php

namespace App\Http\Middleware\v1;

use App\Services\GetUserByTokenService;
use App\Traits\DefaultResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AdminMiddleware
{
    use DefaultResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = GetUserByTokenService::get();

        if (! $user) {
            return $this->jsonResponse([], 401, 0, 'Unauthorized');
        }
        if (! $user->isAdmin()) {
            return $this->jsonResponse([], 403, 0, 'Forbidden');
        }
        return $next($request);
    }
}
