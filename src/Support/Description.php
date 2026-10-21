<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Support;

use BackedEnum;
use Illuminate\Contracts\Translation\Translator;
use Throwable;

/**
 * Resolves the description of an enum case.
 *
 * The module owns the translation key convention, the fallback label and the
 * rule that only string translations are accepted. The translator is passed
 * in; only fromContainer() knows about the application container.
 */
final class Description
{
    public function __construct(private readonly ?Translator $translator = null) {}

    /**
     * Resolve the translator from the container without relying on facades.
     *
     * This keeps the module usable in Laravel, Lumen (which requires
     * withFacades() for facade support) and plain PHP environments.
     */
    public static function fromContainer(): self
    {
        if (! function_exists('app')) {
            return new self;
        }

        try {
            $translator = app('translator');
        } catch (Throwable) {
            return new self;
        }

        return new self($translator instanceof Translator ? $translator : null);
    }

    public function describe(BackedEnum $case, string $localizationGroup = 'enums'): string
    {
        $fallback = $this->fallbackLabel($case);

        if ($this->translator === null) {
            return $fallback;
        }

        $key = $this->keyFor($case, $localizationGroup);

        if (! $this->translator->has($key)) {
            return $fallback;
        }

        $translation = $this->translator->get($key);

        return is_string($translation) ? $translation : $fallback;
    }

    private function keyFor(BackedEnum $case, string $localizationGroup): string
    {
        return $localizationGroup.'.'.$case::class.'.'.$case->name;
    }

    /**
     * Fallback label generated from the enum case name.
     */
    private function fallbackLabel(BackedEnum $case): string
    {
        return mb_strtolower(str_replace('_', ' ', $case->name));
    }
}
