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
        $key = "$localizationGroup.".static::class.'.'.$this->name;

        if (Lang::has($key)) {
            $translation = Lang::get($key);
            if (is_array($translation)) {
                return '';
            }

            return (string) $translation;
        }

        return (string) Str::of($this->name)->replace('_', ' ')->lower();
    }

    public function is(\BackedEnum $enum): bool
    {
        return $this === $enum;
    }

    public function isNot(\BackedEnum $enum): bool
    {
        return ! $this->is($enum);
    }

    /**
     * @param  array<\BackedEnum>  $enums
     */
    public function isAny(array $enums): bool
    {
        return in_array($this, $enums);
    }

    /**
     * @return array<string>
     */
    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }

    /**
     * @return array<int|string>
     */
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
        if (! static::hasName($name)) {
            throw new \ValueError("$name is not a valid backing name for enum \"".static::class.'"');
        }

        $filtered = array_filter(static::cases(), fn (\BackedEnum $enum) => $enum->name === $name);
        $result = head($filtered);

        if ($result === null || ! ($result instanceof static)) {
            throw new \ValueError("$name is not a valid backing name for enum \"".static::class.'"');
        }

        return $result;
    }

    public static function fromValue(int|string $value): static
    {
        if (! static::hasValue($value)) {
            throw new \ValueError("$value is not a valid backing value for enum \"".static::class.'"');
        }

        $filtered = array_filter(static::cases(), fn (\BackedEnum $enum) => $enum->value === $value);
        $result = head($filtered);

        if ($result === null || ! ($result instanceof static)) {
            throw new \ValueError("$value is not a valid backing value for enum \"".static::class.'"');
        }

        return $result;
    }

    public static function guess(int|string $key): static
    {
        return match (true) {
            static::hasName((string) $key) => static::fromName((string) $key),
            static::hasValue($key) => static::fromValue($key),
            default => throw new \ValueError("$key is illegal for enum \"".static::class.'"')
        };
    }

    /**
     * @return array<array{name: string, value: int|string, description: string}>
     */
    public static function toArray(string $localizationGroup = 'enums'): array
    {
        return array_map(fn (\BackedEnum $item) => [
            'name' => $item->name,
            'value' => $item->value,
            'description' => $item->description($localizationGroup),
        ], static::cases());
    }

    /**
     * @return array<int|string, string>
     */
    public static function toSelectArray(string $localizationGroup = 'enums'): array
    {
        return array_reduce(static::toArray($localizationGroup), function (array $carry, array $item): array {
            $carry[$item['value']] = $item['description'];

            return $carry;
        }, []);
    }
}
