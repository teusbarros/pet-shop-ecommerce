<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @phpstan-consistent-constructor */
class Token extends Model
{
    use HasFactory;
    protected $table = 'jwt_tokens';
    protected $fillable = [
        'user_id',
        'unique_id',
        'token_title',
        'restrictions',
        'permissions',
        'expires_at',
        'last_used_at',
        'refreshed_at',
    ];

    /**
     * @return BelongsTo<User, Token>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public static function validatePayload(string $token): bool
    {
        return Token::where('unique_id', $token)->count() == 1;
    }
}
