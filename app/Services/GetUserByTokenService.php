<?php

namespace App\Services;

use App\Models\Token;
use App\Models\User;

final class GetUserByTokenService
{
    /**
     * @return User|null
     */
    public static function get(): User|null
    {
        $token = Token::where('unique_id', request()->bearerToken())->first();

        return $token?->user;
    }
}
