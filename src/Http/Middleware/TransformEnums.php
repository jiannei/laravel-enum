<?php

namespace Jiannei\Enum\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TransformEnums
{
    public function handle(Request $request, Closure $next, $strict = true)
    {
        $strict = (bool) json_decode(strtolower($strict));

        $request->transformEnums(Config::get('enum.transformations'), $strict);

        return $next($request);
    }
}
