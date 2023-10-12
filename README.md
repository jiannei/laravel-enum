<h1 align="center"> laravel-enum </h1>

> A simple and easy-to-use enumeration extension package to help you manage enumerations in your project more conveniently, supporting Laravel and Lumen.
> - 一个简单好用的枚举扩展包，帮助你更方便地管理项目中的枚举，支持 Laravel 和 Lumen。

![Test](https://github.com/Jiannei/laravel-enum/workflows/Test/badge.svg?branch=main)
[![StyleCI](https://github.styleci.io/repos/316907996/shield?branch=main)](https://github.styleci.io/repos/316907996?branch=main)
[![Latest Stable Version](http://poser.pugx.org/jiannei/laravel-enum/v)](https://packagist.org/packages/jiannei/laravel-enum) 
[![Total Downloads](http://poser.pugx.org/jiannei/laravel-enum/downloads)](https://packagist.org/packages/jiannei/laravel-enum)
[![Monthly Downloads](http://poser.pugx.org/jiannei/laravel-enum/d/monthly)](https://packagist.org/packages/jiannei/laravel-enum)
[![Latest Unstable Version](http://poser.pugx.org/jiannei/laravel-enum/v/unstable)](https://packagist.org/packages/jiannei/laravel-enum) 
[![License](http://poser.pugx.org/jiannei/laravel-enum/license)](https://packagist.org/packages/jiannei/laravel-enum)

## 介绍

`laravel-enum` 主要用来扩展项目中的枚举使用，通过合理的定义枚举可以使代码更加规范，更易阅读和维护。

php8.1 版本后内置枚举支持，更多信息可以查看：https://www.php.net/manual/zh/language.enumerations.php

## 概览

- 扩展原生的 BackedEnum
- 提供更多种实用的方式来实例化枚举、枚举 name、value 取值
- 支持多语言本地化描述
- 提供了便捷的比较方法`is`、`isNot`和`in`，用于枚举实例之间的对比

## 安装

支持 Laravel 10/Lumen 10 以上版本：

```shell
$ composer require jiannei/laravel-enum -vvv
```

### 配置项说明

```php
// config/enum.php

return [
    'localization' => [
        'key' => env('ENUM_LOCALIZATION_KEY', 'enums'),// 语言包的文件名。如果 APP_LOCALE=zh_CN，则这里对应 resources/lang/zh_CN/enums.php
    ],
];

```

### Laravel

- 发布配置文件

```shell
$ php artisan vendor:publish --provider="Jiannei\Enum\Laravel\Providers\LaravelServiceProvider"
```

### Lumen

- 复制配置文件到 `vendor/jiannei/laravel-enum/config/enum.php`，到 `config/enum.php`

```bash
cp vendor/jiannei/laravel-enum/config/enum.php config/enum.php
```

- 加载配置

```php
// bootstrap/app.php
$app->configure('enum');
```

- 注册服务容器

```php
$app->register(\Jiannei\Enum\Laravel\Providers\LumenServiceProvider::class);
```

## 使用

更为具体的使用可以查看测试用例：[https://github.com/Jiannei/laravel-enum/tree/main/tests](https://github.com/Jiannei/laravel-enum/tree/main/tests)

### 常规使用

- 定义

```php
<?php

namespace Jiannei\Enum\Laravel\Tests\Enums;

use Jiannei\Enum\Laravel\Support\Traits\EnumEnhance;

enum UserType: int
{
    use EnumEnhance;

    case ADMINISTRATOR = 0;
    case MODERATOR = 1;
    case SUBSCRIBER = 2;
}
```

- 使用

```php
// 获取枚举的值
UserTypeEnum::ADMINISTRATOR->value;// 0

// 获取所有已定义枚举的名称
$names = UserTypeEnum::names();// ['ADMINISTRATOR', 'MODERATOR', 'SUBSCRIBER']

// 获取所有已定义枚举的值
$values = UserTypeEnum::values();// [0, 1, 2]
```

- 本地化描述

```php

// 1. 不存在语言包的情况，返回较为友好的英文描述

// 2. 在 resource/lang/zh_CN/enums.php 中定义枚举与描述的对应关系（enums.php 文件名称可以在 config/enum.php 文件中配置）


```

- 枚举校验

```php
// 检查定义的枚举中是否包含某个「枚举值」
UserTypeEnum::hasValue(1);// true
UserTypeEnum::hasValue(-1);// false

// 检查定义的枚举中是否包含某个「枚举名称」 

UserTypeEnum::hasName('MODERATOR');// true
UserTypeEnum::hasName('ADMIN');// false
```

- 枚举实例化：枚举实例化以后可以方便地通过对象实例访问枚举的 key、value 以及 description 属性的值。

```php

```

- toArray

```php
$array = UserTypeEnum::toArray();

```

- toSelectArray

```php
$array = UserTypeEnum::toSelectArray();// 支持多语言配置

/*
[
    0 => '管理员',
    1 => '监督员',
    2 => '订阅用户',
]
*/
```

### 枚举转换和校验


### Model 中的枚举转换


## License

MIT