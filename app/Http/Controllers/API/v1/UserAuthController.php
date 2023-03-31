<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\LoginRequest;
use App\Models\User;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;

class UserAuthController extends Controller
{
    private LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }
    /**
     * @OA\Post(
     *     tags={"User"},
     *     path="/api/v1/user/login",
     *     summary="Login a User account",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"email","password"},
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="User email"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="User password"
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     ),
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $credentials['is_admin'] = false;

        if (auth()->attempt($credentials)) {
            /** @var \App\Models\User $user*/
            $user = auth()->user();
            $token = $this->loginService->excecute($user);

            return $this->jsonResponse(['token' => $token]);
        }
        return $this->jsonResponse([], 401, 0, 'Failed to authenticate user');
    }
    /**
     * @OA\Get(
     *     tags={"User"},
     *     path="/api/v1/user/logout",
     *     summary="Logout a User account",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     ),
     * )
     */

    public function logout(): JsonResponse
    {
        $user_id = session('uuid');
        auth()->logout();

        if (User::deleteToken($user_id)) {
            return $this->jsonResponse([]);
        }

        return $this->jsonResponse([], 401, 0, 'Invalid token');
    }
}
