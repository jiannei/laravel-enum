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
use Jiannei\Enum\Laravel\Http\Requests\Rules\EnumKey;
use Jiannei\Enum\Laravel\Tests\Enums\StringValuesEnum;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;

test('validation passes', function () {
    // 根据常量的名称（key）来判断是否合法：是否存在某个常量名称
    $passes1 = (new EnumKey(UserTypeEnum::class))->passes('', 'ADMINISTRATOR');
    // strict 默认为 true，校验名称大小写
    $fails = (new EnumKey(StringValuesEnum::class))->passes('', 'Administrator');
    // strict 默认为 true，校验名称大小写
    $passes2 = (new EnumKey(StringValuesEnum::class))->passes('', 'ADMINISTRATOR');
    // strict 默认为 true，不校验名称大小写
    $passes3 = (new EnumKey(StringValuesEnum::class, false))->passes('', 'administrator');

    // strict 默认为 false，不校验名称大小写
    expect($passes1)->toBeTrue();
    expect($fails)->toBeFalse();
    expect($passes2)->toBeTrue();
    expect($passes3)->toBeTrue();
});

test('validation fails', function () {
    // 传入不存在的常量名称（key）
    $fails1 = (new EnumKey(UserTypeEnum::class))->passes('', 'Anything else');
    $fails2 = (new EnumKey(UserTypeEnum::class))->passes('', 2);
    $fails3 = (new EnumKey(UserTypeEnum::class))->passes('', '2');

    expect($fails1)->toBeFalse();
    expect($fails2)->toBeFalse();
    expect($fails3)->toBeFalse();
});

test('an exception is thrown if an non existing class is passed', function () {
    // 实例化一个不存在的常量会抛出异常
    $this->expectException(InvalidArgumentException::class);

    (new EnumKey('PathToAClassThatDoesntExist'))->passes('', 'Test');
});

test('can serialize to string', function () {
    // 校验规则序列化成 string
    $rule1 = new EnumKey(UserTypeEnum::class);
    $rule2 = new EnumKey(UserTypeEnum::class, false);

    expect((string) $rule1)->toEqual('enum_key:'.UserTypeEnum::class.',true');
    expect((string) $rule2)->toEqual('enum_key:'.UserTypeEnum::class.',false');
});
