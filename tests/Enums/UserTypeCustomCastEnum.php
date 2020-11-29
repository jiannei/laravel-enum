<?php

namespace Jiannei\Enum\Laravel\Tests\Enums;

use Jiannei\Enum\Laravel\Enum;

final class UserTypeCustomCastEnum extends Enum
{
    const ADMINISTRATOR = 0;

    const MODERATOR = 1;

    const SUBSCRIBER = 2;

    const SUPER_ADMINISTRATOR = 3;

    public static function parseDatabase($value)
    {
        return explode('-', $value)[1] ?? null;
    }

    public static function serializeDatabase($value)
    {
        return 'type-'.$value;
    }
}
