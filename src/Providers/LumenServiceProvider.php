<?php


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