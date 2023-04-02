<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CreateBrandRequest;
use App\Http\Resources\v1\BrandResource;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * @OA\Post(
     *     tags={"Brands"},
     *     path="/api/v1/brand/create",
     *     summary="Create a new brand",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"title"},
     *                  @OA\Property(
     *                      property="title", type="string",
     *                      description="Brand title"
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
    public function create(CreateBrandRequest $request): JsonResponse
    {
        $dataForm = $request->only('title');
        $dataForm['slug'] = str_replace(' ', '-', $request->title);
        $dataForm['uuid'] = Str::uuid();

        $brand = Brand::create($dataForm);

        return $this->jsonResponse(new BrandResource($brand));
    }

    /**
     * @OA\Put(
     *     tags={"Brands"},
     *     path="/api/v1/brand/{uuid}",
     *     summary="Update an existing brand",
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
     *                      description="Brand title"
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
    public function edit(Brand $brand, CreateBrandRequest $request): JsonResponse
    {
        $brand->title = $request->title;
        $brand->slug = str_replace(' ', '-', $request->title);

        $brand->save();

        return $this->jsonResponse(new BrandResource($brand));
    }

    /**
     * @OA\Get(
     *     tags={"Brands"},
     *     path="/api/v1/brand/{uuid}",
     *     summary="Fetch a brand",
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
    public function show(Brand $brand): JsonResponse
    {
        info($brand);
        return $this->jsonResponse(new BrandResource($brand));
    }
}
