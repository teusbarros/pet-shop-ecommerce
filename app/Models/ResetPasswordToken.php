<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class ResetPasswordToken extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'email';
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];
}
