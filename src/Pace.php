<?php

namespace EllGreen\Pace;

use EllGreen\Pace\View\FactoryRegistrar;
use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory as FactoryContract;

class Pace
{
    public static function register(Container $container, string $rootPath)
    {
        $container->singleton(Structure::class, fn() => new Structure($rootPath));

        $factoryRegistrar = $container->make(FactoryRegistrar::class);
        $container->singleton(FactoryContract::class, fn() => $factoryRegistrar->register());
        $container->singleton('view', FactoryContract::class);
    }
}
