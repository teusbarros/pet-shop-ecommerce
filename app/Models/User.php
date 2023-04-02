<?php

namespace App\Models;

use App\Services\LoginService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'email',
        'password',
        'is_admin',
        'is_marketing',
        'avatar',
        'address',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param Builder<User> $query
     *
     * @return void
     */
    public function scopeNotAdmin(Builder $query): void
    {
        $query->where('is_admin', 0);
    }
    /**
     * @param Builder<User> $query
     *
     * @return void
     */
    public function scopeIsAdmin(Builder $query): void
    {
        $query->where('is_admin', 1);
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
    /**
     * @return HasOne<Token>
     */
    public function token(): HasOne
    {
        return $this->hasOne(Token::class, 'user_id', 'uuid');
    }
    public function isAdmin(): bool
    {
        return $this->is_admin == 1;
    }
    public function updateToken(string $new_token): void
    {
        $token = $this->token;

        if (! $token) {
            $token = new Token();
            $token->user_id = $this->uuid;
            $token->token_title = $this->first_name;
        }
        $token->unique_id = $new_token;
        $token->save();
    }
    public static function deleteToken(string|null $id): void
    {
        $user = User::whereUuid($id)->first();

        if ($user && $user->token) {
            $user->token->delete();
        }
    }

    public function getNewResetPasswordToken(): string
    {
        // check for existing one
        $token = ResetPasswordToken::find($this->email);

        if (! $token) {
            $token = ResetPasswordToken::create([
                'email' => $this->email,
                'token' => Hash::make(Str::random()),
                'created_at' => Carbon::now(),
            ]);
        }
        return $token->token;
    }
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (User $user): void {
            // create user jwt token
            $loginService = new LoginService();
            $loginService->excecute($user);
        });
    }
}
