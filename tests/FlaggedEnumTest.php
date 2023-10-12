<?php

uses(\Jiannei\Enum\Laravel\Tests\TestCase::class);
use Jiannei\Enum\Laravel\Tests\Enums\SuperPowersEnum;


test('can construct flagged enum using static properties', function () {
    // 实例化 Flagged 常量对象的几种方式
    // 方式一：new
    $powers1 = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT, SuperPowersEnum::LASER_VISION]);
    // 传入包含「常量值」的数组
    expect($powers1)->toBeInstanceOf(SuperPowersEnum::class);

    $powers2 = new SuperPowersEnum(SuperPowersEnum::STRENGTH);
    // 传入单个「常量值」
    expect($powers2)->toBeInstanceOf(SuperPowersEnum::class);

    // 方式二：fromValue
    $powers3 = SuperPowersEnum::fromValue([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT, SuperPowersEnum::LASER_VISION]);
    // 传入包含「常量值」的数组
    expect($powers3)->toBeInstanceOf(SuperPowersEnum::class);

    $powers4 = SuperPowersEnum::fromValue(SuperPowersEnum::FLIGHT);
    // 传入单个「常量值」
    expect($powers4)->toBeInstanceOf(SuperPowersEnum::class);

    // 方式三：flags
    $powers5 = SuperPowersEnum::flags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT, SuperPowersEnum::LASER_VISION]);
    // 传入包含「常量值」的数组
    expect($powers5)->toBeInstanceOf(SuperPowersEnum::class);

    // 方式四：fromKey
    $powers6 = SuperPowersEnum::fromKey('immortality', false);
    // 传入单个「常量名称」
    expect($powers6)->toBeInstanceOf(SuperPowersEnum::class);

    // 方式五：magic
    $powers7 = SuperPowersEnum::TIME_TRAVEL();
    expect($powers7)->toBeInstanceOf(SuperPowersEnum::class);

    // 方式六：make
    $powers8 = SuperPowersEnum::make(SuperPowersEnum::SUPERMAN);
    expect($powers8)->toBeInstanceOf(SuperPowersEnum::class);
});

test('can construct flagged enum using instances', function () {
    // 实例化 Flagged 常量对象的几种方式
    $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH(), SuperPowersEnum::FLIGHT(), SuperPowersEnum::LASER_VISION()]);
    expect($powers)->toBeInstanceOf(SuperPowersEnum::class);

    $powers = SuperPowersEnum::fromValue([SuperPowersEnum::STRENGTH(), SuperPowersEnum::FLIGHT(), SuperPowersEnum::LASER_VISION()]);
    expect($powers)->toBeInstanceOf(SuperPowersEnum::class);

    $powers = SuperPowersEnum::flags([SuperPowersEnum::STRENGTH(), SuperPowersEnum::FLIGHT(), SuperPowersEnum::LASER_VISION()]);
    expect($powers)->toBeInstanceOf(SuperPowersEnum::class);
});

test('can check if instance has flag', function () {
    // 检查 FlaggedEnum 是否包含「某个」常量标记
    $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);

    expect($powers->hasFlag(SuperPowersEnum::STRENGTH()))->toBeTrue();
    expect($powers->hasFlag(SuperPowersEnum::STRENGTH))->toBeTrue();
    expect($powers->hasFlag(SuperPowersEnum::LASER_VISION()))->toBeFalse();
    expect($powers->hasFlag(SuperPowersEnum::LASER_VISION))->toBeFalse();
});

test('can check if instance has flags', function () {
    // 检查 FlaggedEnum 是否包含「多个」常量标记
    $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);

    expect($powers->hasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]))->toBeTrue();
    expect($powers->hasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::INVISIBILITY]))->toBeFalse();
});

test('can check if instance does not have flag', function () {
    // 检查 FlaggedEnum 是否不包含「某个」常量标记
    $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);

    expect($powers->notHasFlag(SuperPowersEnum::LASER_VISION()))->toBeTrue();
    expect($powers->notHasFlag(SuperPowersEnum::LASER_VISION))->toBeTrue();
    expect($powers->notHasFlag(SuperPowersEnum::STRENGTH()))->toBeFalse();
    expect($powers->notHasFlag(SuperPowersEnum::STRENGTH))->toBeFalse();
});

test('can check if instance does not have flags', function () {
    // 检查 FlaggedEnum 是否不包含「多个」常量标记
    $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);

    expect($powers->notHasFlags([SuperPowersEnum::INVISIBILITY, SuperPowersEnum::LASER_VISION]))->toBeTrue();
    expect($powers->notHasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::LASER_VISION]))->toBeFalse();
    expect($powers->notHasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]))->toBeFalse();
});

test('can set flags', function () {
    //  往 FlaggedEnum 中添加多个标记
    $powers = SuperPowersEnum::NONE();
    expect($powers->hasFlag(SuperPowersEnum::LASER_VISION))->toBeFalse();

    $powers->setFlags([SuperPowersEnum::LASER_VISION, SuperPowersEnum::STRENGTH]);
    expect($powers->hasFlag(SuperPowersEnum::LASER_VISION))->toBeTrue();
    expect($powers->hasFlag(SuperPowersEnum::STRENGTH))->toBeTrue();
});

function TestCanAddFlag()
{
    //  往 FlaggedEnum 中添加单个标记
    $powers = SuperPowersEnum::NONE();
    expect($powers->hasFlag(SuperPowersEnum::IMMORTALITY))->toBeFalse();

    $powers->addFlag(SuperPowersEnum::IMMORTALITY);
    expect($powers->hasFlag(SuperPowersEnum::IMMORTALITY))->toBeTrue();

    $powers->addFlag(SuperPowersEnum::TELEPORTATION);
    expect($powers->hasFlag(SuperPowersEnum::TELEPORTATION))->toBeTrue();
}

test('can add flags', function () {
    //  往 FlaggedEnum 中添加多个标记
    $powers = new SuperPowersEnum(SuperPowersEnum::NONE);
    expect($powers->hasFlag(SuperPowersEnum::LASER_VISION))->toBeFalse();

    $powers->addFlags([SuperPowersEnum::LASER_VISION, SuperPowersEnum::STRENGTH]);
    expect($powers->hasFlags([SuperPowersEnum::LASER_VISION, SuperPowersEnum::STRENGTH]))->toBeTrue();
});

test('can remove flag', function () {
    // 移除 FlaggedEnum 中的「单个」标记
    $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);
    expect($powers->hasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]))->toBeTrue();

    $powers->removeFlag(SuperPowersEnum::STRENGTH);
    expect($powers->hasFlag(SuperPowersEnum::STRENGTH))->toBeFalse();

    $powers->removeFlag(SuperPowersEnum::FLIGHT);
    expect($powers->hasFlag(SuperPowersEnum::FLIGHT))->toBeFalse();

    expect($powers->is(SuperPowersEnum::NONE))->toBeTrue();
});

test('can remove flags', function () {
    // 移除 FlaggedEnum 中的「多个」标记
    $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);
    expect($powers->hasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]))->toBeTrue();

    $powers->removeFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);
    expect($powers->hasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]))->toBeFalse();

    expect($powers->is(SuperPowersEnum::NONE))->toBeTrue();
});

test('can get flags', function () {
    // 获取 FlaggedEnum 中已定义的标记
    $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT, SuperPowersEnum::INVISIBILITY]);
    $flags = $powers->getFlags();

    expect($flags)->toHaveCount(3);
    $this->assertContainsOnlyInstancesOf(SuperPowersEnum::class, $flags);
});

test('can set shortcut values', function () {
    // FlaggedEnum 也可以通过单个常量值来实例化
    $powers = new SuperPowersEnum(SuperPowersEnum::SUPERMAN);

    expect($powers->hasFlag(SuperPowersEnum::STRENGTH))->toBeTrue();
    expect($powers->hasFlag(SuperPowersEnum::LASER_VISION))->toBeTrue();
    expect($powers->hasFlag(SuperPowersEnum::TIME_TRAVEL))->toBeFalse();
});

test('shortcut values are comparable to explicit set', function () {
    $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::LASER_VISION, SuperPowersEnum::FLIGHT]);
    expect($powers->hasFlag(SuperPowersEnum::SUPERMAN))->toBeTrue();

    $powers->removeFlag(SuperPowersEnum::LASER_VISION);
    expect($powers->hasFlag(SuperPowersEnum::SUPERMAN))->toBeFalse();
});

test('can check if instance has multiple flags set', function () {
    // 检查某个 FlaggedEnum 是否包含多个标记
    expect(SuperPowersEnum::SUPERMAN()->hasMultipleFlags())->toBeTrue();
    expect(SuperPowersEnum::STRENGTH()->hasMultipleFlags())->toBeFalse();
    expect(SuperPowersEnum::NONE()->hasMultipleFlags())->toBeFalse();
});

test('can get bitmask for an instance', function () {
    // 获取常量对应的二进制值
    $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);
    expect($powers->getBitmask())->toEqual(1001);

    expect(SuperPowersEnum::SUPERMAN()->getBitmask())->toEqual(1101);
});

test('can instantiate a flagged enum from a value which has multiple flags set', function () {
    $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT, SuperPowersEnum::LASER_VISION]);

    expect(SuperPowersEnum::fromValue($powers->value))->toEqual($powers);
});
