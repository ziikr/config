<?php

namespace Ziikr\Config;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerCommands();
    }


    protected function registerCommands()
    {
//        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
//        }
    }
}
