<?php

namespace EllGreen\Pace;

use EllGreen\Pace\View\FactoryRegistrar;
use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory as FactoryContract;

class Pace
{
    public static function register(Container $container, string $rootPath)
    {
        $container->singleton(Structure::class, function () use ($rootPath) {
            return new Structure($rootPath);
        });

        $factoryRegistrar = $container->make(FactoryRegistrar::class);
        $container->bind(FactoryContract::class, fn() => $factoryRegistrar->register());
        $container->bind('view', FactoryContract::class);
    }
}
