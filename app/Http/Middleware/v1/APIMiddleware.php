<?php

namespace App\Http\Middleware\v1;

use App\Services\VerifyJWTService;
use App\Traits\DefaultResponse;
use Closure;
use Firebase\JWT\ExpiredException;
use http\Exception\UnexpectedValueException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class APIMiddleware
{
    use DefaultResponse;
    public function handle(Request $request, Closure $next): Response
    {
        try {
            VerifyJWTService::excecute($request->bearerToken() ?? '');

            return $next($request);
        }catch (ExpiredException $e){
            return $this->jsonResponse([], 401, 0, $e->getMessage());
        }catch (UnexpectedValueException $e){
            return $this->jsonResponse([], 422, 0, $e->getMessage());
        }
    }
}
