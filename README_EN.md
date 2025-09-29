<h1 align="center"> laravel-enum </h1>

> A simple and easy-to-use enumeration extension package to help you manage enumerations in your project more conveniently, supporting Laravel and Lumen.

[![Tests](https://github.com/jiannei/laravel-enum/actions/workflows/test.yml/badge.svg?branch=main)](https://github.com/jiannei/laravel-enum/actions/workflows/test.yml)
[![Coverage](https://github.com/jiannei/laravel-enum/actions/workflows/coverage.yml/badge.svg?branch=main)](https://github.com/jiannei/laravel-enum/actions/workflows/coverage.yml)
[![Laravel Pint](https://img.shields.io/badge/Laravel%20Pint-Enabled-brightgreen?style=flat&logo=laravel)](https://laravel.com/docs/pint)
[![Pest](https://img.shields.io/badge/Pest-Enabled-brightgreen?style=flat&logo=php)](https://pestphp.com)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%208-brightgreen?style=flat&logo=php)](https://phpstan.org)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue?style=flat&logo=php)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-10%7C11%7C12-red?style=flat&logo=laravel)](https://laravel.com)
[![Latest Stable Version](https://poser.pugx.org/jiannei/laravel-enum/v)](https://packagist.org/packages/jiannei/laravel-enum) 
[![Total Downloads](https://poser.pugx.org/jiannei/laravel-enum/downloads)](https://packagist.org/packages/jiannei/laravel-enum)
[![Monthly Downloads](https://poser.pugx.org/jiannei/laravel-enum/d/monthly)](https://packagist.org/packages/jiannei/laravel-enum)
[![License](https://poser.pugx.org/jiannei/laravel-enum/license)](https://packagist.org/packages/jiannei/laravel-enum)

[中文文档](README.md) | English

## Introduction

`laravel-enum` is primarily used to extend enumeration usage in projects. Through proper enumeration definitions, it makes code more standardized, easier to read and maintain.

PHP 8.1+ has built-in enumeration support. For more information, see: https://www.php.net/manual/en/language.enumerations.php

Join the community discussion: [A More Elegant Way to Write APIs - Enumeration Usage](https://learnku.com/articles/53015)

## Features

- Extends native BackedEnum with multi-language description support
- Provides various practical ways to instantiate enums and retrieve enum names and values
- Offers convenient comparison methods `is`, `isNot`, and `isAny` for enum instance comparisons

## Installation

Supports Laravel 10 and above:

```shell
$ composer require jiannei/laravel-enum -vvv
```

## Usage

For more detailed usage examples, check the test cases: [https://github.com/Jiannei/laravel-enum/tree/main/tests](https://github.com/Jiannei/laravel-enum/tree/main/tests)

### Basic Usage

#### Definition

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

#### Basic Operations

```php
// Get enum value
UserType::ADMINISTRATOR->value; // 0

// Get all defined enum names
$names = UserType::names(); // ['ADMINISTRATOR', 'MODERATOR', 'SUBSCRIBER']

// Get all defined enum values
$values = UserType::values(); // [0, 1, 2]
```

#### Enum Validation

```php
// Check if the defined enum contains a specific "enum value"
UserType::hasValue(1); // true
UserType::hasValue(-1); // false

// Check if the defined enum contains a specific "enum name"
UserType::hasName('MODERATOR'); // true
UserType::hasName('ADMIN'); // false
```

#### Enum Instantiation

After enum instantiation, you can conveniently access the enum's key, value, and description properties through the object instance.

```php
UserType::fromValue(0) // Instantiate by value

UserType::fromName('ADMINISTRATOR') // Instantiate by name

UserType::guess(2) // Guess by name/value
```

#### Comparison Methods

```php
$admin = UserType::ADMINISTRATOR;

// Check if equal to another enum
$admin->is(UserType::SUBSCRIBER); // false
$admin->is(UserType::ADMINISTRATOR); // true

// Check if not equal to another enum
$admin->isNot(UserType::SUBSCRIBER); // true

// Check if matches any enum in an array
$admin->isAny([UserType::ADMINISTRATOR, UserType::MODERATOR]); // true
```

#### Array Conversion

```php
// Convert to array with full information
$array = UserType::toArray();
/*
[
    ['name' => 'ADMINISTRATOR', 'value' => 0, 'description' => 'Administrator'],
    ['name' => 'MODERATOR', 'value' => 1, 'description' => 'Moderator'],
    ['name' => 'SUBSCRIBER', 'value' => 2, 'description' => 'Subscriber'],
]
*/

// Convert to select array (supports multi-language configuration)
$array = UserType::toSelectArray();
/*
[
    0 => 'Administrator',
    1 => 'Moderator',
    2 => 'Subscriber',
]
*/
```

### Multi-language Support

The package supports multi-language descriptions through Laravel's localization system.

#### Language File Configuration

Create language files in your Laravel application:

**resources/lang/en/enums.php**
```php
<?php

use Jiannei\Enum\Laravel\Tests\Enums\UserType;

return [
    UserType::class => [
        UserType::ADMINISTRATOR->name => 'Administrator',
        UserType::MODERATOR->name => 'Moderator',
        UserType::SUBSCRIBER->name => 'Subscriber',
    ],
];
```

**resources/lang/zh_CN/enums.php**
```php
<?php

use Jiannei\Enum\Laravel\Tests\Enums\UserType;

return [
    UserType::class => [
        UserType::ADMINISTRATOR->name => '管理员',
        UserType::MODERATOR->name => '主持人',
        UserType::SUBSCRIBER->name => '订阅用户',
    ],
];
```

#### Using Custom Localization Groups

```php
// Use custom localization group
$array = UserType::toSelectArray('custom_group');

// Get description with custom localization group
$description = UserType::ADMINISTRATOR->description('custom_group');
```

### Laravel Integration

#### Enum Conversion and Validation

- [Retrieving Enum Input Values](https://laravel.com/docs/11.x/requests#retrieving-enum-input-values)
- [Enum Validation Rule](https://laravel.com/docs/11.x/validation#rule-enum)

#### Enum Casting in Models

- [Enum Casting](https://laravel.com/docs/11.x/eloquent-mutators#enum-casting)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jiannei\Enum\Laravel\Tests\Enums\UserType;

class User extends Model
{
    protected $casts = [
        'type' => UserType::class,
    ];
}
```

## API Reference

### Instance Methods

| Method | Description | Return Type |
|--------|-------------|-------------|
| `name()` | Get the enum case name | `string` |
| `value()` | Get the enum case value | `int\|string` |
| `description($group = 'enums')` | Get localized description | `string` |
| `is(BackedEnum $enum)` | Check if equal to another enum | `bool` |
| `isNot(BackedEnum $enum)` | Check if not equal to another enum | `bool` |
| `isAny(array $enums)` | Check if matches any enum in array | `bool` |

### Static Methods

| Method | Description | Return Type |
|--------|-------------|-------------|
| `names()` | Get all enum case names | `array` |
| `values()` | Get all enum case values | `array` |
| `hasName(string $name, bool $strict = false)` | Check if name exists | `bool` |
| `hasValue(int\|string $value, bool $strict = false)` | Check if value exists | `bool` |
| `fromName(string $name)` | Create instance from name | `static` |
| `fromValue(int\|string $value)` | Create instance from value | `static` |
| `guess(int\|string $key)` | Create instance by guessing from name or value | `static` |
| `toArray(string $group = 'enums')` | Convert to array with full info | `array` |
| `toSelectArray(string $group = 'enums')` | Convert to select array format | `array` |

## Requirements

- PHP 8.1+
- Laravel 10.0+ or Lumen 10.0+

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email jiannei@sinan.fun instead of using the issue tracker.

## Credits

- [jiannei](https://github.com/jiannei)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.