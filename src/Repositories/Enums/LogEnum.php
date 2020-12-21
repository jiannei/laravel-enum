<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Repositories\Enums;

use Jiannei\Enum\Laravel\Contracts\LocalizedEnumContract;
use Jiannei\Enum\Laravel\Enum;

class LogEnum extends Enum implements LocalizedEnumContract
{
    // 定义项目中的日志分类；以冒号区分层级
    const SYSTEM_SQL = 'system:sql';
    const SYSTEM_REQUEST = 'system:request';
}
