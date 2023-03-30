<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\LoginRequest;
use App\Services\LoginService;

class AdminAuthController extends Controller
{

    private object $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }


    public function login(LoginRequest $request): string
    {
        $credentials = $request->only('email', 'password');
        $credentials['is_admin'] = true;

        if (auth()->attempt($credentials)) {
            $token = $this->loginService->excecute(auth()->user());

            return $this->jsonResponse(['token' => $token]);

        }else {
            return $this->jsonResponse([], 401, 0, 'Failed to authenticate user');
        }

    }
}
