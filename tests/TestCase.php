<?php


namespace Jiannei\Enum\Laravel\Tests;


use Illuminate\Http\Request;
use Jiannei\Enum\Laravel\Providers\ServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['path.lang'] = __DIR__.'/lang';

        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['router']->any('test/enums', function (Request $request) {
            return $request->all();
        });
    }
}