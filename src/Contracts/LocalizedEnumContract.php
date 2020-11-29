<?php

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
