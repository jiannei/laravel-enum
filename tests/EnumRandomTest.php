<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

uses(\Jiannei\Enum\Laravel\Tests\TestCase::class);
use Jiannei\Enum\Laravel\Tests\Enums\SingleValueEnum;

test('get random instance', function () {
    // 获取随机的实例
    $instance = SingleValueEnum::getRandomInstance();

    expect($instance->is(SingleValueEnum::KEY))->toBeTrue();
});

test('get random key', function () {
    // 获取随机的常量名称（key）
    $key = SingleValueEnum::getRandomKey();

    expect($key)->toBe(SingleValueEnum::getKey(SingleValueEnum::KEY));
});

test('get random value', function () {
    // 获取随机的常量值（value）
    $value = SingleValueEnum::getRandomValue();

    expect($value)->toBe(SingleValueEnum::KEY);
});
