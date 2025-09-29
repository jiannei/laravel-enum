<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Jiannei\Enum\Laravel\Tests\Enums\UserType;

return [
    UserType::class => [
        UserType::ADMINISTRATOR->name => ['nested' => 'array'], // This should return empty string
        UserType::MODERATOR->name => '监督员',
        UserType::SUBSCRIBER->name => '订阅用户',
    ],
];
