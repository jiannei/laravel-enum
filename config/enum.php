<?php

return [
    'localization' => [
        'key' => env('ENUM_LOCALIZATION_KEY', 'enums'),
    ],

    // 你可以将请求参数中用到的枚举定义在下面，通过中间件，将会被自动转换成枚举类
    'transformations' => [
        // 参数名 => 对应的枚举类

    ],
];
