<?php

uses(\Jiannei\Enum\Laravel\Tests\TestCase::class);
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;


beforeEach(function () {
    Config::set('enum', [
        'localization' => ['key' => 'enums1'], 'transformations' => ['user_type' => UserTypeEnum::class],
    ]);

    $this->withoutMiddleware();
});

test('has transform enums macro', function () {
    // 检查是否成功通过 EnumServiceProvider 向原始的 Request 中注入 transformEnums 方法
    // \App\Support\Enum\Http
    expect(Request::hasMacro('transformEnums'))->toBeTrue();
});

test('can transform a request get parameter to enum by key', function () {
    // 请求中包含枚举参数时，可以通过定义参数与枚举对象之间的对应关系，从而将参数值转换成枚对应的举实例
    // 根据「枚举的名称」转换
    // 比如，客户端发起了一个 request，参数中包含 user_type = 'MODERATOR'，我们希望可以在 Controller 中的方法中更为便捷的操作这个枚举
    // GET 方式：request 参数中包含 user_type
    $this->call('GET', 'test/enums', ['user_type' => 'SUBSCRIBER']);
    $request = $this->app['request'];

    // 在 /config/enum.php 的 transformations 中定义参数和枚举对象的对应关系
    // user_type => UserTypeEnum::class
    $request->transformEnums(['user_type' => UserTypeEnum::class], true);

    // 通过转换成枚举实例，就可以直接使用枚举实例中的属性和方法
    $userType = $request->get('user_type');

    // UserTypeEnum::class
    expect($userType)->toBeInstanceOf(UserTypeEnum::class);
    expect($userType->key)->toEqual(UserTypeEnum::getKey(UserTypeEnum::SUBSCRIBER));
    expect($userType->value)->toEqual(UserTypeEnum::SUBSCRIBER);
    expect($userType->description)->toEqual(UserTypeEnum::getDescription(UserTypeEnum::SUBSCRIBER));
    expect($userType->is(UserTypeEnum::SUBSCRIBER))->toBeTrue();
});

test('can transform a request get parameter to enum by value', function () {
    // 根据「枚举的值」进行转换
    // GET 方式：request 参数中包含 user_type
    $this->call('GET', 'test/enums', ['user_type' => 2]);
    $request = $this->app['request'];

    // user_type => UserTypeEnum::class
    $request->transformEnums(['user_type' => UserTypeEnum::class], true);

    // 通过转换成枚举实例，就可以直接使用枚举实例中的属性和方法
    $userType = $request->get('user_type');

    // UserTypeEnum::class
    expect($userType)->toBeInstanceOf(UserTypeEnum::class);
    expect($userType->key)->toEqual('SUBSCRIBER');
});

test('an exception is thrown if an non existing enum value is passed', function () {
    $this->call('GET', 'test/enums', ['user_type' => '2']);
    // 字符串类型的 2
    $request = $this->app['request'];

    $request->transformEnums(['user_type' => UserTypeEnum::class], true);

    // strict 为 true
    $userType = $request->get('user_type');

    $this->assertNotInstanceOf(UserTypeEnum::class, $userType);
    expect($userType)->toEqual('2');
    // 转换失败，返回字符串原始值 '2'
});

test('can transform a request get parameter to enum by key without strict type checking', function () {
    $this->call('GET', 'test/enums', ['user_type' => 'subscriber']);
    // 或者 Subscriber，不区分大小写
    $request = $this->app['request'];

    $request->transformEnums(['user_type' => UserTypeEnum::class], false);

    // 设置 strict 为 false，不严格校验参数值的小写或类型
    $userType = $request->get('user_type');

    // 转换成功，UserTypeEnum::class
    expect($userType)->toBeInstanceOf(UserTypeEnum::class);
    expect($userType->key)->toEqual('SUBSCRIBER');
});

test('can transform a request get parameter to enum by value without strict type checking', function () {
    $this->call('GET', 'test/enums', ['user_type' => '2']);
    $request = $this->app['request'];

    // user_type => UserTypeEnum::class
    $request->transformEnums(['user_type' => UserTypeEnum::class], false);

    // 设置 strict 为 false，不严格校验参数值的小写或类型
    $userType = $request->get('user_type');

    // 转换成功，UserTypeEnum::class
    expect($userType)->toBeInstanceOf(UserTypeEnum::class);
    expect($userType->key)->toEqual('SUBSCRIBER');
});

test('can transform a request post parameter to enum by key', function () {
    // 根据「枚举的名称」进行转换
    // POST 方式：request 参数中包含 user_type
    $this->call('POST', 'test/enums', ['user_type' => 'SUBSCRIBER']);
    $request = $this->app['request'];

    // user_type => UserTypeEnum::class
    $request->transformEnums(['user_type' => UserTypeEnum::class], true);

    expect($request->get('user_type'))->toBeInstanceOf(UserTypeEnum::class);
    expect($request->get('user_type')->key)->toEqual('SUBSCRIBER');
});

test('can transform a request post parameter to enum by value', function () {
    // 根据「枚举的值」进行转换
    // POST 方式：request 参数中包含 user_type
    $this->call('POST', 'test/enums', ['user_type' => 1]);
    $request = $this->app['request'];

    // user_type => UserTypeEnum::class
    $request->transformEnums(['user_type' => UserTypeEnum::class], true);

    expect($request->get('user_type'))->toBeInstanceOf(UserTypeEnum::class);
    expect($request->get('user_type')->key)->toEqual('MODERATOR');
});
