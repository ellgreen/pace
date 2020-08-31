<?php

namespace EllGreen\Pace\View;

use EllGreen\Pace\Structure;
use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory as FactoryContract;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

class FactoryRegistrar
{
    private Container $container;
    private Structure $structure;

    public function __construct(Container $container, Structure $structure)
    {
        $this->container = $container;
        $this->structure = $structure;
    }

    public function register(): Factory
    {
        $bladeCompiler = new BladeCompiler(
            $filesystem = $this->container->make(Filesystem::class),
            $this->structure->cachedViews()
        );

        $eventDispatcher = new Dispatcher($this->container);

        $viewResolver = tap(
            new EngineResolver,
            function (EngineResolver $viewResolver) use ($bladeCompiler) {
                $viewResolver->register('blade', fn() => new CompilerEngine($bladeCompiler));
                $viewResolver->register('php', fn() => new PhpEngine);
            }
        );

        $viewFinder = new FileViewFinder($filesystem, [$this->structure->views()]);

        $factory = new Factory($viewResolver, $viewFinder, $eventDispatcher);
        $factory->setContainer($this->container);

        return $factory;
    }
}
