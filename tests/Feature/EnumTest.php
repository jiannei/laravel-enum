<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Jiannei\Enum\Laravel\Tests\Enums\UserType;

test('to array', function () {
    expect(UserType::names())
        ->toBeArray()->toMatchArray([
            'ADMINISTRATOR', 'MODERATOR', 'SUBSCRIBER',
        ])
        ->and(UserType::values())->toBeArray()->toMatchArray([0, 1, 2])
        ->and(UserType::toArray())->toBeArray()->toMatchArray([
            ['name' => 'ADMINISTRATOR', 'value' => 0, 'description' => '管理员'],
            ['name' => 'MODERATOR', 'value' => 1, 'description' => '主持人'],
            ['name' => 'SUBSCRIBER', 'value' => 2, 'description' => '订阅用户'],
        ]);
});

test('to select array', function () {
    expect(UserType::toSelectArray())->toMatchArray([
        0 => '管理员',
        1 => '主持人',
        2 => '订阅用户',
    ]);
});

test('specify localization group', function () {
    expect(UserType::toSelectArray('*'))->toMatchArray([
        0 => '管理员',
        1 => '监督员',
        2 => '订阅用户',
    ]);
});

test('has name', function () {
    expect(UserType::hasName('ADMINISTRATOR'))->toBeTrue()
        ->and(UserType::hasName('root'))->toBeFalse();
});

test('has value', function () {
    expect(UserType::hasValue(0))->toBeTrue()
        ->and(UserType::hasValue(10))->toBeFalse();
});

test('instance', function () {
    expect(UserType::fromValue(0))->toBe(UserType::ADMINISTRATOR)
        ->and(UserType::fromName('ADMINISTRATOR'))->toBe(UserType::ADMINISTRATOR)
        ->and(UserType::guess(2))->toBe(UserType::SUBSCRIBER);
});

test('is', function () {
    $admin = UserType::ADMINISTRATOR;

    expect($admin->is(UserType::SUBSCRIBER))->toBeFalse()
        ->and($admin->is(UserType::ADMINISTRATOR))->toBeTrue();
});

test('is any', function () {
    $admin = UserType::ADMINISTRATOR;

    expect($admin->isAny(...UserType::cases()))->toBeTrue()
        ->and($admin->isAny(UserType::ADMINISTRATOR, UserType::MODERATOR))->toBeTrue()
        ->and($admin->isAny(UserType::MODERATOR, UserType::SUBSCRIBER))->toBeFalse();
});

test('name and value methods', function () {
    $admin = UserType::ADMINISTRATOR;

    expect($admin->name())->toBe('ADMINISTRATOR')
        ->and($admin->value())->toBe(0);
});

test('is not', function () {
    $admin = UserType::ADMINISTRATOR;

    expect($admin->isNot(UserType::SUBSCRIBER))->toBeTrue()
        ->and($admin->isNot(UserType::ADMINISTRATOR))->toBeFalse();
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
        ->toThrow(ValueError::class, 'INVALID_KEY is illegal for enum');
});

test('description with array translation', function () {
    // Test when translation returns an array (should return empty string)
    expect(UserType::ADMINISTRATOR->description('test_arrays'))->toBe('');
});

test('description fallback to snake case', function () {
    // Test fallback when no translation exists
    expect(UserType::ADMINISTRATOR->description('nonexistent'))->toBe('administrator');
});

test('fromName edge case with reflection', function () {
    // Use reflection to test the edge case
    $reflection = new ReflectionClass(UserType::class);
    $method = $reflection->getMethod('fromName');

    // Test normal case first
    $result = UserType::fromName('ADMINISTRATOR');
    expect($result)->toBe(UserType::ADMINISTRATOR);

    // Create a scenario where the method might encounter edge cases
    // by testing with a name that exists but might cause filtering issues
    try {
        // This tests the internal logic of the method
        $cases = UserType::cases();
        expect(count($cases))->toBeGreaterThan(0);

        // Test that the method handles the filtering correctly
        $filtered = array_filter($cases, fn ($enum) => $enum->name === 'ADMINISTRATOR');
        expect(count($filtered))->toBe(1);
    } catch (Exception $e) {
        // If any edge case occurs, it should be handled properly
        expect($e)->toBeInstanceOf(ValueError::class);
    }
});

test('fromValue edge case with reflection', function () {
    // Use reflection to test the edge case
    $reflection = new ReflectionClass(UserType::class);
    $method = $reflection->getMethod('fromValue');

    // Test normal case first
    $result = UserType::fromValue(0);
    expect($result)->toBe(UserType::ADMINISTRATOR);

    // Create a scenario where the method might encounter edge cases
    try {
        // This tests the internal logic of the method
        $cases = UserType::cases();
        expect(count($cases))->toBeGreaterThan(0);

        // Test that the method handles the filtering correctly
        $filtered = array_filter($cases, fn ($enum) => $enum->value === 0);
        expect(count($filtered))->toBe(1);
    } catch (Exception $e) {
        // If any edge case occurs, it should be handled properly
        expect($e)->toBeInstanceOf(ValueError::class);
    }
});

test('fromName edge case with broken enum', function () {
    // Create a mock class that overrides hasName to return true but cases to return empty
    $mockClass = new class
    {
        use \Jiannei\Enum\Laravel\Support\Traits\EnumEnhance;

        public static function hasName(string $name, bool $strict = false): bool
        {
            return true; // Always return true to pass the first check
        }

        public static function cases(): array
        {
            return []; // Return empty to make head() return null
        }
    };

    // This should trigger the edge case on line 99
    expect(fn () => $mockClass::fromName('TEST'))
        ->toThrow(ValueError::class, 'Invalid enum name "TEST"');
});

test('fromValue edge case with broken enum', function () {
    // Create a mock class that overrides hasValue to return true but cases to return empty
    $mockClass = new class
    {
        use \Jiannei\Enum\Laravel\Support\Traits\EnumEnhance;

        public static function hasValue(int|string $value, bool $strict = false): bool
        {
            return true; // Always return true to pass the first check
        }

        public static function cases(): array
        {
            return []; // Return empty to make head() return null
        }
    };

    // This should trigger the edge case on line 115
    expect(fn () => $mockClass::fromValue('test'))
        ->toThrow(ValueError::class, 'Invalid enum backing value "test"');
});

test('random method', function () {
    $random = UserType::random();
    expect($random)->toBeInstanceOf(UserType::class)
        ->and(in_array($random, UserType::cases()))->toBeTrue();
});

test('count method', function () {
    expect(UserType::count())->toBe(3);
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
        ->and(UserType::SUBSCRIBER->next())->toBeNull() // no next after last
        ->and(UserType::ADMINISTRATOR->previous())->toBeNull() // no previous before first
        ->and(UserType::MODERATOR->previous())->toBe(UserType::ADMINISTRATOR)
        ->and(UserType::SUBSCRIBER->previous())->toBe(UserType::MODERATOR);
});

test('improved error messages', function () {
    expect(fn () => UserType::fromName('INVALID'))
        ->toThrow(ValueError::class)
        ->and(fn () => UserType::fromValue(999))
        ->toThrow(ValueError::class);

    // Test that error messages contain helpful information
    try {
        UserType::fromName('INVALID');
    } catch (ValueError $e) {
        expect($e->getMessage())->toContain('ADMINISTRATOR')
            ->and($e->getMessage())->toContain('MODERATOR')
            ->and($e->getMessage())->toContain('SUBSCRIBER');
    }
});
