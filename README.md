<h1 align="center"> laravel-enum </h1>

<p align="center"> 一个简单好用的枚举扩展包，帮助你更方便地管理项目中的枚举，支持 Laravel 和 Lumen。</p>

![Test](https://github.com/Jiannei/laravel-enum/workflows/Test/badge.svg?branch=main)
[![StyleCI](https://github.styleci.io/repos/316907996/shield?branch=main)](https://github.styleci.io/repos/316907996?branch=main)

**社区讨论**：[教你更优雅地写 API 之「枚举使用」](https://learnku.com/articles/53015)

## 介绍

`laravel-enum` 主要用来扩展项目中的常量使用，通过合理的定义常量可以使代码更加规范，更易阅读和维护。

## 概览

- 提供了多种实用的方式来实例化枚举
- 支持多语言本地化描述
- 支持表单验证，提供验证规则 enum，enum_key 和 enum_value，对请求参数中的参数进行枚举校验
- 支持路由中间件自动将 Request 参数转换成相应枚举实例
- 支持 `Eloquent\Model` 中的 `$casts` 特性，将查询出的数据自动转换成枚举实例
- 提供了便捷的比较方法`is`、`isNot`和`in`，用于枚举实例之间的对比
- 内置了多种实用的枚举集：
    - 标准的 Http 状态码枚举定义，方便在 API 返回响应数据时设置 Http 状态码；
    - `CacheEnum` 缓存枚举定义，一种统一项目中缓存 key 和缓存过期时间定义的方案；
    - `LogEnum` 日志枚举定义，用于规范日志记录时的描述内容

## 安装

支持 Laravel 8/Lumen 8 以上版本：

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

    // 通过 transform 中间件将 request 中的参数转换成枚举实例时定义对应关系
    // 你可以将请求参数中用到的枚举定义在下面，通过中间件，将会被自动转换成枚举类
    'transformations' => [
        // 参数名 => 对应的枚举类

    ],
];

```

### Laravel

- 发布配置文件

```shell
$ php artisan vendor:publish --provider="Jiannei\Enum\Laravel\Providers\LaravelServiceProvider"
```

- 添加路由中间件

```php
// app/Http/Kernel.php

<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // ...
    protected $routeMiddleware = [
        'enum' => \Jiannei\Enum\Laravel\Http\Middleware\TransformEnums::class // 加到这里
    ];
}
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

- 添加路由中间件

```php
$app->routeMiddleware([
    'enum' => \Jiannei\Enum\Laravel\Http\Middleware\TransformEnums::class,
]);
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

namespace App\Repositories\Enums;

use Jiannei\Enum\Laravel\Enum;

use Jiannei\Enum\Laravel\Enum;

final class UserTypeEnum extends Enum
{
    const ADMINISTRATOR = 0;

    const MODERATOR = 1;

    const SUBSCRIBER = 2;

    const SUPER_ADMINISTRATOR = 3;
}
```

- 使用

```php
// 获取常量的值
UserTypeEnum::ADMINISTRATOR;// 0

// 获取所有已定义常量的名称
$keys = UserTypeEnum::getKeys();// ['ADMINISTRATOR', 'MODERATOR', 'SUBSCRIBER', 'SUPER_ADMINISTRATOR']

// 根据常量的值获取常量的名称
UserTypeEnum::getKey(1);// MODERATOR

// 获取所有已定义常量的值
$values = UserTypeEnum::getValues();// [0, 1, 2, 3]

// 根据常量的名称获取常量的值
UserTypeEnum::getValue('MODERATOR');// 1
```

- 本地化描述

```php

// 1. 不存在语言包的情况，返回较为友好的英文描述
UserTypeEnum::getDescription(UserTypeEnum::ADMINISTRATOR);// Administrator

// 2. 在 resource/lang/zh_CN/enums.php 中定义常量与描述的对应关系（enums.php 文件名称可以在 config/enum.php 文件中配置）
ExampleEnum::getDescription(ExampleEnum::ADMINISTRATOR);// 管理员

// 补充：也可以先实例化常量对象，然后再根据对象实例来获取常量描述
$responseEnum = new ExampleEnum(ExampleEnum::ADMINISTRATOR);
$responseEnum->description;// 管理员

// 其他方式
ExampleEnum::ADMINISTRATOR()->description;// 管理员

```

- 枚举校验

```php
// 检查定义的常量中是否包含某个「常量值」
UserTypeEnum::hasValue(1);// true
UserTypeEnum::hasValue(-1);// false

// 检查定义的常量中是否包含某个「常量名称」 

UserTypeEnum::hasKey('MODERATOR');// true
UserTypeEnum::hasKey('ADMIN');// false
```

- 枚举实例化：枚举实例化以后可以方便地通过对象实例访问枚举的 key、value 以及 description 属性的值。

```php
// 方式一：new 传入常量的值
$administrator1 = new UserTypeEnum(UserTypeEnum::ADMINISTRATOR);

// 方式二：fromValue
$administrator2 = UserTypeEnum::fromValue(0);

// 方式三：fromKey
$administrator3 = UserTypeEnum::fromKey('ADMINISTRATOR');

// 方式四：magic
$administrator4 = UserTypeEnum::ADMINISTRATOR();

// 方式五：make，尝试根据「常量的值」或「常量的名称」实例化对象常量，实例失败时返回原先传入的值
$administrator5 = UserTypeEnum::make(0); // 此处尝试根据「常量的值」实例化
$administrator6 = UserTypeEnum::make('ADMINISTRATOR'); // 此处尝试根据「常量的名称」实例化
```

- 枚举实例化进阶：（TransfrormEnums 中间件自动转换请求参数为枚举实例，使用的便是下面的 make 方法）

```php
$administrator2 = UserTypeEnum::make('ADMINISTRATOR');// strict 默认为 true；准备被实例化

$administrator3 = UserTypeEnum::make(0);// strict 默认为 true；准备被实例化

// 注意：这里的 0 是字符串类型，而原先定义的是数值类型
$administrator4 = UserTypeEnum::make('0', false); // strict 设置为 false，不校验传入值的类型；会被准确实例化

// 注意：这里的 AdminiStrator 是大小写混乱的
$administrator6 = UserTypeEnum::make('AdminiStrator', false); // strict 设置为 false，不校验传入值的大小写；会被准确实例化
```

- 随机获取

```php
// 随机获取一个常量的值
UserTypeEnum::getRandomValue();

// 随机获取一个常量的名称
UserTypeEnum::getRandomKey();

// 随机获取一个枚举实例
UserTypeEnum::getRandomInstance()
```

- toArray

```php
$array = UserTypeEnum::toArray();

/*
[
    'ADMINISTRATOR' => 0,
    'MODERATOR' => 1,
    'SUBSCRIBER' => 2,
    'SUPER_ADMINISTRATOR' => 3,
]
*/
```

- toSelectArray

```php
$array = UserTypeEnum::toSelectArray();// 支持多语言配置

/*
[
    0 => '管理员',
    1 => '监督员',
    2 => '订阅用户',
    3 => '超级管理员',
]
*/
```

### 枚举转换和校验

这一部分通过一个需求场景来描述：用户登录 API 需要校验传入的 identity_type 是否合法，并且根据不同的值调用不同的登录逻辑。

- 定义 IdentityTypeEnum

```php
<?php

namespace App\Repositories\Enums;

use Jiannei\Enum\Laravel\Contracts\LocalizedEnumContract;
use Jiannei\Enum\Laravel\Enum;

class IdentityTypeEnum extends Enum implements LocalizedEnumContract
{
    const NAME = 1;
    const EMAIL = 2;
    const PHONE = 3;
    const GITHUB = 4;
    const WECHAT = 5;
}
```

- `app/Http/Kernel.php` 中添加路由中间件

```php
protected $routeMiddleware = [

		// ...
    'enum' => \Jiannei\Enum\Laravel\Http\Middleware\TransformEnums::class
];
```

- `config/enum.php` 中配置 **Request 参数**和**枚举**之间的转换关系：参数 ⇒ 枚举

```php
<?php

use App\Repositories\Enums\IdentityTypeEnum;

return [
    'localization' => [
        'key' => env('ENUM_LOCALIZATION_KEY', 'enums'),
    ],

    // 你可以将请求参数中用到的枚举定义在下面，通过中间件，将会被自动转换成枚举类
    'transformations' => [
        // 参数名 => 对应的枚举类
        'identity_type' => IdentityTypeEnum::class,
    ],
];

```

- Controller 中使用

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Enums\IdentityTypeEnum;
use App\Services\AuthorizationService;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

class AuthorizationController extends Controller
{
    private AuthorizationService $service;

    public function __construct(AuthorizationService $service)
    {
        $this->middleware('auth:api', ['except' => ['store',]]);
        $this->middleware('enum:false');// 请求参数中包含枚举时转换过程中不区分大小写

        $this->service = $service;
    }

		// 假设客户端 POST 方式传入下面参数：（注意，这里 identity_type 传入的值是 email）
		
		/*
		{
		    "identity_type":"email",// 对应 IdentityTypeEnum 中的 EMAIL，这里为小写形式
		    "account":"longjian.huang@foxmail.com",
		    "password":"password",
		    "remember":false
		}
		*/
    public function store(Request $request)
    {
        $this->validate($request, [
           'identity_type' => 'required|enum:'.IdentityTypeEnum::class,// 校验传入的 identity_type 是否能够被实例化成枚举 
		        'account' => 'required|string|max:64|unique:users,account', // 账号
		        'password' => 'required|min:8', // 密码
		        'remember' => 'boolean', // 记住我
        ]);

				// identity_type 为 github 时走 Github 登录
				// $request->get('identity_type') 为 IdentityTypeEnum 实例，可以调用对象中的方法
        if ($request->get('identity_type')->is(IdentityTypeEnum::GITHUB)) {
            $token = $this->service->handleGithubLogin($request->all());
        }else{
            $token = $this->service->handleLogin($request->all());
        }

        return Response::created($token);
    }
}
```

说明：

- 扩展了验证规则 enum、enum_key、enum_value，可以对 Request 中的参数  identity_type 进行校验。
- 引入了 `\Jiannei\Enum\Laravel\Http\Middleware\TransformEnums` 到路由中间件中。
- 在 Controller 中以 `$this->middleware('enum:false');` 形式使用`TransformEnums` 中间件，并且向中间件传入了 false 参数。对应上面的`UserTypeEnum::make('AdminiStrator', false);` ，将不会对枚举参数进行大小写和类型校验
- `$request->get('identity_type')` 获取到的是 `IdentityTypeEnum` 实例，Enum 实例中提供了 `is`、`isNot` 和 `in` 共 3 种枚举实例之间的比较方法

### Model 中的枚举转换

为了实现上面的多账号类型登录，account 数据表中就需要有字段 `identity_type` 来描述账号类型。

Laravel 的 `Eloquent\Model` 提供了 `$casts` 特性，可以将查询出来的数据字段转换成指定类型。这里也可以利用这个特性，将 account 表中的 `identity_type` 转换成 `IdentityTypeEnum` 实例。

```php
<?php

namespace App\Repositories\Models\MySql;

use App\Repositories\Enums\IdentityTypeEnum;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $casts = [
        'identity_type' => IdentityTypeEnum::class
    ];
}
```


### 缓存枚举

为了提高应用性能，在应用中使用缓存是很常见的一种做法。但随着业务到达一定规模后，分散在各处的缓存使用，由于定义缓存 key 的方式不统一，缓存生效时间的不确定性，都会使后期缓存的维护变得困难。

实现部分可以直接查看源码进行了解：[https://github.com/Jiannei/laravel-enum/blob/main/src/Repositories/Enums/CacheEnum.php](https://github.com/Jiannei/laravel-enum/blob/main/src/Repositories/Enums/CacheEnum.php)

lumen-api-statrer 中的实际使用实例：

- 定义：通过下面的方式将项目中所有缓存 key 和过期时间的定义统一到 CacheEnum 中，从而可以很直观的看出项目哪些地方使用了缓存，缓存多久失效。

```php
<?php

namespace App\Repositories\Enums;

use Illuminate\Support\Carbon;
use Jiannei\Enum\Laravel\Repositories\Enums\CacheEnum as BaseCacheEnum;

class CacheEnum extends BaseCacheEnum
{
	// key => 过期时间计算方法
    // 警告：方法名不能相同
    const AUTHORIZATION_USER = 'authorizationUser';// 将调用下面定义的 authorizationUser 方法获取缓存过期时间

	// ...

	// 授权用户信息过期时间定义：将在 Jwt token 过期时一同失效
    protected static function authorizationUser($options)
    {
        $exp = auth('api')->payload()->get('exp'); // token 剩余有效时间

        return Carbon::now()->diffInSeconds(Carbon::createFromTimestamp($exp));
    }

	// ...
}
```

- 使用

```php
// app/Providers/EloquentUserProvider.php

public function retrieveById($identifier)
{
	// 获取授权用户的缓存 key：类似于 lumen_cache:authorization:user:11
	$cacheKey = CacheEnum::getCacheKey(CacheEnum::AUTHORIZATION_USER,$identifier);
    // 获取缓存用户缓存的过期时间
	$cacheExpireTime = CacheEnum::getCacheExpireTime(CacheEnum::AUTHORIZATION_USER);

    return Cache::remember($cacheKey, $cacheExpireTime, function () use ($identifier) {
        $model = $this->createModel();

        return $this->newModelQuery($model)
            ->where($model->getAuthIdentifierName(), $identifier)
            ->first();
    });
}
```


## 特别说明

[laravel-enum](https://github.com/Jiannei/laravel-enum) 在下面2 个 package 功能的基础上，扩展增加了松散的（不区分大小写和数据类型） Request 参数转枚举实例，并内置提供了 HttpStatusCodeEnum 和 CacheEnum 等实用枚举集合。

- [BenSampo/laravel-enum](https://github.com/BenSampo/laravel-enum)：支持枚举定义、提供各种实用的枚举校验和转换，但是缺少中间件转换，不支持松散的 Request 参数转枚举实例
- [spatie/laravel-enum](https://github.com/spatie/laravel-enum)：支持中间件转换枚举

## License

MIT