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

use BackedEnum;
use Jiannei\Enum\Laravel\Support\Description;
use ReflectionEnum;
use ValueError;

/**
 * @phpstan-require-implements BackedEnum
 */
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
        return Description::fromContainer()->describe($this, $localizationGroup);
    }

    public function is(BackedEnum $enum): bool
    {
        return $this === $enum;
    }

    public function isNot(BackedEnum $enum): bool
    {
        return ! $this->is($enum);
    }

    /**
     * Check if this enum is any of the given enums
     */
    public function isAny(BackedEnum ...$enums): bool
    {
        return in_array($this, $enums, true);
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

    public static function hasName(string $name): bool
    {
        return static::caseByName($name) !== null;
    }

    public static function hasValue(int|string $value): bool
    {
        return static::caseByValue($value) !== null;
    }

    /**
     * @throws ValueError
     */
    public static function fromName(string $name): static
    {
        return static::caseByName($name) ?? throw static::invalidLookup('name', $name, 'names', static::names());
    }

    /**
     * @throws ValueError
     */
    public static function fromValue(int|string $value): static
    {
        return static::caseByValue($value) ?? throw static::invalidLookup('backing value', (string) $value, 'backing values', static::values());
    }

    /**
     * @throws ValueError
     */
    public static function guess(int|string $key): static
    {
        return static::caseByName((string) $key)
            ?? static::caseByValue($key)
            ?? throw static::invalidLookup(
                'name or backing value',
                (string) $key,
                'names or backing values',
                array_values(array_unique([...static::names(), ...static::values()])),
            );
    }

    private static function caseByName(string $name): ?static
    {
        foreach (static::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return null;
    }

    private static function caseByValue(int|string $value): ?static
    {
        $value = static::normalizeBackingValue($value);

        foreach (static::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        return null;
    }

    /**
     * Convert request input to the enum's backing type.
     *
     * Int-backed enums accept integer strings, string-backed enums accept
     * integers. Values that cannot be converted are returned unchanged so the
     * lookup fails instead of matching the wrong case.
     */
    private static function normalizeBackingValue(int|string $value): int|string
    {
        if (! enum_exists(static::class)) {
            return $value;
        }

        $backingType = (new ReflectionEnum(static::class))->getBackingType();

        if ($backingType?->getName() === 'string') {
            return (string) $value;
        }

        if (preg_match('/^\s*-?\d+\s*$/', (string) $value) === 1) {
            return (int) $value;
        }

        return $value;
    }

    /**
     * @param  array<int, int|string>  $valid
     */
    private static function invalidLookup(string $kind, string $input, string $validLabel, array $valid): ValueError
    {
        return new ValueError(sprintf(
            'Invalid enum %s "%s" for %s. Valid %s are: %s',
            $kind,
            $input,
            static::class,
            $validLabel,
            implode(', ', $valid),
        ));
    }

    private static function positionOf(BackedEnum $case): ?int
    {
        foreach (static::cases() as $index => $candidate) {
            if ($candidate === $case) {
                return $index;
            }
        }

        return null;
    }

    /**
     * @return array<array{name: string, value: int|string, description: string}>
     */
    public static function toArray(string $localizationGroup = 'enums'): array
    {
        $description = Description::fromContainer();

        $items = [];

        foreach (static::cases() as $item) {
            $items[] = [
                'name' => $item->name,
                'value' => $item->value,
                'description' => $description->describe($item, $localizationGroup),
            ];
        }

        return $items;
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
     *
     * @throws ValueError
     */
    public static function random(): static
    {
        $enum = static::class;
        $cases = $enum::cases();

        if ($cases === []) {
            throw new ValueError('Cannot pick a random case from an empty enum '.static::class);
        }

        return $cases[array_rand($cases)];
    }

    /**
     * Get the count of enum cases
     */
    public static function count(): int
    {
        return count(static::cases());
    }

    /**
     * Check if this is the first enum case
     */
    public function isFirst(): bool
    {
        return static::positionOf($this) === 0;
    }

    /**
     * Check if this is the last enum case
     */
    public function isLast(): bool
    {
        $position = static::positionOf($this);

        return $position !== null && $position === count(static::cases()) - 1;
    }

    /**
     * Get the next enum case
     */
    public function next(): ?static
    {
        $position = static::positionOf($this);

        return $position === null ? null : (static::cases()[$position + 1] ?? null);
    }

    /**
     * Get the previous enum case
     */
    public function previous(): ?static
    {
        $position = static::positionOf($this);

        return $position === null || $position === 0 ? null : (static::cases()[$position - 1] ?? null);
    }
}
