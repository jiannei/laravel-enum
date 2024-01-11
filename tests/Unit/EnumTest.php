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

test('type', function () {
    expect(UserType::class)->toBeEnum()
        ->and(UserType::ADMINISTRATOR)->toBeInstanceOf(UnitEnum::class)
        ->and(UserType::ADMINISTRATOR)->toBeInstanceOf(BackedEnum::class);
});

test('backed', function () {
    expect(UserType::ADMINISTRATOR)
        ->toHaveProperty('name')
        ->toHaveProperty('value')
        ->toHaveMethods(['cases', 'from', 'tryFrom']);
});
