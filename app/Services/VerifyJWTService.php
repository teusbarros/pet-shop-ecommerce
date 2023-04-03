<?php

namespace App\Services;

use App\Models\Token;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

final class VerifyJWTService
{
    public const ALLOWED_ALGOS = 'RS256';

    public static function excecute(string|null $token): void
    {
        if (! $token) {
            throw new \UnexpectedValueException('Invalid token');
        }
        try {
            $decoded = JWT::decode(
                $token,
                new Key(
                    env('JWT_PUBLIC'),
                    self::ALLOWED_ALGOS
                )
            );

            session(['uuid' => $decoded->user_uuid]);

            if (! Token::validatePayload($token)) {
                throw new ExpiredException();
            }
        } catch (ExpiredException $e) {
            throw new ExpiredException('Expired token');
        } catch (\UnexpectedValueException $e) {
            throw new \UnexpectedValueException($e->getMessage());
        }
    }
}
