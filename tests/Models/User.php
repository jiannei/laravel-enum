<?php

namespace Jiannei\Enum\Laravel\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeCustomCastEnum;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;


class User extends Model
{
    protected $casts = [
        'user_type' => UserTypeEnum::class,
        'user_type_custom' => UserTypeCustomCastEnum::class,
    ];

    protected $fillable = [
        'user_type',
        'user_type_custom',
    ];
}
