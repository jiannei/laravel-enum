<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Jiannei\Enum\Laravel\Support\Description;
use Jiannei\Enum\Laravel\Support\Traits\EnumEnhance;

function normalizedType(ReflectionType $type): string
{
    $rendered = (string) $type;
    $parts = explode('|', ltrim($rendered, '?'));
    $nullable = str_starts_with($rendered, '?') || in_array('null', $parts, true);

    $parts = array_values(array_filter($parts, fn (string $part): bool => $part !== 'null'));
    sort($parts);

    return ($nullable ? '?' : '').implode('|', $parts);
}

function reflectedSignature(ReflectionMethod $method): string
{
    $parameters = array_map(function (ReflectionParameter $parameter): string {
        $type = $parameter->getType();
        $signature = $type === null ? '' : normalizedType($type).' ';
        $signature .= $parameter->isVariadic() ? '...' : '';
        $signature .= '$'.$parameter->getName();

        if ($parameter->isDefaultValueAvailable()) {
            $signature .= ' = '.var_export($parameter->getDefaultValue(), true);
        }

        return $signature;
    }, $method->getParameters());

    $returnType = $method->getReturnType();

    return $method->getDeclaringClass()->getShortName()
        .'::'.$method->getName()
        .'('.implode(', ', $parameters).')'
        .': '.($returnType === null ? 'mixed' : normalizedType($returnType));
}

test('the interface contract matches the implementation', function () {
    $markdown = file_get_contents(__DIR__.'/../../docs/API.md');

    preg_match('/```api-contract\s*\n(.*?)```/s', $markdown, $matches);

    expect($matches)->toHaveKey(1);

    $documented = array_values(array_filter(
        array_map('trim', explode("\n", $matches[1])),
    ));

    $implemented = [];

    foreach ([EnumEnhance::class, Description::class] as $class) {
        $reflection = new ReflectionClass($class);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->isConstructor()) {
                continue;
            }

            $implemented[] = reflectedSignature($method);
        }
    }

    sort($documented);
    sort($implemented);

    expect($implemented)->toBe($documented);
})->skip(
    ! is_file(__DIR__.'/../../docs/API.md'),
    'docs/API.md is maintained outside the repository.',
);
