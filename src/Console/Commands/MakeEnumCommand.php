<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeEnumCommand extends GeneratorCommand
{
    protected $signature = 'make:enum {name} {--type=default}';

    protected $description = 'Create a backed enum.';

    protected $type = 'Enum';

    protected function getStub()
    {
        return base_path("stubs/enum/{$this->option('type')}.stub");
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Enums';
    }
}
