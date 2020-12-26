<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Jiannei\Enum\Laravel\Tests\Enums\ExampleEnum;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;

return [

    // example
    ExampleEnum::class => [
        ExampleEnum::ADMINISTRATOR => '管理员',
        ExampleEnum::SUPER_ADMINISTRATOR => '超级管理员',
    ],

    UserTypeEnum::class => [
        UserTypeEnum::ADMINISTRATOR => '管理员',
        UserTypeEnum::SUPER_ADMINISTRATOR => '超级管理员',
        UserTypeEnum::MODERATOR => '监督员',
        UserTypeEnum::SUBSCRIBER => '订阅用户',
    ],
];
