<h1 align="center"> laravel-enum </h1>

> A simple and easy-to-use enumeration extension package to help you manage enumerations in your project more conveniently, supporting Laravel and Lumen.

[![Tests](https://github.com/jiannei/laravel-enum/actions/workflows/test.yml/badge.svg?branch=main)](https://github.com/jiannei/laravel-enum/actions/workflows/test.yml)
[![Coverage](https://github.com/jiannei/laravel-enum/actions/workflows/coverage.yml/badge.svg?branch=main)](https://github.com/jiannei/laravel-enum/actions/workflows/coverage.yml)
[![Laravel Pint](https://img.shields.io/badge/Laravel%20Pint-Enabled-brightgreen?style=flat&logo=laravel)](https://laravel.com/docs/pint)
[![Pest](https://img.shields.io/badge/Pest-Enabled-brightgreen?style=flat&logo=php)](https://pestphp.com)
[![Larastan](https://img.shields.io/badge/Larastan-Level%209-brightgreen?style=flat&logo=php)](https://github.com/larastan/larastan)
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

- 🌍 **Multi-language Support**: Extends native BackedEnum with multi-language description support
- 🔧 **Instantiation Methods**: Provides various practical ways to instantiate enums (fromValue, fromName, guess, random)
- 📊 **Data Retrieval**: Convenient access to enum name, value, names, values, count and other information
- 🔍 **Comparison Methods**: Provides convenient comparison methods `is`, `isNot`, `isAny` for enum instance comparisons
- 🧭 **Navigation Features**: Supports enum position checking (isFirst, isLast) and navigation (next, previous)
- 📋 **Array Conversion**: Supports conversion to array formats, suitable for API responses and form options
- ⚠️ **Error Handling**: Provides detailed error messages with valid value hints

## Compatibility

| Laravel | Recommended version | PHP | Status |
|---------|---------------------|-----|--------|
| 13.x | `^5.0` | 8.3 / 8.4 / 8.5 | ✅ CI tested |
| 12.x | `^5.0` | 8.2 / 8.3 / 8.4 / 8.5 | ✅ CI tested |
| 11.x | `^5.0` | 8.2 / 8.3 / 8.4 | ✅ CI tested |
| 10.x | `^5.0` | 8.1 / 8.2 / 8.3 | ✅ CI tested |
| 9.x | `^5.0` | 8.1 / 8.2 | ✅ CI tested |
| 5.5 – 8.x | `^1.0` / `^2.0` / `^3.0` | 7.x | ⛔ EOL, legacy versions only |
| Lumen 9 – 11 | `^5.0` | 8.1 – 8.4 | ✅ Smoke tested (CI covers 10/11) |

> Native PHP enums require PHP 8.1+, so Laravel 5.5–8 cannot use the 5.x line. See [docs/COMPATIBILITY.md](docs/COMPATIBILITY.md) for the full version/branch/EOL matrix.

## Installation

```shell
$ composer require jiannei/laravel-enum -vvv
```

Localized descriptions require `illuminate/translation` (included in Laravel by default). Lumen or minimal installations must require it explicitly:

```shell
$ composer require illuminate/translation
```

## Usage

For more detailed usage examples, check the test cases: [https://github.com/Jiannei/laravel-enum/tree/main/tests](https://github.com/Jiannei/laravel-enum/tree/main/tests)

### Enum Definition

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

### Basic Usage

#### Getting Enum Names and Values

```php
// Get all defined enum names
$names = UserType::names(); // ['ADMINISTRATOR', 'MODERATOR', 'SUBSCRIBER']

// Get all defined enum values
$values = UserType::values(); // [0, 1, 2]

// Get enum count
$count = UserType::count(); // 3

// Get enum value
UserType::ADMINISTRATOR->value; // 0

// Get enum name
UserType::ADMINISTRATOR->name; // 'ADMINISTRATOR'
```

### Enum Validation

```php
// Check if the defined enum contains a specific "enum value"
UserType::hasValue(1); // true
UserType::hasValue(-1); // false

// Check if the defined enum contains a specific "enum name"
UserType::hasName('MODERATOR'); // true
UserType::hasName('ADMIN'); // false
```

### Enum Instantiation

After enum instantiation, you can conveniently access the enum's key, value, and description properties through the object instance.

```php
// Instantiate by value
$admin = UserType::fromValue(0); // UserType::ADMINISTRATOR

// Instantiate by name
$admin = UserType::fromName('ADMINISTRATOR'); // UserType::ADMINISTRATOR

// Guess by name/value (automatically determines whether it's a name or value)
$admin = UserType::guess(0); // UserType::ADMINISTRATOR
$admin = UserType::guess('ADMINISTRATOR'); // UserType::ADMINISTRATOR

// Get random enum instance
$random = UserType::random(); // Randomly returns one of UserType::ADMINISTRATOR, UserType::MODERATOR, UserType::SUBSCRIBER
```

### Enum Comparison

```php
$admin = UserType::ADMINISTRATOR;

// Check if equal to another enum
$admin->is(UserType::SUBSCRIBER); // false
$admin->is(UserType::ADMINISTRATOR); // true

// Check if not equal to another enum
$admin->isNot(UserType::SUBSCRIBER); // true

// Check if matches any enum
$admin->isAny(UserType::ADMINISTRATOR, UserType::MODERATOR); // true
```

### Position and Navigation

```php
$admin = UserType::ADMINISTRATOR;
$moderator = UserType::MODERATOR;
$subscriber = UserType::SUBSCRIBER;

// Check if it's the first enum
$admin->isFirst(); // true
$moderator->isFirst(); // false

// Check if it's the last enum
$subscriber->isLast(); // true
$moderator->isLast(); // false

// Get next enum (returns null if it's the last one)
$admin->next(); // UserType::MODERATOR
$subscriber->next(); // null

// Get previous enum (returns null if it's the first one)
$moderator->previous(); // UserType::ADMINISTRATOR
$admin->previous(); // null
```

### Array Conversion

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

### Multi-language Description

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

#### Using Descriptions

```php
// Get description (default uses 'enums' localization group)
$description = UserType::ADMINISTRATOR->description(); // 'Administrator'

// Use custom localization group
$description = UserType::ADMINISTRATOR->description('custom_group');

// Convert to select array with descriptions
$array = UserType::toSelectArray(); // [0 => 'Administrator', 1 => 'Moderator', 2 => 'Subscriber']
```

### Error Handling

When using `fromName` and `fromValue` methods with invalid parameters, detailed error messages are provided:

```php
try {
    UserType::fromName('INVALID_NAME');
} catch (ValueError $e) {
    echo $e->getMessage();
    // "INVALID_NAME" is not a valid name for enum "Jiannei\Enum\Laravel\Tests\Enums\UserType". Valid names are: ADMINISTRATOR, MODERATOR, SUBSCRIBER
}

try {
    UserType::fromValue(999);
} catch (ValueError $e) {
    echo $e->getMessage();
    // 999 is not a valid value for enum "Jiannei\Enum\Laravel\Tests\Enums\UserType". Valid values are: 0, 1, 2
}
```

### Practical Application Examples

#### In Controllers

```php
<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Get user type from request
        $userType = UserType::fromValue($request->input('type'));
        
        // Use enum for business logic
        if ($userType->is(UserType::ADMINISTRATOR)) {
            // Administrator logic
        }
        
        return response()->json([
            'user_types' => UserType::toSelectArray(),
            'current_type' => $userType->value,
        ]);
    }
}
```

#### In Models

```php
<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Model;

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
        return $this->type->isAny(
            UserType::ADMINISTRATOR,
            UserType::MODERATOR,
        );
    }
}
```

#### In Form Validation

```php
<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'type' => ['required', Rule::enum(UserType::class)],
        ];
    }
}
```

### Laravel Integration

#### Enum Conversion and Validation

- [Retrieving Enum Input Values](https://laravel.com/docs/13.x/requests#retrieving-enum-input-values)
- [Enum Validation Rule](https://laravel.com/docs/13.x/validation#rule-enum)

#### Enum Casting in Models

- [Enum Casting](https://laravel.com/docs/13.x/eloquent-mutators#enum-casting)

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

See `src/Support/Traits/EnumEnhance.php` for the complete signatures, parameter types and behavior contract. The methods used most often:

| Method | Description |
|--------|-------------|
| `names()` / `values()` | All case names / backing values |
| `fromName($name)` / `fromValue($value)` | Resolve a case by name / value, throws `ValueError` |
| `guess($key)` | Resolve by name first, then by value |
| `random()` | A random case |
| `description($group = 'enums')` | Localized description, falls back to the case name |
| `is()` / `isAny()` | Compare cases |
| `toArray()` / `toSelectArray()` | Array / select options |

Navigation helpers (`isFirst()`, `isLast()`, `next()`, `previous()`) and the `Description` signatures live in the same source files.

## Requirements

- PHP 8.1+
- Laravel 9.0+ or Lumen 9.0+

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