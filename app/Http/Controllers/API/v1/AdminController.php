<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CreateAdminRequest;
use App\Http\Resources\v1\AdminResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
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
}
