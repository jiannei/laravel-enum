<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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
