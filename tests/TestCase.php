<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Tests;

use Illuminate\Contracts\Config\Repository;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['path.lang'] = __DIR__.'/lang';

        tap($app['config'], function (mixed $config): void {
            if ($config instanceof Repository) {
                $config->set('app.locale', 'zh_CN');
                $config->set('database.default', 'sqlite');
                $config->set('database.connections.sqlite', [
                    'driver' => 'sqlite',
                    'database' => ':memory:',
                    'prefix' => '',
                ]);
            }
        });
    }
}
