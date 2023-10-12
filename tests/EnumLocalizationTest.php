<?php

uses(\Jiannei\Enum\Laravel\Tests\TestCase::class);
use Jiannei\Enum\Laravel\Tests\Enums\ExampleEnum;


test('enum get description with localization', function () {
    // 常量描述本地化：存在相应语言包的时候取语言包中对应的描述
    $this->app->setLocale('en');
    expect(ExampleEnum::getDescription(ExampleEnum::SUPER_ADMINISTRATOR))->toEqual('Super administrator');

    $this->app->setLocale('zh_CN');
    expect(ExampleEnum::getDescription(ExampleEnum::SUPER_ADMINISTRATOR))->toEqual('超级管理员');
});

test('enum get description for missing localization key', function () {
    // 常量描述本地化：语言包中不存在相应常量对应描述，根据常量的名称（key）值进行转换
    $this->app->setLocale('en');
    expect(ExampleEnum::getDescription(ExampleEnum::MODERATOR))->toEqual('Moderator');

    $this->app->setLocale('zh_CN');
    expect(ExampleEnum::getDescription(ExampleEnum::MODERATOR))->toEqual('Moderator');
});
