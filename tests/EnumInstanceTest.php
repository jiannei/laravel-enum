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
use Jiannei\Enum\Laravel\Exceptions\InvalidEnumKeyException;
use Jiannei\Enum\Laravel\Exceptions\InvalidEnumValueException;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;

test('can instantiate enum class with new', function () {
    // 使用 new 方式实例化常量
    $userType = new UserTypeEnum(UserTypeEnum::ADMINISTRATOR);
    expect($userType)->toBeInstanceOf(UserTypeEnum::class);
});

test('can instantiate enum class from value', function () {
    // 根据常量的值（value）来实例化常量
    $userType = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);
    expect($userType)->toBeInstanceOf(UserTypeEnum::class);
});

test('can instantiate enum class from key', function () {
    // 根据常量的名称（key）来实例化常量
    $userType = UserTypeEnum::fromKey('ADMINISTRATOR');
    expect($userType)->toBeInstanceOf(UserTypeEnum::class);
});

test('an exception is thrown when trying to instantiate enum class with an invalid enum value', function () {
    // 尝试使用不存在的常量值实例化常量时会抛出异常
    $this->expectException(InvalidEnumValueException::class);

    UserTypeEnum::fromValue('InvalidValue');
});

test('an exception is thrown when trying to instantiate enum class with an invalid enum key', function () {
    // 尝试使用不存在的常量名称实例化常量时会抛出异常
    $this->expectException(InvalidEnumKeyException::class);

    UserTypeEnum::fromKey('foobar');
});

test('can get the value for an enum instance', function () {
    // 通过常量实例可以获取常量的值（value）
    $userType = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

    expect(UserTypeEnum::ADMINISTRATOR)->toEqual($userType->value);
});

test('can get the key for an enum instance', function () {
    // 通过常量实例可以获取常量的名称（key）
    $userType = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

    expect(UserTypeEnum::getKey(UserTypeEnum::ADMINISTRATOR))->toEqual($userType->key);
});

test('can get the description for an enum instance', function () {
    // 通过常量实例可以获取常量的描述（description）
    $userType = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

    expect(UserTypeEnum::getDescription(UserTypeEnum::ADMINISTRATOR))->toEqual($userType->description);
});

test('can get enum instance by calling an enum key as a static method', function () {
    // 通过静态方法来实例化常量
    expect(UserTypeEnum::ADMINISTRATOR())->toBeInstanceOf(UserTypeEnum::class);
});

test('magic instantiation from instance method', function () {
    // 静态方法、非静态方法
    $userType = new UserTypeEnum(UserTypeEnum::ADMINISTRATOR);
    expect($userType->magicInstantiationFromInstanceMethod())->toBeInstanceOf(UserTypeEnum::class);
});

test('an exception is thrown when trying to get enum instance by calling an enum key as a static method which does not exist', function () {
    // 通过不合法的静态方法实例化常量时将抛出异常
    $this->expectException(InvalidEnumKeyException::class);

    UserTypeEnum::KeyWhichDoesNotExist();
});

test('getting an instance using an instance returns an instance', function () {
    expect(UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR))->toBeInstanceOf(UserTypeEnum::class);
});
