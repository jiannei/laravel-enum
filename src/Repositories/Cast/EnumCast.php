<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Repositories\Cast;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Jiannei\Enum\Laravel\Enum;

class EnumCast implements CastsAttributes
{
    /** @var string */
    protected $enumClass;

    public function __construct(string $enumClass)
    {
        $this->enumClass = $enumClass;
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return $this->castEnum($value);
    }

    /**
     * @param  mixed  $value
     *
     * @return Enum|null
     */
    protected function castEnum($value): ?Enum
    {
        if ($value === null || $value instanceof $this->enumClass) {
            return $value;
        }

        $value = $this->getCastableValue($value);

        return $this->enumClass::getInstance($value);
    }

    /**
     * Retrieve the value that can be casted into Enum.
     *
     * @param  mixed  $value
     *
     * @return mixed
     */
    protected function getCastableValue($value)
    {
        // If the enum has overridden the `castNative` method, use it to get the cast value
        $value = $this->enumClass::parseDatabase($value);

        // If the value exists in the enum (using strict type checking) return it
        if ($this->enumClass::hasValue($value)) {
            return $value;
        }

        // Find the value in the enum that the incoming value can be coerced to
        foreach ($this->enumClass::getValues() as $enumValue) {
            if ($value == $enumValue) {
                return $enumValue;
            }
        }

        // Fall back to trying to construct it directly (will result in an error since it doesn't exist)
        return $value;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        $value = $this->castEnum($value);

        return [$key => $this->enumClass::serializeDatabase($value)];
    }
}
