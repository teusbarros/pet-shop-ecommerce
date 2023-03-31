<?php

namespace App\Traits;

use App\Http\Resources\v1\AdminResource;
use App\Http\Resources\v1\UserResource;
use Illuminate\Http\JsonResponse;

trait DefaultResponse
{
    /**
     * @param array<mixed>|AdminResource|UserResource|null $data
     * @param int $code
     * @param int $success
     * @param string|null $error
     * @param array<mixed> $errors
     * @param array<mixed> $extra
     *
     * @return JsonResponse
     */
    public function jsonResponse(array|null|AdminResource|UserResource $data, int $code = 200, int $success = 1, string $error = null, array $errors = [], array $extra = []): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'error' => $error,
            'errors' => $errors,
            'extra' => $extra,
        ], $code);
    }
}
