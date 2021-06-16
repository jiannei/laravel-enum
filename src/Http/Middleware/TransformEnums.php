<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TransformEnums
{
    public function handle(Request $request, Closure $next, $strict = true)
    {
        $strict = strtobool($strict);

        $request->transformEnums(Config::get('enum.transformations'), $strict);

        return $next($request);
    }
}
