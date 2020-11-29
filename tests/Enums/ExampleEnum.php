<?php

namespace Jiannei\Enum\Laravel\Tests\Enums;

use Jiannei\Enum\Laravel\Contracts\LocalizedEnumContract;
use Jiannei\Enum\Laravel\Enum;

class ExampleEnum extends Enum implements LocalizedEnumContract
{
    const ADMINISTRATOR = 1;

    const MODERATOR = 0;

    const SUPER_ADMINISTRATOR = 2;
}
