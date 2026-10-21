<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Jiannei\Enum\Laravel\Tests\Enums\EmptyEnum;

test('an empty enum exposes empty collections', function () {
    expect(EmptyEnum::cases())->toBe([])
        ->and(EmptyEnum::count())->toBe(0)
        ->and(EmptyEnum::names())->toBe([])
        ->and(EmptyEnum::values())->toBe([])
        ->and(EmptyEnum::toArray())->toBe([])
        ->and(EmptyEnum::toSelectArray())->toBe([]);
});

test('random throws on an empty enum', function () {
    expect(fn () => EmptyEnum::random())
        ->toThrow(ValueError::class, 'Cannot pick a random case from an empty enum');
});

test('fromName and fromValue throw on an empty enum', function () {
    expect(fn () => EmptyEnum::fromName('ANY'))
        ->toThrow(ValueError::class, 'Invalid enum name "ANY"')
        ->and(fn () => EmptyEnum::fromValue(1))
        ->toThrow(ValueError::class, 'Invalid enum backing value "1"');
});
