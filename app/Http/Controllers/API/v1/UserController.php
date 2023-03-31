<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CreateUserRequest;
use App\Http\Requests\v1\EditUserRequest;
use App\Http\Resources\v1\UserResource;
use App\Models\User;
use App\Services\GetUserByTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class UserController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"User"},
     *     path="/api/v1/user",
     *     summary="View a User account",
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
    public function show(): JsonResponse
    {
        $user = GetUserByTokenService::get();

        if (! $user) {
            return $this->jsonResponse([], 401, 0, 'Unauthorized');
        }
        return $this->jsonResponse(new UserResource($user), 200, 1);
    }

    /**
     * @OA\Post(
     *     tags={"User"},
     *     path="/api/v1/user/create",
     *     summary="Create a User account",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={
     *                      "first_name","last_name",
     *                      "email","password",
     *                      "password_confirmation","avatar",
     *                      "address","phone_number"
     *                  },
     *                  @OA\Property(
     *                      property="first_name", type="string",
     *                      description="User first name"
     *                  ),
     *                  @OA\Property(
     *                      property="last_name", type="string",
     *                      description="User last name"
     *                  ),
     *                  @OA\Property(
     *                      property="email", type="string",
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
     *                  @OA\Property(
     *                      property="avatar", type="string",
     *                      description="Avatar image UUID"
     *                  ),
     *                  @OA\Property(
     *                      property="address", type="string",
     *                      description="User main address"
     *                  ),
     *                  @OA\Property(
     *                      property="phone_number", type="string",
     *                      description="User main phone number"
     *                  ),
     *                  @OA\Property(
     *                      property="marketing", type="string",
     *                      description="User marketing preferences"
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
    public function create(CreateUserRequest $request): JsonResponse
    {
        $dataForm = $request->all();
        $dataForm['is_admin'] = false;
        $dataForm['uuid'] = Str::uuid();
        $dataForm['password'] = Hash::make($dataForm['password']);

        $user = User::create($dataForm);

        return $this->jsonResponse(new UserResource($user));
    }

    /**
     * @OA\Put(
     *     tags={"User"},
     *     path="/api/v1/user/edit",
     *     summary="Update a User account",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={
     *                      "first_name","last_name",
     *                      "email","password",
     *                      "password_confirmation",
     *                      "address","phone_number"
     *                  },
     *                  @OA\Property(
     *                      property="first_name", type="string",
     *                      description="User first name"
     *                  ),
     *                  @OA\Property(
     *                      property="last_name", type="string",
     *                      description="User last name"
     *                  ),
     *                  @OA\Property(
     *                      property="email", type="string",
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
     *                  @OA\Property(
     *                      property="avatar", type="string",
     *                      description="Avatar image UUID"
     *                  ),
     *                  @OA\Property(
     *                      property="address", type="string",
     *                      description="User main address"
     *                  ),
     *                  @OA\Property(
     *                      property="phone_number", type="string",
     *                      description="User main phone number"
     *                  ),
     *                  @OA\Property(
     *                      property="marketing", type="string",
     *                      description="User marketing preferences"
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
    public function edit(EditUserRequest $request): JsonResponse
    {
        $user_id = session('uuid');
        $user = User::whereUuid($user_id)->firstOrFail();

        $dataForm = $request->all();
        $dataForm['is_admin'] = 0;

        $user->update($dataForm);

        return $this->jsonResponse(new UserResource($user));
    }

    /**
     * @OA\Delete(
     *     tags={"User"},
     *     path="/api/v1/user",
     *     summary="Delete a User account",
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

    public function destroy(): JsonResponse
    {
        $user_id = session('uuid');
        $user = User::whereUuid($user_id)->firstOrFail();

        $user->token?->delete();
        $user->delete();

        return $this->jsonResponse([], 200, 1);
    }
}
