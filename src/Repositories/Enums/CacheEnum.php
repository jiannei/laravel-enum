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

use Illuminate\Support\Str;
use Jiannei\Enum\Laravel\Contracts\LocalizedEnumContract;
use Jiannei\Enum\Laravel\Enum;
use Jiannei\Enum\Laravel\Exceptions\InvalidMethodException;

class CacheEnum extends Enum implements LocalizedEnumContract
{
    // Define cache key and cache expire time.
    // cache key => cache time method
    // eg: const AUTHORIZATION_USER = 'authorizationUser';

    /**
     * Parse cache key to enum key.
     *
     * eg: authorization:user:1 => AUTHORIZATION_USER.
     *
     * @param  string  $cacheKey
     * @return string
     */
    public static function parseEnumKey(string $cacheKey): string
    {
        $segments = explode('_', Str::upper(str_replace(':', '_', $cacheKey)));

        if (is_numeric(last($segments))) {// TODO
            array_pop($segments);
        }

        return implode('_', $segments);
    }

    /**
     * Get cache key.
     *
     * @param $value
     * @param  string|int|null  $identifier
     * @return string
     */
    public static function getCacheKey($value, $identifier = null): string
    {
        $key = Str::lower(str_replace('_', ':', static::getKey($value)));

        return is_null($identifier) ? $key : $key.':'.$identifier;
    }

    /**
     * Get cache expire time.
     *
     * @param $value
     * @param  null  $options
     * @return mixed
     * @throws InvalidMethodException
     */
    public static function getCacheExpireTime($value, $options = null)
    {
        if (! method_exists(static::class, $value)) {
            throw new InvalidMethodException($value, static::class);
        }

        return static::{$value}($options);
    }
}
