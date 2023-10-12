<?php

uses(\Jiannei\Enum\Laravel\Tests\TestCase::class);
use Illuminate\Support\Facades\Validator;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;

test('validate value using pipe validation', function () {
    // 根据常量的值（value）来判断是否合法：是否存在某个常量值
    $validator = Validator::make(['type' => UserTypeEnum::ADMINISTRATOR], [
        'type' => 'enum_value:'.UserTypeEnum::class,
    ]);

    expect($validator->passes())->toBeTrue();

    $validator = Validator::make(['type' => 99], [
        'type' => 'enum_value:'.UserTypeEnum::class,
    ]);

    expect($validator->passes())->toBeFalse();
});

test('validate value using pipe validation without strict type checking', function () {
    // 根据常量的值（value）来判断是否合法：是否存在某个常量值
    // 默认 strict 为 true，校验类名称型或字符串大小写
    // strict 为 true 时，这里的 '1' 必须是整数
    $validator = Validator::make(['type' => '1'], [
        'type' => 'enum_value:'.UserTypeEnum::class.',false',
    ]);

    expect($validator->passes())->toBeTrue();
});

test('validate key using pipe validation', function () {
    // 根据常量的名称（key）来判断是否合法：是否存在某个常量名称
    $validator = Validator::make(['type' => 'ADMINISTRATOR'], [
        'type' => 'enum_key:'.UserTypeEnum::class,
    ]);

    expect($validator->passes())->toBeTrue();

    $validator = Validator::make(['type' => 'wrong'], [
        'type' => 'enum_key:'.UserTypeEnum::class,
    ]);

    expect($validator->passes())->toBeFalse();
});

test('validate key using pipe validation without strict type checking', function () {
    // 根据常量的名称（key）来判断是否合法：是否存在某个常量名称
    // strict 为 false 时，不校验大小写
    $validator = Validator::make(['type' => 'administrator'], [
        'type' => 'enum_key:'.UserTypeEnum::class.',false',
    ]);

    expect($validator->passes())->toBeTrue();
});

test('validate enum using pipe validation', function () {
    //  // 校验常量是否属于某一个常量 class
    $validator = Validator::make(['type' => UserTypeEnum::ADMINISTRATOR()], [
        'type' => 'enum:'.UserTypeEnum::class,
    ]);

    expect($validator->passes())->toBeTrue();

    $validator = Validator::make(['type' => 'wrong'], [
        'type' => 'enum:'.UserTypeEnum::class,
    ]);

    expect($validator->passes())->toBeFalse();
});
