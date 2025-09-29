<h1 align="center"> laravel-enum </h1>

> A simple and easy-to-use enumeration extension package to help you manage enumerations in your project more conveniently, supporting Laravel and Lumen.
> - 一个简单好用的枚举扩展包，帮助你更方便地管理项目中的枚举，支持 Laravel 和 Lumen。

[![Tests](https://github.com/jiannei/laravel-enum/actions/workflows/test.yml/badge.svg?branch=main)](https://github.com/jiannei/laravel-enum/actions/workflows/test.yml)
[![Coverage](https://github.com/jiannei/laravel-enum/actions/workflows/coverage.yml/badge.svg?branch=main)](https://github.com/jiannei/laravel-enum/actions/workflows/coverage.yml)
[![codecov](https://codecov.io/gh/jiannei/laravel-enum/branch/main/graph/badge.svg)](https://codecov.io/gh/jiannei/laravel-enum)
[![Laravel Pint](https://img.shields.io/badge/Laravel%20Pint-Enabled-brightgreen?style=flat&logo=laravel)](https://laravel.com/docs/pint)
[![Pest](https://img.shields.io/badge/Pest-Enabled-brightgreen?style=flat&logo=php)](https://pestphp.com)
[![Larastan](https://img.shields.io/badge/Larastan-Level%209-brightgreen?style=flat&logo=php)](https://github.com/larastan/larastan)
[![Latest Stable Version](https://poser.pugx.org/jiannei/laravel-enum/v)](https://packagist.org/packages/jiannei/laravel-enum) 
[![Total Downloads](https://poser.pugx.org/jiannei/laravel-enum/downloads)](https://packagist.org/packages/jiannei/laravel-enum)
[![Monthly Downloads](https://poser.pugx.org/jiannei/laravel-enum/d/monthly)](https://packagist.org/packages/jiannei/laravel-enum)
[![License](https://poser.pugx.org/jiannei/laravel-enum/license)](https://packagist.org/packages/jiannei/laravel-enum)

中文文档 | [English](README_EN.md)

## 介绍

`laravel-enum` 主要用来扩展项目中的枚举使用，通过合理的定义枚举可以使代码更加规范，更易阅读和维护。

php8.1 版本后内置枚举支持，更多信息可以查看：https://www.php.net/manual/zh/language.enumerations.php

参与社区讨论：- [教你更优雅地写 API 之「枚举使用」](https://learnku.com/articles/53015)

## 概览

- 🌍 **多语言支持**：扩展原生的 BackedEnum，支持多语言描述
- 🔧 **实例化方法**：提供多种实用的方式来实例化枚举（fromValue、fromName、guess、random）
- 📊 **数据获取**：便捷获取枚举 name、value、names、values、count 等信息
- 🔍 **比较方法**：提供便捷的比较方法 `is`、`isNot`、`isAny`，用于枚举实例之间的对比
- 🧭 **导航功能**：支持枚举位置检查（isFirst、isLast）和导航（next、previous）
- 📋 **数组转换**：支持转换为数组格式，适用于 API 响应和表单选项
- ⚠️ **错误处理**：提供详细的错误信息，包含有效值提示

## 安装

支持 Laravel 10 以上版本：

```shell
$ composer require jiannei/laravel-enum -vvv
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

### 基础使用

```php
// 获取枚举的值
UserType::ADMINISTRATOR->value; // 0

// 获取枚举的名称
UserType::ADMINISTRATOR->name(); // 'ADMINISTRATOR'

// 获取枚举的值（通过方法）
UserType::ADMINISTRATOR->value(); // 0

// 获取所有已定义枚举的名称
$names = UserType::names(); // ['ADMINISTRATOR', 'MODERATOR', 'SUBSCRIBER']

// 获取所有已定义枚举的值
$values = UserType::values(); // [0, 1, 2]

// 获取枚举数量
$count = UserType::count(); // 3
```

### 枚举校验

```php
// 检查定义的枚举中是否包含某个「枚举值」
UserType::hasValue(1); // true
UserType::hasValue(-1); // false

// 检查定义的枚举中是否包含某个「枚举名称」
UserType::hasName('MODERATOR'); // true
UserType::hasName('ADMIN'); // false
```

### 枚举实例化

枚举实例化以后可以方便地通过对象实例访问枚举的 name、value 以及 description 属性的值。

```php
// 通过 value 实例化
$admin = UserType::fromValue(0); // UserType::ADMINISTRATOR

// 通过 name 实例化
$admin = UserType::fromName('ADMINISTRATOR'); // UserType::ADMINISTRATOR

// 通过 name/value 智能猜测
$subscriber = UserType::guess(2); // UserType::SUBSCRIBER
$subscriber = UserType::guess('SUBSCRIBER'); // UserType::SUBSCRIBER

// 获取随机枚举实例
$random = UserType::random(); // 随机返回一个枚举实例
```

### 枚举比较

```php
$admin = UserType::ADMINISTRATOR;

// 检查是否等于某个枚举
$admin->is(UserType::ADMINISTRATOR); // true
$admin->is(UserType::SUBSCRIBER); // false

// 检查是否不等于某个枚举
$admin->isNot(UserType::SUBSCRIBER); // true
$admin->isNot(UserType::ADMINISTRATOR); // false

// 检查是否为多个枚举中的任意一个
$admin->isAny(UserType::ADMINISTRATOR, UserType::MODERATOR); // true
$admin->isAny(UserType::MODERATOR, UserType::SUBSCRIBER); // false
```

### 枚举位置和导航

```php
$admin = UserType::ADMINISTRATOR;
$moderator = UserType::MODERATOR;
$subscriber = UserType::SUBSCRIBER;

// 检查是否为第一个枚举
$admin->isFirst(); // true
$moderator->isFirst(); // false

// 检查是否为最后一个枚举
$subscriber->isLast(); // true
$admin->isLast(); // false

// 获取下一个枚举（如果是最后一个则返回 null）
$admin->next(); // UserType::MODERATOR
$moderator->next(); // UserType::SUBSCRIBER
$subscriber->next(); // null

// 获取上一个枚举（如果是第一个则返回 null）
$admin->previous(); // null
$moderator->previous(); // UserType::ADMINISTRATOR
$subscriber->previous(); // UserType::MODERATOR
```

### 数组转换

```php
// 转换为详细数组
$array = UserType::toArray();
/*
[
    ['name' => 'ADMINISTRATOR', 'value' => 0, 'description' => '管理员'],
    ['name' => 'MODERATOR', 'value' => 1, 'description' => '主持人'],
    ['name' => 'SUBSCRIBER', 'value' => 2, 'description' => '订阅用户'],
]
*/

// 转换为选择数组（适用于下拉框等场景）
$selectArray = UserType::toSelectArray(); // 支持多语言配置
/*
[
    0 => '管理员',
    1 => '主持人',
    2 => '订阅用户',
]
*/

// 指定本地化组
$selectArray = UserType::toSelectArray('*');
/*
[
    0 => '管理员',
    1 => '监督员',
    2 => '订阅用户',
]
*/
```

### 多语言描述

枚举支持多语言描述，通过 `description()` 方法获取：

```php
// 获取默认语言描述
UserType::ADMINISTRATOR->description(); // '管理员'

// 获取指定本地化组的描述
UserType::ADMINISTRATOR->description('*'); // 可能返回不同的翻译

// 在数组转换中使用多语言
$array = UserType::toArray('custom_group');
$selectArray = UserType::toSelectArray('custom_group');
```

### 错误处理

当使用无效的名称或值时，会抛出详细的错误信息：

```php
try {
    UserType::fromName('INVALID_NAME');
} catch (ValueError $e) {
    // 错误信息会包含所有有效的枚举名称
    echo $e->getMessage(); // "Invalid enum name "INVALID_NAME". Valid names are: ADMINISTRATOR, MODERATOR, SUBSCRIBER"
}

try {
    UserType::fromValue(999);
} catch (ValueError $e) {
    // 错误信息会包含所有有效的枚举值
    echo $e->getMessage(); // "Invalid enum backing value "999". Valid values are: 0, 1, 2"
}
```

### 实际应用示例

```php
// 在控制器中使用
class UserController extends Controller
{
    public function index(Request $request)
    {
        $userType = UserType::fromValue($request->input('type', 0));
        
        $users = User::where('type', $userType->value)->get();
        
        return response()->json([
            'users' => $users,
            'current_type' => [
                'name' => $userType->name(),
                'value' => $userType->value(),
                'description' => $userType->description(),
                'is_admin' => $userType->is(UserType::ADMINISTRATOR),
            ]
        ]);
    }
    
    public function getTypes()
    {
        return response()->json([
            'types' => UserType::toSelectArray(),
            'count' => UserType::count(),
        ]);
    }
}

// 在模型中使用
class User extends Model
{
    protected $casts = [
        'type' => UserType::class,
    ];
    
    public function isAdmin(): bool
    {
        return $this->type->is(UserType::ADMINISTRATOR);
    }
    
    public function canModerate(): bool
    {
        return $this->type->isAny(UserType::ADMINISTRATOR, UserType::MODERATOR);
    }
    
    public function getNextRole(): ?UserType
    {
        return $this->type->next();
    }
}

// 在表单验证中使用
class CreateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => ['required', 'integer', Rule::in(UserType::values())],
        ];
    }
}
```

### 枚举转换和校验

- https://laravel.com/docs/11.x/requests#retrieving-enum-input-values
- https://laravel.com/docs/11.x/validation#rule-enum


### Model 中的枚举转换

- https://laravel.com/docs/11.x/eloquent-mutators#enum-casting

## API 参考

### 静态方法

| 方法 | 返回类型 | 描述 |
|------|----------|------|
| `names()` | `array<string>` | 获取所有枚举名称 |
| `values()` | `array<int\|string>` | 获取所有枚举值 |
| `count()` | `int` | 获取枚举数量 |
| `hasName(string $name)` | `bool` | 检查是否包含指定名称 |
| `hasValue(mixed $value)` | `bool` | 检查是否包含指定值 |
| `fromName(string $name)` | `static` | 通过名称实例化 |
| `fromValue(mixed $value)` | `static` | 通过值实例化 |
| `guess(mixed $value)` | `static` | 智能猜测实例化 |
| `random()` | `static` | 获取随机枚举实例 |
| `toArray(?string $localizationGroup = null)` | `array` | 转换为详细数组 |
| `toSelectArray(?string $localizationGroup = null)` | `array` | 转换为选择数组 |

### 实例方法

| 方法 | 返回类型 | 描述 |
|------|----------|------|
| `name()` | `string` | 获取枚举名称 |
| `value()` | `int\|string` | 获取枚举值 |
| `description(?string $localizationGroup = null)` | `string` | 获取描述 |
| `is(BackedEnum $enum)` | `bool` | 检查是否等于指定枚举 |
| `isNot(BackedEnum $enum)` | `bool` | 检查是否不等于指定枚举 |
| `isAny(BackedEnum ...$enums)` | `bool` | 检查是否为任意指定枚举 |
| `isFirst()` | `bool` | 检查是否为第一个枚举 |
| `isLast()` | `bool` | 检查是否为最后一个枚举 |
| `next()` | `?static` | 获取下一个枚举 |
| `previous()` | `?static` | 获取上一个枚举 |

## License

MIT