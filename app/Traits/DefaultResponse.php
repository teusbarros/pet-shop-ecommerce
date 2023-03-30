<?php

namespace App\Traits;

trait DefaultResponse
{
    public function jsonResponse(array $data, int $code = 200, int $success = 1, string $error = null, array $errors = [], array $extra = []): string
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
