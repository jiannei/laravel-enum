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

class LaravelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->setupConfig();
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
