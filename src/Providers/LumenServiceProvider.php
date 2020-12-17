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

class LumenServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->app->configure('enum');
    }

    protected function setupConfig()
    {
        $path = dirname(__DIR__, 2).'/config/enum.php';

        $this->mergeConfigFrom($path, 'enum');
    }
}
