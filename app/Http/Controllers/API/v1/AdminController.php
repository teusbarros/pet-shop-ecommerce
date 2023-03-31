<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CreateAdminRequest;
use App\Http\Requests\v1\EditUserRequest;
use App\Http\Resources\v1\AdminResource;
use App\Http\Resources\v1\UserCollection;
use App\Http\Resources\v1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

final class AdminController extends Controller
{
    /**
     * @OA\Post(
     *     tags={"Admin"},
     *     path="/api/v1/admin/create",
     *     summary="Create a Admin account",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={
     *                      "first_name","last_name","email",
     *                      "password","password_confirmation",
     *                      "avatar","address","phone_number"
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
    public function create(CreateAdminRequest $request): JsonResponse
    {
        $dataForm = $request->all();
        $dataForm['is_admin'] = true;
        $dataForm['uuid'] = Str::uuid();
        $dataForm['password'] = Hash::make($dataForm['password']);

        $user = User::create($dataForm);

        return $this->jsonResponse(new AdminResource($user));
    }
    /**
     * @OA\Get(
     *     tags={"Admin"},
     *     path="/api/v1/admin/user-listing",
     *     summary="Create a Admin account",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="desc",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="boolean"
     *         )
     *     ),
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
    public function index(Request $request): ResourceCollection
    {
        $sort_by = $request->sort_by ?? null;
        $limit = $request->limit ?? null;
        $desc = $request->desc === 'true';
        $to_sort = Schema::hasColumn((new User())->getTable(), $sort_by);

        $users = User::notAdmin()
            ->when($to_sort, function ($query) use ($sort_by, $desc) {
                if ($desc) {
                    return $query->orderBy($sort_by, 'desc');
                }
                return $query->orderBy($sort_by);
            })->paginate($limit);

        return new UserCollection($users);
    }
    /**
     * @OA\Put(
     *     tags={"Admin"},
     *     path="/api/v1/admin/user-edit/{uuid}",
     *     summary="Edit a User account",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         required=true,
     *         in="path",
     *         description="",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={
     *                      "first_name","last_name","email",
     *                      "password","password_confirmation",
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
    public function edit(User $user, EditUserRequest $request): JsonResponse
    {
        if ($user->is_admin) {
            return $this->jsonResponse([], 403, 0, 'Unauthorized: Not enough privileges');
        }

        $user->update($request->all());

        return $this->jsonResponse(new UserResource($user));
    }

    /**
     * @OA\Delete(
     *     tags={"Admin"},
     *     path="/api/v1/admin/user-delete/{uuid}",
     *     summary="Delete a User account",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         required=true,
     *         in="path",
     *         description="",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
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

    public function destroy(User $user): JsonResponse
    {
        if ($user->is_admin) {
            return $this->jsonResponse([], 401, 0, 'Unauthorized: Not enough privileges');
        }

        $user->token?->delete();
        $user->delete();

        return $this->jsonResponse([], 200, 1);
    }
}
