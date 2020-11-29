<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Tests\Enums;

use Jiannei\Enum\Laravel\Contracts\LocalizedEnumContract;
use Jiannei\Enum\Laravel\Enum;

class ExampleEnum extends Enum implements LocalizedEnumContract
{
    const ADMINISTRATOR = 1;

    const MODERATOR = 0;

    const SUPER_ADMINISTRATOR = 2;
}
