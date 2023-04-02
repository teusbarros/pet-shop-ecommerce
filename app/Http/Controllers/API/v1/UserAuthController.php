<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ForgotPasswordRequest;
use App\Http\Requests\v1\LoginRequest;
use App\Http\Requests\v1\UpdatePasswordRequest;
use App\Models\ResetPasswordToken;
use App\Models\User;
use App\Services\GetUserByTokenService;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

final class UserAuthController extends Controller
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
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     ),
     * )
     */

    public function logout(): JsonResponse
    {
        $user = GetUserByTokenService::get();

        auth()->logout();

        User::deleteToken($user?->uuid);

        return $this->jsonResponse([]);
    }

    /**
     * @OA\Post(
     *     tags={"User"},
     *     path="/api/v1/user/forgot-password",
     *     summary="Creates a token to reset a user password",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"email"},
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="User email"
     *                  )
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
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
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
    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return $this->jsonResponse([], 404, 0, 'Invalid email');
        }
        if ($user->isAdmin()) {
            return $this->jsonResponse([], 403, 0, 'Admin user cannot be edited');
        }

        $reset_token = $user->getNewResetPasswordToken();

        return $this->jsonResponse(['reset_token' => $reset_token]);
    }

    /**
     * @OA\Post(
     *     tags={"User"},
     *     path="/api/v1/user/reset-password-token",
     *     summary="Reset a user password using the token",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={
     *                      "email", "token",
     *                      "password", "password_confirmation"
     *                  },
     *                  @OA\Property(
     *                      property="token",
     *                      type="string",
     *                      description="User reset token"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="User email"
     *                  ),
     *                  @OA\Property(
     *                      property="password", type="string",
     *                      description="User password"
     *                  ),
     *                  @OA\Property(
     *                      property="password_confirmation", type="string",
     *                      description="User password"
     *                  ),
     *
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
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
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
    public function reset(UpdatePasswordRequest $request): JsonResponse
    {
        $token = ResetPasswordToken::where([['token', $request->token],['email', '=', $request->email]])->first();

        if (! $token) {
            return $this->jsonResponse([], 404, 0, 'Invalid or expired token');
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            $token->delete();
        }

        return $this->jsonResponse(['message' => 'Password has been successfully updated']);
    }
}
