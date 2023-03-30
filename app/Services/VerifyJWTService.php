<?php

namespace App\Services;

use App\Models\Token;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use http\Exception\UnexpectedValueException;

class VerifyJWTService {
    const ALLOWED_ALGOS = 'RS256';

    public static function excecute($token): void
    {
        if (!$token || $token == '') {
            throw new UnexpectedValueException('Invalid token');
        }
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_PUBLIC'), self::ALLOWED_ALGOS));

            session(['uuid' => $decoded->user_uuid]);

            if (!Token::validatePayload($token))
                throw new ExpiredException;
        } catch (ExpiredException $e) {
            throw new ExpiredException('Expired token');
        } catch (UnexpectedValueException $e) {
            throw new UnexpectedValueException($e->getMessage());
        }
    }
}
