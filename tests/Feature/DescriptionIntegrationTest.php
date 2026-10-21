<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Jiannei\Enum\Laravel\Support\Description;
use Jiannei\Enum\Laravel\Tests\Enums\TestEnum;
use Jiannei\Enum\Laravel\Tests\Enums\UserType;

test('to array', function () {
    expect(UserType::toArray())->toBeArray()->toMatchArray([
        ['name' => 'ADMINISTRATOR', 'value' => 0, 'description' => '管理员'],
        ['name' => 'MODERATOR', 'value' => 1, 'description' => '主持人'],
        ['name' => 'SUBSCRIBER', 'value' => 2, 'description' => '订阅用户'],
    ]);
});

test('to array falls back per case when the group is missing', function () {
    expect(UserType::toArray('nonexistent'))->toBe([
        ['name' => 'ADMINISTRATOR', 'value' => 0, 'description' => 'administrator'],
        ['name' => 'MODERATOR', 'value' => 1, 'description' => 'moderator'],
        ['name' => 'SUBSCRIBER', 'value' => 2, 'description' => 'subscriber'],
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

test('description resolves the translator from the container', function () {
    expect(Description::fromContainer()->describe(UserType::ADMINISTRATOR))->toBe('管理员');
});

test('description with array translation falls back to generated label', function () {
    expect(UserType::ADMINISTRATOR->description('test_arrays'))->toBe('administrator');
});

test('description falls back to the generated label', function () {
    expect(UserType::ADMINISTRATOR->description('nonexistent'))->toBe('administrator');
});

test('description fallback keeps underscores as spaces and lowercases', function () {
    expect(TestEnum::TEST_CASE->description('nonexistent'))->toBe('test case');
});
