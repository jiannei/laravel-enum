<?php

namespace Jiannei\Enum\Laravel\Tests;


use Jiannei\Enum\Laravel\Tests\Enums\StringValuesEnum;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;
use Jiannei\Enum\Laravel\Tests\TestCase;

class EnumComparisonTest extends TestCase
{
    public function testComparisonAgainstPlainValueMatching()
    {
        // 使用常量实例的 is 方法，比较某个值与当前常量实例的值是否相同
        $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

        $this->assertTrue($admin->is(UserTypeEnum::ADMINISTRATOR));
    }

    public function testComparisonAgainstPlainValueNotMatching()
    {
        // 使用常量实例的 is 方法，比较某个值与当前常量实例的值是否相同
        // 对应的，常量实例还有 isNot 方法
        $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

        $this->assertFalse($admin->is(UserTypeEnum::SUPER_ADMINISTRATOR));
        $this->assertFalse($admin->is('some-random-value'));
        $this->assertTrue($admin->isNot(UserTypeEnum::SUPER_ADMINISTRATOR));
        $this->assertTrue($admin->isNot('some-random-value'));
    }

    public function testComparisonAgainstItselfMatches()
    {
        $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

        $this->assertTrue($admin->is($admin));
    }

    public function testComparisonAgainstOtherInstancesMatches()
    {
        // 比较两个常量实例的值是否相同
        $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);
        $anotherAdmin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

        $this->assertTrue($admin->is($anotherAdmin));
    }

    public function testComparisonAgainstOtherInstancesNotMatching()
    {
        // 比较两个常量实例的值是否相同
        $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);
        $superAdmin = UserTypeEnum::fromValue(UserTypeEnum::SUPER_ADMINISTRATOR);

        $this->assertFalse($admin->is($superAdmin));
    }

    public function testEnumInstanceInArray()
    {
        // 常量实例的值是否属于数组
        $administrator = new StringValuesEnum(StringValuesEnum::ADMINISTRATOR);

        // 数组元素是常量的值
        $this->assertTrue($administrator->in([
            StringValuesEnum::MODERATOR,
            StringValuesEnum::ADMINISTRATOR,
        ]));

        // 数组元素是常量的实例
        $this->assertTrue($administrator->in([
            new StringValuesEnum(StringValuesEnum::MODERATOR),
            new StringValuesEnum(StringValuesEnum::ADMINISTRATOR),
        ]));

        $this->assertTrue($administrator->in([StringValuesEnum::ADMINISTRATOR]));
        $this->assertFalse($administrator->in([StringValuesEnum::MODERATOR]));
    }
}
