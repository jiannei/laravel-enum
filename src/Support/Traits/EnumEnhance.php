<?php

namespace Jiannei\Enum\Laravel\Support\Traits;

use Illuminate\Support\Arr;
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

    public function description(): string
    {
        return Str::of($this->name)->replace('_', ' ')->lower();
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

    public static function fromName(string $name)
    {
        if (!self::hasName($name)) {
            throw new \ValueError("$name is not a valid backing name for enum \"".static::class."\"");
        }

        return head(array_filter(self::cases(), fn(\BackedEnum $enum) => $enum->name === $name));
    }

    public static function fromValue(int|string $value)
    {
        if (!self::hasValue($value)) {
            throw new \ValueError("$value is not a valid backing value for enum \"".static::class."\"");
        }

        return head(array_filter(self::cases(), fn(\BackedEnum $enum) => $enum->value === $value));
    }

    public static function guess(int|string $key)
    {
        return match (true) {
            static::hasName($key) => static::fromName($key),
            static::hasValue($key) => static::fromValue($key),
            default => throw new \ValueError("$key is illegal for enum \"".static::class."\"")
        };
    }

    public static function toArray(): array
    {
        return array_map(fn(\BackedEnum $item) => [
            'name' => $item->name,
            'value' => $item->value,
            'description' => $item->description(),
        ], static::cases());
    }

    public static function toSelectArray(): array
    {
        return array_reduce(static::toArray(), function ($carry, $item) {
            $carry[$item['value']] = $item['description'];

            return $carry;
        }, []);
    }
}
