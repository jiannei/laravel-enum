<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Providers;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Jiannei\Enum\Laravel\Http\Requests\EnumRequest;
use Jiannei\Enum\Laravel\Http\Requests\Rules\Enum;
use Jiannei\Enum\Laravel\Http\Requests\Rules\EnumKey;
use Jiannei\Enum\Laravel\Http\Requests\Rules\EnumValue;

class LaravelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerRequestTransformMacro();

        $this->setupConfig();
    }

    public function boot()
    {
        $this->bootValidationRules();
    }

    protected function bootValidationRules(): void
    {
        Validator::extend('enum_key', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;
            $strict = $parameters[1] ?? null;

            if (! $strict) {
                return (new EnumKey($enum))->passes($attribute, $value);
            }

            $strict = (bool) json_decode(strtolower($strict));

            return (new EnumKey($enum, $strict))->passes($attribute, $value);
        });

        Validator::extend('enum_value', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;
            $strict = $parameters[1] ?? null;

            if (! $strict) {
                return (new EnumValue($enum))->passes($attribute, $value);
            }

            $strict = (bool) json_decode(strtolower($strict));

            return (new EnumValue($enum, $strict))->passes($attribute, $value);
        });

        Validator::extend('enum', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;

            return (new Enum($enum))->passes($attribute, $value);
        });
    }

    protected function registerRequestTransformMacro()
    {
        Request::mixin(new EnumRequest);
    }

    protected function setupConfig()
    {
        $path = dirname(__DIR__, 2).'/config/enum.php';

        if ($this->app->runningInConsole()) {
            $this->publishes([$path => config_path('enum.php')], 'enum');
        }

        $this->mergeConfigFrom($path, 'enum');
    }
}
