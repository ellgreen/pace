<?php

namespace EllGreen\Pace;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

class Builder
{
    private Factory $factory;
    private string $viewsPath;
    private Filesystem $filesystem;
    private BladeCompiler $bladeCompiler;

    public function __construct(Container $container, string $viewsPath, string $cachePath)
    {
        $this->viewsPath = rtrim($viewsPath, '/');

        $this->filesystem = new Filesystem;
        $this->bladeCompiler = new BladeCompiler($this->filesystem, $cachePath);
        $eventDispatcher = new Dispatcher($container);

        $viewResolver = tap(
            new EngineResolver,
            function (EngineResolver $viewResolver) {
                $viewResolver->register('blade', fn() => new CompilerEngine($this->bladeCompiler));
                $viewResolver->register('php', fn() => new PhpEngine());
            }
        );

        $viewFinder = new FileViewFinder($this->filesystem, [$viewsPath]);

        $this->factory = new Factory($viewResolver, $viewFinder, $eventDispatcher);
        $this->factory->setContainer($container);

        $container->instance(\Illuminate\Contracts\View\Factory::class, $this->factory);
        $container->instance('view', $this->factory);
    }

    public function build($outputDir)
    {
        $outputDir = rtrim($outputDir, '/');

        if (! $this->filesystem->isDirectory($outputDir)) {
            $this->filesystem->makeDirectory($outputDir);
        }

        foreach ($this->filesystem->allFiles($this->viewsPath.'/pages') as $file) {
            $relativeName = Str::of($file->getRelativePathname())->beforeLast('.blade.php');

            $viewName = $relativeName->replace(DIRECTORY_SEPARATOR, '.')->prepend('pages.');
            $outputPath = $outputDir.'/'.$relativeName->append('.html');

            $renderedView = $this->factory->make($viewName);
            $this->filesystem->put($outputPath, $renderedView);
        }
    }
}
