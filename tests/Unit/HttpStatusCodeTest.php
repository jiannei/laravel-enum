<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Jiannei\Enum\Laravel\Support\Description;
use Jiannei\Enum\Laravel\Support\Enums\HttpStatusCode;

test('http status codes resolve by value and name', function () {
    expect(HttpStatusCode::hasValue(200))->toBeTrue()
        ->and(HttpStatusCode::hasValue(404))->toBeTrue()
        ->and(HttpStatusCode::hasValue(999))->toBeFalse()
        ->and(HttpStatusCode::fromValue(404))->toBe(HttpStatusCode::HTTP_NOT_FOUND)
        ->and(HttpStatusCode::fromName('HTTP_OK'))->toBe(HttpStatusCode::HTTP_OK)
        ->and(HttpStatusCode::guess(500))->toBe(HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR)
        ->and(HttpStatusCode::guess('HTTP_NO_CONTENT'))->toBe(HttpStatusCode::HTTP_NO_CONTENT);
});

test('http status codes keep their int backing type', function () {
    expect(HttpStatusCode::values())->toContain(200, 404, 500)
        ->and(HttpStatusCode::fromValue('200'))->toBe(HttpStatusCode::HTTP_OK);
});

test('every http status name follows the HTTP_ convention', function () {
    foreach (HttpStatusCode::names() as $name) {
        expect($name)->toStartWith('HTTP_');
    }
});

test('http status codes fall back to a generated label', function () {
    expect((new Description)->describe(HttpStatusCode::HTTP_NOT_FOUND))->toBe('http not found');
});
