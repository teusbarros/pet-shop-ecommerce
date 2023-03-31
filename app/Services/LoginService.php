<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;

final class LoginService
{
    public const MAX_TIME = 60 * 60 * 1; // 1 hour

    public const ALLOWED_ALGOS = 'RS256';

    /**
     * @param User $user
     *
     * @return string
     */
    public function excecute(User $user): string
    {
        $payload = [
            'iss' => env('APP_URL'),
            'exp' => time() * self::MAX_TIME,
            'user_uuid' => $user->uuid,
        ];

        $token = JWT::encode($payload, env('JWT_PRIVATE'), self::ALLOWED_ALGOS);

        $user->updateToken($token);

        return $token;
    }
}
