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

use Illuminate\Support\ServiceProvider;
use Jiannei\Enum\Laravel\Console\Commands\MakeEnumCommand;

class LaravelServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                dirname(__DIR__, 2).'/stubs' => $this->app->basePath('stubs'),
            ], 'stubs');

            $this->commands([
                MakeEnumCommand::class,
            ]);
        }
    }
}
