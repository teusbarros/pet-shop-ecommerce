<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\PostCollection;
use App\Http\Resources\v1\PromotionCollection;
use App\Models\Post;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Schema;

class MainPageController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"MainPage"},
     *     path="/api/v1/main/blog",
     *     summary="List all posts",
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
    public function blogs(Request $request): ResourceCollection
    {
        $sort_by = $request->sort_by ?? null;
        $limit = $request->limit ?? null;
        $desc = $request->desc === 'true';
        $to_sort = Schema::hasColumn((new Post())->getTable(), $sort_by);

        $posts = Post::when($to_sort, function ($query) use ($sort_by, $desc) {
            if ($desc) {
                return $query->orderBy($sort_by, 'desc');
            }
            return $query->orderBy($sort_by);
        })->paginate($limit);

        return new PostCollection($posts);
    }

    /**
     * @OA\Get(
     *     tags={"MainPage"},
     *     path="/api/v1/main/promotions",
     *     summary="List all promotions",
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
    public function promotions(Request $request): ResourceCollection
    {
        $sort_by = $request->sort_by ?? null;
        $limit = $request->limit ?? null;
        $desc = $request->desc === 'true';
        $to_sort = Schema::hasColumn((new Promotion())->getTable(), $sort_by);

        $promotions = Promotion::when($to_sort, function ($query) use ($sort_by, $desc) {
            if ($desc) {
                return $query->orderBy($sort_by, 'desc');
            }
            return $query->orderBy($sort_by);
        })->paginate($limit);

        return new PromotionCollection($promotions);
    }
}
