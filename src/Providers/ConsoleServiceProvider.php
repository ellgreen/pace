<?php

namespace EllGreen\Pace\Providers;

use EllGreen\Pace\Console\Application;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Application::class, function (Container $container) {
            $application = new Application();

            $application->registerCommands($container);

            return $application;
        });
    }
}
