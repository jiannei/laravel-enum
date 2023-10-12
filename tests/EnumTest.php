<?php

uses(\Jiannei\Enum\Laravel\Tests\TestCase::class);
use Jiannei\Enum\Laravel\Enum;
use Jiannei\Enum\Laravel\Tests\Enums\ExampleEnum;
use Jiannei\Enum\Laravel\Tests\Enums\StringValuesEnum;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;


test('enum values', function () {
    // 常规用法：获取常量的值
    expect(UserTypeEnum::ADMINISTRATOR)->toEqual(0);
    expect(UserTypeEnum::SUPER_ADMINISTRATOR)->toEqual(3);
});

test('enum get keys', function () {
    // 获取所有已定义常量的名称
    $keys = UserTypeEnum::getKeys();
    $expectedKeys = ['ADMINISTRATOR', 'MODERATOR', 'SUBSCRIBER', 'SUPER_ADMINISTRATOR'];

    expect($keys)->toEqual($expectedKeys);
});

test('enum get key', function () {
    // 根据常量的值获取常量的名称
    expect(UserTypeEnum::getKey(1))->toEqual('MODERATOR');
    expect(UserTypeEnum::getKey(3))->toEqual('SUPER_ADMINISTRATOR');
});

test('enum get values', function () {
    // 获取所有已定义常量的值
    $values = UserTypeEnum::getValues();
    $expectedValues = [0, 1, 2, 3];

    expect($values)->toEqual($expectedValues);
});

test('enum get value', function () {
    // 根据常量的名称获取常量的值
    expect(UserTypeEnum::getValue('MODERATOR'))->toEqual(1);
    expect(UserTypeEnum::getValue('SUPER_ADMINISTRATOR'))->toEqual(3);
});

test('enum get description', function () {
    // 根据常量的值获取常量的描述信息
    $this->app->setLocale('zh_CN');

    // 1. 不存在语言包的情况，返回较为友好的英文描述
    expect(ExampleEnum::getDescription(ExampleEnum::MODERATOR))->toEqual('Moderator');

    // 2. 在 resource/lang/zh_CN/enums.php 中定义常量与描述的对应关系（enums.php 文件名称可以在 config/enum.php 文件中配置）
    expect(ExampleEnum::getDescription(ExampleEnum::ADMINISTRATOR))->toEqual('管理员');

    // 补充：也可以先实例化常量对象，然后再根据对象实例来获取常量描述
    $responseEnum = new ExampleEnum(ExampleEnum::ADMINISTRATOR);
    expect($responseEnum->description)->toEqual('管理员');

    $responseEnum = ExampleEnum::ADMINISTRATOR();
    expect($responseEnum->description)->toEqual('管理员');
});

test('enum has value', function () {
    // 检查定义的常量中是否包含某个「常量值」
    expect(UserTypeEnum::hasValue(1))->toBeTrue();

    expect(UserTypeEnum::hasValue(-1))->toBeFalse();
});

test('enum has key', function () {
    // 检查定义的常量中是否包含某个「常量值」
    expect(UserTypeEnum::hasKey('MODERATOR'))->toBeTrue();

    expect(UserTypeEnum::hasKey('ADMIN'))->toBeFalse();
});

test('enum instance', function () {
    // 实例化常量对象的方式
    // 方式一：new 传入常量的值
    $administrator1 = new UserTypeEnum(UserTypeEnum::ADMINISTRATOR);

    // 方式二：fromValue
    $administrator2 = UserTypeEnum::fromValue(0);

    // 方式三：fromKey
    $administrator3 = UserTypeEnum::fromKey('ADMINISTRATOR');

    // 方式四：magic
    $administrator4 = UserTypeEnum::ADMINISTRATOR();

    // 方式五：make，尝试根据「常量的值」或「常量的名称」实例化对象常量，实例失败时返回原先传入的值
    $administrator5 = UserTypeEnum::make(0);
    // 此处尝试根据「常量的值」实例化
    $administrator6 = UserTypeEnum::make('ADMINISTRATOR');

    // 此处尝试根据「常量的名称」实例化
    expect($administrator1)->toBeInstanceOf(UserTypeEnum::class);
    expect($administrator2)->toBeInstanceOf(UserTypeEnum::class);
    expect($administrator3)->toBeInstanceOf(UserTypeEnum::class);
    expect($administrator4)->toBeInstanceOf(UserTypeEnum::class);
    expect($administrator5)->toBeInstanceOf(UserTypeEnum::class);
    expect($administrator6)->toBeInstanceOf(UserTypeEnum::class);
});

test('enum make', function () {
    // 尝试根据常量的值或者常量的名称实例化一个常量对象
    // 1.尝试根据常量的值实例化常量对象
    $administrator1 = UserTypeEnum::make(UserTypeEnum::ADMINISTRATOR()->value);
    expect($administrator1->value)->toEqual(UserTypeEnum::ADMINISTRATOR);

    // 2.尝试根据常量的名称实例化常量对象
    $administrator2 = UserTypeEnum::make(UserTypeEnum::ADMINISTRATOR()->key);
    expect($administrator2->value)->toEqual(UserTypeEnum::ADMINISTRATOR);

    // 3. 尝试用不存在的值来实例化常量对象
    $enum = UserTypeEnum::make(-1);
    expect($enum)->toEqual(-1);

    // 不存在时返回传入的值
    $enum = UserTypeEnum::make(null);
    expect($enum)->toEqual(null);

    // 不存在时返回传入的值
    // 4. 不区分传入值的类型、大小写等
    $administrator3 = UserTypeEnum::make('0');
    // strict 默认为 true，会校验传入值的类型
    $this->assertNotInstanceOf(UserTypeEnum::class, $administrator3);

    $administrator4 = UserTypeEnum::make('0', false);
    // strict 设置为 false，不校验传入值的类型
    expect($administrator4)->toBeInstanceOf(UserTypeEnum::class);

    $administrator5 = UserTypeEnum::make('administrator');
    // strict 默认为 true，会校验传入值的大小写
    $this->assertNotInstanceOf(UserTypeEnum::class, $administrator5);

    $administrator6 = UserTypeEnum::make('AdminiStrator', false);
    // strict 设置为 false，不校验传入值的大小写
    expect($administrator6)->toBeInstanceOf(UserTypeEnum::class);
});

test('enum get random key', function () {
    // 随机获取一个常量的名称
    expect(UserTypeEnum::getKeys())->toContain(UserTypeEnum::getRandomKey());
});

test('enum get random value', function () {
    // 随机获取一个常量的值
    expect(UserTypeEnum::getValues())->toContain(UserTypeEnum::getRandomValue());
});

test('enum to array', function () {
    // 转换为 key => value 形式的数组
    $array = UserTypeEnum::toArray();
    $expectedArray = [
        'ADMINISTRATOR' => 0,
        'MODERATOR' => 1,
        'SUBSCRIBER' => 2,
        'SUPER_ADMINISTRATOR' => 3,
    ];

    expect($array)->toEqual($expectedArray);
});

test('enum to select array', function () {
    $this->app->setLocale('zh_CN');

    // 转换为 value => key 形式的数组，可以用于页面的下拉选项
    $array = UserTypeEnum::toSelectArray();
    $expectedArray = [
        0 => '管理员',
        1 => '监督员',
        2 => '订阅用户',
        3 => '超级管理员',
    ];

    expect($array)->toEqual($expectedArray);
});

test('enum to select array with string values', function () {
    // 转换为  value => description 形式的数组，可以用于页面的下拉选项
    $array = StringValuesEnum::toSelectArray();
    $expectedArray = [
        'administrator' => 'Administrator',
        'moderator' => 'Moderator',
    ];

    expect($array)->toEqual($expectedArray);
});

test('enum get key using string value', function () {
    // 根据字符串格式的值获取常量名称
    expect(StringValuesEnum::getKey('administrator'))->toEqual('ADMINISTRATOR');
});

test('enum get value using string key', function () {
    // 根据字符串格式的名称获取常量的值
    expect(StringValuesEnum::getValue('ADMINISTRATOR'))->toEqual('administrator');
});

test('enum is', function () {
    // 获取常量内部定义的全部对象实例
    /** @var StringValuesEnum $administrator */
    /** @var StringValuesEnum $moderator */
    [
        'ADMINISTRATOR' => $administrator,
        'MODERATOR' => $moderator,
    ] = StringValuesEnum::getInstances();

    // 检查某个常量是与另一个常量「值」相同
    expect($administrator->is(StringValuesEnum::ADMINISTRATOR))->toBeTrue();
    expect($moderator->is(StringValuesEnum::MODERATOR))->toBeTrue();
});

test('enum is any', function () {
    // 实例化一个常量对象
    $administrator = StringValuesEnum::fromValue(StringValuesEnum::ADMINISTRATOR);

    // 检查某个常量是为属于已定义的任一常量
    expect($administrator->in(StringValuesEnum::getInstances()))->toBeTrue();
});

test('enum can be cast to string', function () {
    // 常量实例 __toString 后为改常量的「值」
    $enumWithZeroIntegerValue = new UserTypeEnum(UserTypeEnum::ADMINISTRATOR);
    $enumWithPositiveIntegerValue = new UserTypeEnum(UserTypeEnum::SUPER_ADMINISTRATOR);
    $enumWithStringValue = new StringValuesEnum(StringValuesEnum::MODERATOR);

    // Numbers should be cast to strings
    expect((string) $enumWithZeroIntegerValue)->toBe('0');
    expect((string) $enumWithPositiveIntegerValue)->toBe('3');

    // Strings should just be returned
    expect((string) $enumWithStringValue)->toBe(StringValuesEnum::MODERATOR);
});

test('enum is macroable with static methods', function () {
    // 给 Enum 扩展静态方法
    Enum::macro('toFlippedArray', function () {
        return array_flip(self::toArray());
    });

    expect(UserTypeEnum::hasMacro('toFlippedArray'))->toBeTrue();
    expect(array_flip(UserTypeEnum::toArray()))->toEqual(UserTypeEnum::toFlippedArray());
});

test('enum is macroable with instance methods', function () {
    // 给 Enum 扩展实例方法
    Enum::macro('macroGetValue', function () {
        return $this->value;
    });

    expect(UserTypeEnum::hasMacro('macroGetValue'))->toBeTrue();

    $user = new UserTypeEnum(UserTypeEnum::ADMINISTRATOR);
    expect($user->macroGetValue())->toBe(UserTypeEnum::ADMINISTRATOR);
});
