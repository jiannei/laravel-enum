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
use Jiannei\Enum\Laravel\Http\Requests\Rules\Enum;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;

test('validation passes', function () {
    // 校验常量是否属于某一个常量 class
    $passes1 = (new Enum(UserTypeEnum::class))->passes('', UserTypeEnum::ADMINISTRATOR());

    expect($passes1)->toBeTrue();
});

test('validation fails', function () {
    // 校验常量是否属于某一个常量 class
    $fails1 = (new Enum(UserTypeEnum::class))->passes('', 'Some string');
    $fails2 = (new Enum(UserTypeEnum::class))->passes('', 1);
    $fails3 = (new Enum(UserTypeEnum::class))->passes('', UserTypeEnum::ADMINISTRATOR()->key);
    $fails4 = (new Enum(UserTypeEnum::class))->passes('', UserTypeEnum::ADMINISTRATOR()->value);

    expect($fails1)->toBeFalse();
    expect($fails2)->toBeFalse();
    expect($fails3)->toBeFalse();
    expect($fails4)->toBeFalse();
});

test('an exception is thrown if an non existing class is passed', function () {
    // 实例化一个不存在的常量会抛出异常
    $this->expectException(InvalidArgumentException::class);

    (new Enum('PathToAClassThatDoesntExist'))->passes('', 'Test');
});

test('can serialize to string', function () {
    // 校验规则序列化成 string
    $rule = new Enum(UserTypeEnum::class);

    expect((string) $rule)->toEqual('enum:'.UserTypeEnum::class);
});
