<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Exceptions;

use Exception;
use Jiannei\Enum\Laravel\Enum;

class InvalidEnumValueException extends Exception
{
    /**
     * Create an InvalidEnumValueException.
     *
     * @param $invalidValue
     * @param  Enum|string  $enumClass
     */
    public function __construct($invalidValue, $enumClass)
    {
        $invalidValueType = gettype($invalidValue);
        $enumValues = implode(', ', $enumClass::getValues());
        $enumClassName = class_basename($enumClass);

        parent::__construct("Cannot construct an instance of $enumClassName using the value ($invalidValueType) `$invalidValue`. Possible values are [$enumValues].");
    }
}
