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
use Jiannei\Enum\Laravel\Http\Requests\Rules\EnumValue;
use Jiannei\Enum\Laravel\Tests\Enums\StringValuesEnum;
use Jiannei\Enum\Laravel\Tests\Enums\SuperPowersEnum;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;

test('validation passes', function () {
    // 根据常量的值（value）来判断是否合法：是否存在某个常量值
    $passes1 = (new EnumValue(UserTypeEnum::class))->passes('', 3);
    $passes2 = (new EnumValue(StringValuesEnum::class))->passes('', 'administrator');

    expect($passes1)->toBeTrue();
    expect($passes2)->toBeTrue();
});

test('validation fails', function () {
    // 传入不存在的常量值
    $fails1 = (new EnumValue(UserTypeEnum::class))->passes('', 7);
    $fails2 = (new EnumValue(UserTypeEnum::class))->passes('', 'OtherString');
    $fails3 = (new EnumValue(UserTypeEnum::class))->passes('', '3');

    expect($fails1)->toBeFalse();
    expect($fails2)->toBeFalse();
    expect($fails3)->toBeFalse();
});

test('flagged enum passes with no flags set', function () {
    // 校验 FlaggedEnum 是否存在某个常量值
    $passed = (new EnumValue(SuperPowersEnum::class))->passes('', 0);

    expect($passed)->toBeTrue();
});

test('flagged enum passes with single flag set', function () {
    // 校验 FlaggedEnum 是否存在某个常量值
    $passed = (new EnumValue(SuperPowersEnum::class))->passes('', SuperPowersEnum::FLIGHT);

    expect($passed)->toBeTrue();
});

test('flagged enum passes with multiple flags set', function () {
    // 校验 FlaggedEnum 是否存在某个常量值
    $passed = (new EnumValue(SuperPowersEnum::class))->passes('', SuperPowersEnum::SUPERMAN);

    expect($passed)->toBeTrue();
});

test('flagged enum passes with all flags set', function () {
    // 校验 FlaggedEnum 是否存在某个常量值
    $allFlags = array_reduce(SuperPowersEnum::getValues(), function (int $carry, int $powerValue) {
        return $carry | $powerValue;
    }, 0);

    $passed = (new EnumValue(SuperPowersEnum::class))->passes('', $allFlags);

    expect($passed)->toBeTrue();
});

test('flagged enum fails with invalid flag set', function () {
    $allFlagsSet = array_reduce(SuperPowersEnum::getValues(), function ($carry, $value) {
        return $carry | $value;
    }, 0);
    $passed = (new EnumValue(SuperPowersEnum::class))->passes('', $allFlagsSet + 1);

    expect($passed)->toBeFalse();
});

test('can turn off strict type checking', function () {
    // strict 默认为 true，校验变量类型或名称大小写；为 false 不校验
    $passes = (new EnumValue(UserTypeEnum::class, false))->passes('', '3');

    expect($passes)->toBeTrue();

    $fails1 = (new EnumValue(UserTypeEnum::class, false))->passes('', '10');
    $fails2 = (new EnumValue(UserTypeEnum::class, false))->passes('', 'a');

    expect($fails1)->toBeFalse();
    expect($fails2)->toBeFalse();
});

test('an exception is thrown if an non existing class is passed', function () {
    // 实例化一个不存在的常量会抛出异常
    $this->expectException(InvalidArgumentException::class);

    (new EnumValue('PathToAClassThatDoesntExist'))->passes('', 'Test');
});

test('can serialize to string without strict type checking', function () {
    // 校验规则序列化成 string
    $rule = new EnumValue(UserTypeEnum::class, false);

    expect((string) $rule)->toEqual('enum_value:'.UserTypeEnum::class.',false');
});

test('can serialize to string with strict type checking', function () {
    // 校验规则序列化成 string
    $rule = new EnumValue(UserTypeEnum::class, true);

    expect((string) $rule)->toEqual('enum_value:'.UserTypeEnum::class.',true');
});
