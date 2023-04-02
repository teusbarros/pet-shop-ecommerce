<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CreateProductRequest;
use App\Http\Resources\v1\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * @OA\Post(
     *     tags={"Products"},
     *     path="/api/v1/product/create",
     *     summary="Create a new product",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"category_uuid", "title", "price", "description", "metadata"},
     *                  @OA\Property(
     *                      property="category_uuid", type="string",
     *                      description="Category UUID"
     *                  ),
     *                  @OA\Property(
     *                      property="title", type="string",
     *                      description="Product title"
     *                  ),
     *                  @OA\Property(
     *                      property="price", type="number",
     *                      description="Product price"
     *                  ),
     *                  @OA\Property(
     *                      property="description", type="string",
     *                      description="Product description"
     *                  ),
     *                  @OA\Property(
     *                      property="metadata", type="object",
     *                      description="Product metadata"
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
    public function create(CreateProductRequest $request): JsonResponse
    {
        $dataForm = $request->all();
        $dataForm['uuid'] = Str::uuid();

        $product = Product::create($dataForm);

        return $this->jsonResponse(new ProductResource($product));
    }

    /**
     * @OA\Put(
     *     tags={"Products"},
     *     path="/api/v1/product/{uuid}",
     *     summary="Update an existing product",
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
     *                  required={"category_uuid", "title", "price", "description", "metadata"},
     *                  @OA\Property(
     *                      property="category_uuid", type="string",
     *                      description="Category UUID"
     *                  ),
     *                  @OA\Property(
     *                      property="title", type="string",
     *                      description="Product title"
     *                  ),
     *                  @OA\Property(
     *                      property="price", type="number",
     *                      description="Product price"
     *                  ),
     *                  @OA\Property(
     *                      property="description", type="string",
     *                      description="Product description"
     *                  ),
     *                  @OA\Property(
     *                      property="metadata", type="object",
     *                      description="Product metadata"
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
    public function edit(Product $product, CreateProductRequest $request): JsonResponse
    {
        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->metadata = $request->metadata;

        $product->save();

        return $this->jsonResponse(new ProductResource($product));
    }
    /**
     * @OA\Get(
     *     tags={"Products"},
     *     path="/api/v1/product/{uuid}",
     *     summary="Fetch a product",
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
    public function show(Product $product): JsonResponse
    {
        return $this->jsonResponse(new ProductResource($product));
    }

    /**
     * @OA\Delete(
     *     tags={"Products"},
     *     path="/api/v1/product/{uuid}",
     *     summary="Delete an existing product",
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
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return $this->jsonResponse([]);
    }
}
