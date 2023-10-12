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
use Jiannei\Enum\Laravel\Exceptions\InvalidEnumValueException;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeCustomCastEnum;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;
use Jiannei\Enum\Laravel\Tests\Models\User;

test('can set model value using enum instance', function () {
    // 可以使用常量实例来给 model 赋值
    $model = app(User::class);
    $model->user_type = UserTypeEnum::MODERATOR();

    expect($model->user_type)->toEqual(UserTypeEnum::MODERATOR());
});

test('can set model value using enum value', function () {
    // 可以使用常量的值来给 model 赋值
    $model = app(User::class);
    $model->user_type = UserTypeEnum::MODERATOR;

    // 由于事先在 \Tests\Unit\Enum\Models\User 中定义了 $casts，所以这里 $model->user_type 取值时会自动进行转换
    expect($model->user_type)->toBeInstanceOf(UserTypeEnum::class);

    expect($model->user_type)->toEqual(UserTypeEnum::MODERATOR());
});

test('cannot set model value using invalid enum value', function () {
    // 给 model 赋值非法的常量值会抛出异常
    $this->expectException(InvalidEnumValueException::class);

    $model = app(User::class);
    $model->user_type = 5;
});

test('getting model value returns enum instance', function () {
    // 由于事先在 \Tests\Unit\Enum\Models\User 中定义了 $casts，所以这里 $model->user_type 取值时会自动进行转换
    $model = app(User::class);
    $model->user_type = UserTypeEnum::MODERATOR;

    expect($model->user_type)->toBeInstanceOf(UserTypeEnum::class);
});

test('can get and set null on enum castable', function () {
    // 可以设置空值
    $model = app(User::class);
    $model->user_type = null;

    expect($model->user_type)->toBeNull();
});

test('can handle string int from database', function () {
    // 从数据库中取出的枚举值/常量值通常为字符串类型，也可以转换成相应的常量实例
    $model = app(User::class);
    $reflection = new ReflectionProperty(User::class, 'attributes');
    $reflection->setAccessible(true);
    $reflection->setValue($model, ['user_type' => '1']);

    expect($model->user_type)->toBeInstanceOf(UserTypeEnum::class);
});

test('that model with enum can be cast to array', function () {
    // 包含有 cast 常量转换的 Model 也可以转成数组
    $model = app(User::class);
    $model->user_type = UserTypeEnum::MODERATOR();

    expect(json_decode(json_encode($model), true))->toBe([
        'user_type' => [
            'description' => 'Moderator',
            'key' => 'MODERATOR',
            'value' => 1,
        ],
    ]);
});

test('can use custom casting', function () {
    // 自定义常量实例转换逻辑
    $model = app(User::class);

    $reflection = new ReflectionProperty(User::class, 'attributes');
    $reflection->setAccessible(true);
    $reflection->setValue($model, ['user_type_custom' => 'type-3']);

    // 常规转换此处是 3
    expect($model->user_type_custom)->toBeInstanceOf(UserTypeCustomCastEnum::class);
    // type-3 => UserTypeCustomCastEnum
    expect($model->user_type_custom)->toEqual(UserTypeCustomCastEnum::SUPER_ADMINISTRATOR());

    $model->user_type_custom = UserTypeCustomCastEnum::ADMINISTRATOR();

    // 常规转换此处 user_type_custom 的值为 0
    expect($reflection->getValue($model)['user_type_custom'])->toBe('type-0');
});
