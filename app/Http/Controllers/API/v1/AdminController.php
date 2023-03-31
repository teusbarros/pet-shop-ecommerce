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

class AdminController extends Controller
{
    public function create(CreateAdminRequest $request): JsonResponse
    {
        $dataForm = $request->all();
        $dataForm['is_admin'] = true;
        $dataForm['uuid'] = Str::uuid();
        $dataForm['password'] = Hash::make($dataForm['password']);

        $user = User::create($dataForm);

        if ($user){
            return $this->jsonResponse(new AdminResource($user));
        }else{
            return $this->jsonResponse([], 422);
        }

    }
    public function index(Request $request): ResourceCollection
    {
        $sort_by = $request->sort_by ?? null;
        $limit = $request->limit ?? null;
        $desc = $request->desc === "true";
        $to_sort = Schema::hasColumn((new User)->getTable(), $sort_by);

        $users = User::notAdmin()
            ->when($to_sort, function ($query) use ($sort_by, $desc){
                if ($desc)
                    return $query->orderBy($sort_by, 'desc');
                return $query->orderBy($sort_by);
            })->paginate($limit);


        return new UserCollection($users);
    }
    public function edit(User $user, EditUserRequest $request): JsonResponse
    {
        if ($user->is_admin){
            return $this->jsonResponse([], 403, 0, 'Unauthorized: Not enough privileges');
        }

        $user->update($request->all());

        return $this->jsonResponse(new UserResource($user));
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->is_admin){
            return $this->jsonResponse([], 403, 0, 'Unauthorized: Not enough privileges');
        }

        $user->token?->delete();
        $user->delete();

        return $this->jsonResponse([], 200, 1);
    }
}
