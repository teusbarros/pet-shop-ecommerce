<?php

namespace App\Http\Controllers;

use App\Traits\DefaultResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(title="Pet Shop eCommerce - API Documentation", version="0.1")
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     securityScheme="bearerAuth",
 * )
 *
 *  @OA\Tag(
 *     name="Admin",
 *     description="Admin API endpoint",
 * )
 *  @OA\Tag(
 *     name="User",
 *     description="User API endpoint",
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    use DefaultResponse;
}
