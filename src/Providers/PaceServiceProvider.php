<?php

namespace EllGreen\Pace\Providers;

use EllGreen\Pace\Structure;
use EllGreen\Pace\View\FactoryRegistrar;
use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory as FactoryContract;
use Mni\FrontYAML\Bridge\CommonMark\CommonMarkParser;
use Mni\FrontYAML\Bridge\Symfony\SymfonyYAMLParser;
use Mni\FrontYAML\Parser;

class PaceServiceProvider
{
    public static function register(Container $container, string $rootPath)
    {
        $container->singleton(Structure::class, fn() => new Structure($rootPath));

        $container->singleton(FactoryContract::class, function ($container) {
            return $container->make(FactoryRegistrar::class)->register();
        });

        $container->singleton('view', FactoryContract::class);

        $container->singleton(Parser::class, function ($container) {
            return new Parser(
                $container->make(SymfonyYAMLParser::class),
                $container->make(CommonMarkParser::class)
            );
        });
    }
}
