<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CreateCategoryRequest;
use App\Http\Resources\v1\CategoryCollection;
use App\Http\Resources\v1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

final class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Categories"},
     *     path="/api/v1/categories",
     *     summary="List all categories",
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
    public function index(Request $request): CategoryCollection
    {
        $sort_by = $request->sort_by ?? null;
        $limit = $request->limit ?? null;
        $desc = $request->desc === 'true';
        $to_sort = Schema::hasColumn((new Category())->getTable(), $sort_by);

        $categories = Category::when($to_sort, function ($query) use ($sort_by, $desc) {
            if ($desc) {
                return $query->orderBy($sort_by, 'desc');
            }
            return $query->orderBy($sort_by);
        })->paginate($limit);

        return new CategoryCollection($categories);
    }

    /**
     * @OA\Post(
     *     tags={"Categories"},
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

    /**
     * @OA\Put(
     *     tags={"Categories"},
     *     path="/api/v1/category/{uuid}",
     *     summary="Update an existing category",
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
    public function edit(Category $category, CreateCategoryRequest $request): JsonResponse
    {
        $category->title = $request->title;
        $category->slug = str_replace(' ', '-', $request->title);

        $category->save();

        return $this->jsonResponse(new CategoryResource($category));
    }

    /**
     * @OA\Get(
     *     tags={"Categories"},
     *     path="/api/v1/category/{uuid}",
     *     summary="Fetch a category",
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
    public function show(Category $category): JsonResponse
    {
        return $this->jsonResponse(new CategoryResource($category));
    }

    /**
     * @OA\Delete(
     *     tags={"Categories"},
     *     path="/api/v1/category/{uuid}",
     *     summary="Delete an existing category",
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
     *         response=409,
     *         description="This category cannot be deleted because an existing product is using it."
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
    public function destroy(Category $category): JsonResponse
    {
        if (count($category->products)) {
            return $this->jsonResponse([], 409, 0, "This category cannot be deleted because an existing product is using it.");
        }
        $category->delete();

        return $this->jsonResponse([]);
    }
}
