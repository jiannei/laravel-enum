<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Support\Traits;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

trait EnumEnhance
{
    public function name(): string
    {
        return $this->name;
    }

    public function value(): int|string
    {
        return $this->value;
    }

    public function description(string $localizationGroup = 'enums'): string
    {
        $name = (version_compare(app()->version(), '11.0.0', '>=') ? $this->value : $this->name);
        $key = sprintf("%s.%s.%s", $localizationGroup, static::class, $name);

        return Lang::has($key) ? Lang::get($key) : Str::of($this->name)->replace('_', ' ')->lower();
    }

    public function is(\BackedEnum $enum): bool
    {
        return $this === $enum;
    }

    public function isNot(\BackedEnum $enum): bool
    {
        return !$this->is($enum);
    }

    public function isAny(array $enums): bool
    {
        return in_array($this, $enums);
    }

    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }

    public static function hasName(string $name, bool $strict = false): bool
    {
        return in_array($name, static::names(), $strict);
    }

    public static function hasValue(int|string $value, bool $strict = false): bool
    {
        return in_array($value, static::values(), $strict);
    }

    public static function fromName(string $name): static
    {
        if (!static::hasName($name)) {
            throw new \ValueError("$name is not a valid backing name for enum \"".static::class.'"');
        }

        return head(array_filter(static::cases(), fn (\BackedEnum $enum) => $enum->name === $name));
    }

    public static function fromValue(int|string $value): static
    {
        if (!static::hasValue($value)) {
            throw new \ValueError("$value is not a valid backing value for enum \"".static::class.'"');
        }

        return head(array_filter(static::cases(), fn (\BackedEnum $enum) => $enum->value === $value));
    }

    public static function guess(int|string $key): static
    {
        return match (true) {
            static::hasName($key) => static::fromName($key),
            static::hasValue($key) => static::fromValue($key),
            default => throw new \ValueError("$key is illegal for enum \"".static::class.'"')
        };
    }

    public static function toArray(string $localizationGroup = 'enums'): array
    {
        return array_map(fn (\BackedEnum $item) => [
            'name' => $item->name,
            'value' => $item->value,
            'description' => $item->description($localizationGroup),
        ], static::cases());
    }

    public static function toSelectArray(string $localizationGroup = 'enums'): array
    {
        return array_reduce(static::toArray($localizationGroup), function ($carry, $item) {
            $carry[$item['value']] = $item['description'];

            return $carry;
        }, []);
    }
}
