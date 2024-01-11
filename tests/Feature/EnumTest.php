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

test('to array', function () {
    expect(UserType::names())
        ->toBeArray()->toMatchArray([
            'ADMINISTRATOR', 'MODERATOR', 'SUBSCRIBER',
        ])
        ->and(UserType::values())->toBeArray()->toMatchArray([0, 1, 2])
        ->and(UserType::toArray())->toBeArray()->toMatchArray([
            ['name' => 'ADMINISTRATOR', 'value' => 0, 'description' => '管理员'],
            ['name' => 'MODERATOR', 'value' => 1, 'description' => '主持人'],
            ['name' => 'SUBSCRIBER', 'value' => 2, 'description' => '订阅用户'],
        ]);
});

test('to select array', function () {
    expect(UserType::toSelectArray())->toMatchArray([
        0 => '管理员',
        1 => '主持人',
        2 => '订阅用户',
    ]);
});

test('specify localization group', function () {
    expect(UserType::toSelectArray('*'))->toMatchArray([
        0 => '管理员',
        1 => '监督员',
        2 => '订阅用户',
    ]);
});

test('has name', function () {
    expect(UserType::hasName('ADMINISTRATOR'))->toBeTrue()
        ->and(UserType::hasName('root'))->toBeFalse();
});

test('has value', function () {
    expect(UserType::hasValue(0))->toBeTrue()
        ->and(UserType::hasValue(10))->toBeFalse();
});

test('instance', function () {
    expect(UserType::fromValue(0))->toBe(UserType::ADMINISTRATOR)
        ->and(UserType::fromName('ADMINISTRATOR'))->toBe(UserType::ADMINISTRATOR)
        ->and(UserType::guess(2))->toBe(UserType::SUBSCRIBER);
});

test('is', function () {
    $admin = UserType::ADMINISTRATOR;

    expect($admin->is(UserType::SUBSCRIBER))->toBeFalse()
        ->and($admin->is(UserType::ADMINISTRATOR))->toBeTrue();
});

test('is any', function () {
    $admin = UserType::ADMINISTRATOR;

    expect($admin->isAny(UserType::cases()))->toBeTrue();
});
