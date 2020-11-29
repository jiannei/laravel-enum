<?php


namespace Jiannei\Enum\Laravel\Tests\Enums;

use Jiannei\Enum\Laravel\Enum;

final class UserTypeEnum extends Enum
{
    const ADMINISTRATOR = 0;

    const MODERATOR = 1;

    const SUBSCRIBER = 2;

    const SUPER_ADMINISTRATOR = 3;

    public function magicInstantiationFromInstanceMethod(): self
    {
        return self::ADMINISTRATOR();
    }
}
