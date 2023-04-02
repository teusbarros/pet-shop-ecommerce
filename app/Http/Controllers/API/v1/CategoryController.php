<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CreateCategoryRequest;
use App\Http\Resources\v1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * @OA\Post(
     *     tags={"Category"},
     *     path="/api/v1/category/create",
     *     summary="Create a new category",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"title"},
     *                  @OA\Property(
     *                      property="title", type="string",
     *                      description="Category title"
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
    public function create(CreateCategoryRequest $request): JsonResponse
    {
        $dataForm = $request->only('title');
        $dataForm['slug'] = str_replace(' ', '-', $request->title);
        $dataForm['uuid'] = Str::uuid();

        $category = Category::create($dataForm);

        return $this->jsonResponse(new CategoryResource($category));
    }
}
