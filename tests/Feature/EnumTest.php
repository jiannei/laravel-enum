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

test('to array', function () {
    expect(UserType::names())
        ->toBeArray()->toMatchArray([
            'ADMINISTRATOR', 'MODERATOR', 'SUBSCRIBER',
        ])
        ->and(UserType::values())->toBeArray()->toMatchArray([0, 1, 2])
        ->and(UserType::toArray())->toBeArray()->toMatchArray([
            ['name' => 'ADMINISTRATOR', 'value' => 0, 'description' => 'administrator'],
            ['name' => 'MODERATOR', 'value' => 1, 'description' => 'moderator'],
            ['name' => 'SUBSCRIBER', 'value' => 2, 'description' => 'subscriber'],
        ]);
});

test('to select array', function () {
    expect(UserType::toSelectArray())->toMatchArray([
        0 => 'administrator',
        1 => 'moderator',
        2 => 'subscriber',
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