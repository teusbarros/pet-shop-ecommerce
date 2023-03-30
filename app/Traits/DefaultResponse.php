<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait DefaultResponse
{
    public function jsonResponse(array $data, int $code = 200, int $success = 1, string $error = null, array $errors = [], array $extra = []): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'error'=> $error,
            'errors'=> $errors,
            'extra'=> $extra
        ],  $code);
    }
}
