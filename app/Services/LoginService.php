<?php

namespace App\Services;

use Firebase\JWT\JWT;

class LoginService {

    const MAX_TIME = 60 * 60 * 1; // 1 hour

    const ALLOWED_ALGOS = 'RS256';

    public function excecute($user): string
    {
        $payload = [
            'iss' => env('APP_URL'),
            'aud' => $user->uuid,
            'iat' => time(),
            'exp' => time() * self::MAX_TIME,
        ];

        $token = JWT::encode($payload, env('JWT_PRIVATE'), self::ALLOWED_ALGOS);

        $user->updateToken($token);

        return $token;
    }
}