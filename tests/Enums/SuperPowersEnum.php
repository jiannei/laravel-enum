<?php

namespace Jiannei\Enum\Laravel\Tests\Enums;

use Jiannei\Enum\Laravel\FlaggedEnum;

final class SuperPowersEnum extends FlaggedEnum
{
    const FLIGHT = 1 << 0;

    const INVISIBILITY = 1 << 1;

    const LASER_VISION = 1 << 2;

    const STRENGTH = 1 << 3;

    const TELEPORTATION = 1 << 4;

    const IMMORTALITY = 1 << 5;

    const TIME_TRAVEL = 1 << 6;

    const SUPERMAN = self::FLIGHT | self::STRENGTH | self::LASER_VISION;
}
