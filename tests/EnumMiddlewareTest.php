<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

uses(\Jiannei\Enum\Laravel\Tests\TestCase::class);
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Jiannei\Enum\Laravel\Http\Middleware\TransformEnums;
use Jiannei\Enum\Laravel\Tests\Enums\UserTypeEnum;

beforeEach(function () {
    Config::set('enum', [
        'localization' => ['key' => 'enums'], 'transformations' => ['user_type' => UserTypeEnum::class],
    ]);

    $this->withoutMiddleware();
});

test('can transform a request parameter to enum by key', function () {
    // 通过 TransformEnums 中间件，将请求参数转换成枚举实例（需要预先定义好参数和枚举对象之间的对应关系）
    // 根据枚举名称来转换
    $this->post('test/enums', [
        'user_type' => 'MODERATOR',
    ]);

    (new TransformEnums())->handle($this->app['request'], function (Request $request) {
        expect($request->get('user_type'))->toBeInstanceOf(UserTypeEnum::class);
        expect($request['user_type']->key)->toEqual('MODERATOR');
    });
});

test('can transform a request parameter to enum by value', function () {
    // 通过 TransformEnums 中间件，将请求参数转换成枚举实例（需要预先定义好参数和枚举对象之间的对应关系）
    // 根据枚举值来转换
    $this->post('test/enums', [
        'user_type' => 1,
    ]);

    (new TransformEnums())->handle($this->app['request'], function (Request $request) {
        expect($request->get('user_type'))->toBeInstanceOf(UserTypeEnum::class);
        expect($request['user_type']->key)->toEqual('MODERATOR');
    });
});

test('can transform a request parameter to enum by key without strict type checking', function () {
    // 通过 TransformEnums 中间件，将请求参数转换成枚举实例（需要预先定义好参数和枚举对象之间的对应关系）
    // 根据枚举名称来转换
    $this->post('test/enums', [
        'user_type' => 'moderator', // strict 为 true 时，这里必须和枚举名称大小写一样
    ]);

    (new TransformEnums())->handle($this->app['request'], function (Request $request) {
        expect($request->get('user_type'))->toBeInstanceOf(UserTypeEnum::class);
        expect($request['user_type']->key)->toEqual('MODERATOR');
    }, false);
    // 设置 strict 为 false
});

test('can transform a request parameter to enum by value without strict type checking', function () {
    // 通过 TransformEnums 中间件，将请求参数转换成枚举实例（需要预先定义好参数和枚举对象之间的对应关系）
    // 根据枚举值来转换
    $this->post('test/enums', [
        'user_type' => '1', // strict 为 true 时，这里必须和枚举名值类型一样
    ]);

    (new TransformEnums())->handle($this->app['request'], function (Request $request) {
        expect($request->get('user_type'))->toBeInstanceOf(UserTypeEnum::class);
        expect($request['user_type']->key)->toEqual('MODERATOR');
    }, false);
    // 设置 strict 为 false
});
