<?php

uses(\Jiannei\Enum\Laravel\Tests\TestCase::class);
use Jiannei\Enum\Laravel\Tests\Enums\StringValuesEnum;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;


test('comparison against plain value matching', function () {
    // 使用常量实例的 is 方法，比较某个值与当前常量实例的值是否相同
    $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

    expect($admin->is(UserTypeEnum::ADMINISTRATOR))->toBeTrue();
});

test('comparison against plain value not matching', function () {
    // 使用常量实例的 is 方法，比较某个值与当前常量实例的值是否相同
    // 对应的，常量实例还有 isNot 方法
    $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

    expect($admin->is(UserTypeEnum::SUPER_ADMINISTRATOR))->toBeFalse();
    expect($admin->is('some-random-value'))->toBeFalse();
    expect($admin->isNot(UserTypeEnum::SUPER_ADMINISTRATOR))->toBeTrue();
    expect($admin->isNot('some-random-value'))->toBeTrue();
});

test('comparison against itself matches', function () {
    $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

    expect($admin->is($admin))->toBeTrue();
});

test('comparison against other instances matches', function () {
    // 比较两个常量实例的值是否相同
    $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);
    $anotherAdmin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

    expect($admin->is($anotherAdmin))->toBeTrue();
});

test('comparison against other instances not matching', function () {
    // 比较两个常量实例的值是否相同
    $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);
    $superAdmin = UserTypeEnum::fromValue(UserTypeEnum::SUPER_ADMINISTRATOR);

    expect($admin->is($superAdmin))->toBeFalse();
});

test('enum instance in array', function () {
    // 常量实例的值是否属于数组
    $administrator = new StringValuesEnum(StringValuesEnum::ADMINISTRATOR);

    // 数组元素是常量的值
    expect($administrator->in([
        StringValuesEnum::MODERATOR,
        StringValuesEnum::ADMINISTRATOR,
    ]))->toBeTrue();

    // 数组元素是常量的实例
    expect($administrator->in([
        new StringValuesEnum(StringValuesEnum::MODERATOR),
        new StringValuesEnum(StringValuesEnum::ADMINISTRATOR),
    ]))->toBeTrue();

    expect($administrator->in([StringValuesEnum::ADMINISTRATOR]))->toBeTrue();
    expect($administrator->in([StringValuesEnum::MODERATOR]))->toBeFalse();
});
