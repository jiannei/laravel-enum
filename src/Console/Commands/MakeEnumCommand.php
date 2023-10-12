<?php

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
