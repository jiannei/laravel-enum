<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Tests\Validation;

use InvalidArgumentException;
use Jiannei\Enum\Laravel\Http\Requests\Rules\Enum;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;
use Jiannei\Enum\Laravel\Tests\TestCase;

class EnumTest extends TestCase
{
    public function testValidationPasses()
    {
        // 校验常量是否属于某一个常量 class
        $passes1 = (new Enum(UserTypeEnum::class))->passes('', UserTypeEnum::ADMINISTRATOR());

        $this->assertTrue($passes1);
    }

    public function testValidationFails()
    {
        // 校验常量是否属于某一个常量 class
        $fails1 = (new Enum(UserTypeEnum::class))->passes('', 'Some string');
        $fails2 = (new Enum(UserTypeEnum::class))->passes('', 1);
        $fails3 = (new Enum(UserTypeEnum::class))->passes('', UserTypeEnum::ADMINISTRATOR()->key);
        $fails4 = (new Enum(UserTypeEnum::class))->passes('', UserTypeEnum::ADMINISTRATOR()->value);

        $this->assertFalse($fails1);
        $this->assertFalse($fails2);
        $this->assertFalse($fails3);
        $this->assertFalse($fails4);
    }

    public function testAnExceptionIsThrownIfAnNonExistingClassIsPassed()
    {
        // 实例化一个不存在的常量会抛出异常
        $this->expectException(InvalidArgumentException::class);

        (new Enum('PathToAClassThatDoesntExist'))->passes('', 'Test');
    }

    public function testCanSerializeToString()
    {
        // 校验规则序列化成 string
        $rule = new Enum(UserTypeEnum::class);

        $this->assertEquals('enum:'.UserTypeEnum::class, (string) $rule);
    }
}
