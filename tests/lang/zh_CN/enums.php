<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Jiannei\Enum\Laravel\Tests\Enums\UserType;

return [
    UserType::class => [
        UserType::ADMINISTRATOR->name => '管理员',
        UserType::MODERATOR->name => '主持人',
        UserType::SUBSCRIBER->name => '订阅用户',
    ],
];
