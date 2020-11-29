<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Tests;

use Jiannei\Enum\Laravel\Tests\Enums\SingleValueEnum;

class EnumRandomTest extends TestCase
{
    public function testGetRandomInstance()
    {
        // 获取随机的实例
        $instance = SingleValueEnum::getRandomInstance();

        $this->assertTrue(
            $instance->is(SingleValueEnum::KEY)
        );
    }

    public function testGetRandomKey()
    {
        // 获取随机的常量名称（key）
        $key = SingleValueEnum::getRandomKey();

        $this->assertSame(
            SingleValueEnum::getKey(SingleValueEnum::KEY),
            $key
        );
    }

    public function testGetRandomValue()
    {
        // 获取随机的常量值（value）
        $value = SingleValueEnum::getRandomValue();

        $this->assertSame(SingleValueEnum::KEY, $value);
    }
}
