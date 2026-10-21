<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Illuminate\Container\Container;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Jiannei\Enum\Laravel\Support\Description;
use Jiannei\Enum\Laravel\Tests\Enums\TestEnum;
use Jiannei\Enum\Laravel\Tests\Enums\UserType;

function translatorWith(array $lines, string $locale = 'zh_CN'): Translator
{
    $translator = new Translator(new ArrayLoader, $locale);
    $translator->addLines($lines, $locale);

    return $translator;
}

test('description resolves the conventional key from an injected translator', function () {
    $translator = translatorWith([
        'enums.'.UserType::class.'.ADMINISTRATOR' => '管理员',
    ]);

    $description = new Description($translator);

    expect($description->describe(UserType::ADMINISTRATOR))->toBe('管理员')
        ->and($description->describe(UserType::MODERATOR))->toBe('moderator');
});

test('description respects the localization group', function () {
    $translator = translatorWith([
        'custom.'.UserType::class.'.ADMINISTRATOR' => '自定义管理员',
    ]);

    $description = new Description($translator);

    expect($description->describe(UserType::ADMINISTRATOR, 'custom'))->toBe('自定义管理员')
        ->and($description->describe(UserType::ADMINISTRATOR))->toBe('administrator');
});

test('description falls back when the translation is not a string', function () {
    $translator = translatorWith([
        'enums.'.UserType::class.'.ADMINISTRATOR' => ['nested' => 'array'],
    ]);

    expect((new Description($translator))->describe(UserType::ADMINISTRATOR))->toBe('administrator');
});

test('description falls back without a translator', function () {
    expect((new Description)->describe(TestEnum::TEST_CASE))->toBe('test case');
});

test('description falls back without a container translator', function () {
    $previous = Container::getInstance();

    Container::setInstance(new Container);

    try {
        expect(UserType::ADMINISTRATOR->description())->toBe('administrator')
            ->and(UserType::ADMINISTRATOR->description('missing_group'))->toBe('administrator');
    } finally {
        Container::setInstance($previous);
    }
});
