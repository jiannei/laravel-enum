<?php

use Jiannei\Enum\Laravel\Tests\Enums\UserType;

return [
    UserType::class => [
        UserType::ADMINISTRATOR->value => '管理员',
        UserType::MODERATOR->value => '主持人',
        UserType::SUBSCRIBER->value => '订阅用户',
    ],
];