<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Contracts;

interface LocalizedEnumContract
{
    /**
     * Get the default localization key.
     *
     * @return string
     */
    public static function getLocalizationKey();
}
