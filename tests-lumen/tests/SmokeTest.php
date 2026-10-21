<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Tests\Lumen;

use Illuminate\Support\Facades\Facade;
use Jiannei\Enum\Laravel\Support\Traits\EnumEnhance;
use Laravel\Lumen\Application;
use PHPUnit\Framework\TestCase;

enum SmokeEnum: int
{
    use EnumEnhance;

    case ONE = 1;
    case TWO = 2;
}

final class SmokeTest extends TestCase
{
    protected function tearDown(): void
    {
        restore_error_handler();
        restore_exception_handler();

        parent::tearDown();
    }

    public function test_description_uses_container_translator_without_facades(): void
    {
        $app = new Application(__DIR__.'/..');
        $translator = $app['translator'];

        $translator->setLocale('zh_CN');
        $translator->addLines([
            'enums.'.SmokeEnum::class.'.ONE' => '一',
        ], 'zh_CN');

        $this->assertNull(Facade::getFacadeApplication());
        $this->assertSame('一', SmokeEnum::ONE->description());
        $this->assertSame('two', SmokeEnum::TWO->description());
    }
}
