<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Enum\Laravel\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;

class MakeEnumCommand extends GeneratorCommand
{
    protected $signature = 'make:enum {name} {--type=int}';

    protected $description = 'Create a backed enum';

    protected $type = 'Enum';

    protected function getStub()
    {
        $stubPath = base_path("stubs/enum/{$this->option('type')}.stub");

        return File::exists($stubPath) ? $stubPath : dirname(__DIR__, 3)."/stubs/enum/{$this->option('type')}.stub";
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Enums';
    }
}
