<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\LoginRequest;
use App\Models\User;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;

class AdminAuthController extends Controller
{

    private object $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }


    /**
     * @OA\Post(
     *     tags={"Admin"},
     *     path="/api/v1/admin/login",
     *     summary="Login a Admin account",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"email","password"},
     *                  @OA\Property(
     *                      property="email", type="string", description="Admin email"
     *                  ),
     *                  @OA\Property(
     *                      property="password", type="string", description="Admin password"
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
        $credentials['is_admin'] = true;

        if (auth()->attempt($credentials)) {
            $token = $this->loginService->excecute(auth()->user());

            return $this->jsonResponse(['token' => $token]);

        }else {
            return $this->jsonResponse([], 401, 0, 'Failed to authenticate user');
        }

    }
    /**
     * @OA\Get(
     *     tags={"Admin"},
     *     path="/api/v1/admin/logout",
     *     summary="Logout a Admin account",
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

        if(User::deleteToken($user_id))
            return $this->jsonResponse([]);

        return $this->jsonResponse([], 401, 0, 'Invalid token');
    }
}
