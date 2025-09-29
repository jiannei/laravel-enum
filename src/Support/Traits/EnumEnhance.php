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
     * Check if this enum is any of the given enums
     */
    public function isAny(\BackedEnum ...$enums): bool
    {
        return in_array($this, $enums);
    }

    /**
     * Get enum cases
     *
     * @return array<static>
     */
    private static function getCases(): array
    {
        return static::cases();
    }

    /**
     * @return array<string>
     */
    public static function names(): array
    {
        return array_column(static::getCases(), 'name');
    }

    /**
     * @return array<int|string>
     */
    public static function values(): array
    {
        return array_column(static::getCases(), 'value');
    }

    public static function hasName(string $name, bool $strict = false): bool
    {
        return in_array($name, static::names(), $strict);
    }

    public static function hasValue(int|string $value, bool $strict = false): bool
    {
        return in_array($value, static::values(), $strict);
    }

    /**
     * Find enum case by property value
     */
    private static function findCase(string $property, mixed $value): static
    {
        $cases = static::getCases();

        foreach ($cases as $case) {
            if ($value === $case->$property) {
                return $case;
            }
        }

        // Generate helpful error message with available options
        $availableValues = $property === 'name' ? static::names() : static::values();
        $valueType = $property === 'name' ? 'name' : 'backing value';

        $valueString = is_scalar($value) ? (string) $value : gettype($value);

        throw new \ValueError(sprintf(
            'Invalid enum %s "%s" for %s. Valid %ss are: %s',
            $valueType,
            $valueString,
            static::class,
            $valueType,
            implode(', ', $availableValues)
        ));
    }

    public static function fromName(string $name): static
    {
        return static::findCase('name', $name);
    }

    public static function fromValue(int|string $value): static
    {
        return static::findCase('value', $value);
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
        return array_map(fn ($item) => [
            'name' => $item->name,
            'value' => $item->value,
            'description' => $item->description($localizationGroup),
        ], static::getCases());
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

    /**
     * Get a random enum case
     */
    public static function random(): static
    {
        $cases = static::getCases();

        return $cases[array_rand($cases)];
    }

    /**
     * Get the count of enum cases
     */
    public static function count(): int
    {
        return count(static::getCases());
    }

    /**
     * Check if this is the first enum case
     */
    public function isFirst(): bool
    {
        $cases = static::getCases();

        return $this === reset($cases);
    }

    /**
     * Check if this is the last enum case
     */
    public function isLast(): bool
    {
        $cases = static::getCases();

        return $this === end($cases);
    }

    /**
     * Get the next enum case
     */
    public function next(): ?static
    {
        $cases = static::getCases();
        $currentIndex = array_search($this, $cases, true);

        if ($currentIndex === false || ! is_int($currentIndex) || $currentIndex === count($cases) - 1) {
            return null;
        }

        return $cases[$currentIndex + 1];
    }

    /**
     * Get the previous enum case
     */
    public function previous(): ?static
    {
        $cases = static::getCases();
        $currentIndex = array_search($this, $cases, true);

        if ($currentIndex === false || ! is_int($currentIndex) || $currentIndex === 0) {
            return null;
        }

        return $cases[$currentIndex - 1];
    }
}
