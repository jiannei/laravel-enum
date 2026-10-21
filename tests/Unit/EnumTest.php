<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Jiannei\Enum\Laravel\Tests\Enums\TestEnum;
use Jiannei\Enum\Laravel\Tests\Enums\UserType;

test('type', function () {
    expect(UserType::class)->toBeEnum()
        ->and(UserType::ADMINISTRATOR)->toBeInstanceOf(UnitEnum::class)
        ->and(UserType::ADMINISTRATOR)->toBeInstanceOf(BackedEnum::class);
});

test('backed', function () {
    expect(UserType::ADMINISTRATOR)
        ->toHaveProperty('name')
        ->toHaveProperty('value');
});

test('names and values', function () {
    expect(UserType::names())->toBe(['ADMINISTRATOR', 'MODERATOR', 'SUBSCRIBER'])
        ->and(UserType::values())->toBe([0, 1, 2])
        ->and(TestEnum::names())->toBe(['TEST_CASE', 'ANOTHER_CASE'])
        ->and(TestEnum::values())->toBe(['test', 'another']);
});

test('has name', function () {
    expect(UserType::hasName('ADMINISTRATOR'))->toBeTrue()
        ->and(UserType::hasName('administrator'))->toBeFalse()
        ->and(UserType::hasName('root'))->toBeFalse();
});

test('has value', function () {
    expect(UserType::hasValue(0))->toBeTrue()
        ->and(UserType::hasValue('0'))->toBeTrue()
        ->and(UserType::hasValue(10))->toBeFalse();
});

test('instance', function () {
    expect(UserType::fromValue(0))->toBe(UserType::ADMINISTRATOR)
        ->and(UserType::fromName('ADMINISTRATOR'))->toBe(UserType::ADMINISTRATOR)
        ->and(UserType::guess(2))->toBe(UserType::SUBSCRIBER)
        ->and(UserType::guess('SUBSCRIBER'))->toBe(UserType::SUBSCRIBER);
});

test('fromValue accepts the string form of an integer backing value', function () {
    expect(UserType::fromValue('0'))->toBe(UserType::ADMINISTRATOR)
        ->and(UserType::fromValue('2'))->toBe(UserType::SUBSCRIBER)
        ->and(UserType::fromValue('02'))->toBe(UserType::SUBSCRIBER)
        ->and(UserType::guess('2'))->toBe(UserType::SUBSCRIBER);
});

test('fromValue rejects values that are not the backing type', function () {
    expect(fn () => UserType::fromValue('abc'))
        ->toThrow(ValueError::class, 'Invalid enum backing value "abc"')
        ->and(fn () => UserType::fromValue('2.5'))
        ->toThrow(ValueError::class, 'Invalid enum backing value "2.5"')
        ->and(UserType::hasValue('abc'))->toBeFalse()
        ->and(UserType::hasValue('2.5'))->toBeFalse();
});

test('string backed enums accept integer input and resolve by name first', function () {
    expect(TestEnum::fromValue('test'))->toBe(TestEnum::TEST_CASE)
        ->and(TestEnum::guess('TEST_CASE'))->toBe(TestEnum::TEST_CASE)
        ->and(TestEnum::guess('test'))->toBe(TestEnum::TEST_CASE)
        ->and(TestEnum::hasValue('test'))->toBeTrue()
        ->and(fn () => TestEnum::fromValue(123))
        ->toThrow(ValueError::class, 'Invalid enum backing value "123"');
});

test('is', function () {
    $admin = UserType::ADMINISTRATOR;

    expect($admin->is(UserType::SUBSCRIBER))->toBeFalse()
        ->and($admin->is(UserType::ADMINISTRATOR))->toBeTrue();
});

test('is not', function () {
    $admin = UserType::ADMINISTRATOR;

    expect($admin->isNot(UserType::SUBSCRIBER))->toBeTrue()
        ->and($admin->isNot(UserType::ADMINISTRATOR))->toBeFalse();
});

test('is any', function () {
    $admin = UserType::ADMINISTRATOR;

    expect($admin->isAny(...UserType::cases()))->toBeTrue()
        ->and($admin->isAny(UserType::ADMINISTRATOR, UserType::MODERATOR))->toBeTrue()
        ->and($admin->isAny(UserType::MODERATOR, UserType::SUBSCRIBER))->toBeFalse()
        ->and($admin->isAny())->toBeFalse();
});

test('name and value methods', function () {
    $admin = UserType::ADMINISTRATOR;

    expect($admin->name())->toBe('ADMINISTRATOR')
        ->and($admin->value())->toBe(0);
});

test('from name exceptions', function () {
    expect(fn () => UserType::fromName('INVALID_NAME'))
        ->toThrow(ValueError::class, 'Invalid enum name "INVALID_NAME"');
});

test('from value exceptions', function () {
    expect(fn () => UserType::fromValue(999))
        ->toThrow(ValueError::class, 'Invalid enum backing value "999"');
});

test('guess exceptions', function () {
    expect(fn () => UserType::guess('INVALID_KEY'))
        ->toThrow(ValueError::class, 'Invalid enum name or backing value "INVALID_KEY"');
});

test('error messages list the available options', function () {
    try {
        UserType::fromName('INVALID');
    } catch (ValueError $e) {
        expect($e->getMessage())->toContain('ADMINISTRATOR')
            ->and($e->getMessage())->toContain('MODERATOR')
            ->and($e->getMessage())->toContain('SUBSCRIBER');
    }

    try {
        UserType::guess('INVALID_KEY');
    } catch (ValueError $e) {
        expect($e->getMessage())->toContain('ADMINISTRATOR')
            ->and($e->getMessage())->toContain('0, 1, 2');
    }
});

test('random method', function () {
    $random = UserType::random();
    expect($random)->toBeInstanceOf(UserType::class)
        ->and(in_array($random, UserType::cases()))->toBeTrue();
});

test('count method', function () {
    expect(UserType::count())->toBe(3)
        ->and(TestEnum::count())->toBe(2);
});

test('isFirst and isLast methods', function () {
    expect(UserType::ADMINISTRATOR->isFirst())->toBeTrue()
        ->and(UserType::ADMINISTRATOR->isLast())->toBeFalse()
        ->and(UserType::SUBSCRIBER->isFirst())->toBeFalse()
        ->and(UserType::SUBSCRIBER->isLast())->toBeTrue()
        ->and(UserType::MODERATOR->isFirst())->toBeFalse()
        ->and(UserType::MODERATOR->isLast())->toBeFalse();
});

test('next and previous methods', function () {
    expect(UserType::ADMINISTRATOR->next())->toBe(UserType::MODERATOR)
        ->and(UserType::MODERATOR->next())->toBe(UserType::SUBSCRIBER)
        ->and(UserType::SUBSCRIBER->next())->toBeNull()
        ->and(UserType::ADMINISTRATOR->previous())->toBeNull()
        ->and(UserType::MODERATOR->previous())->toBe(UserType::ADMINISTRATOR)
        ->and(UserType::SUBSCRIBER->previous())->toBe(UserType::MODERATOR);
});
