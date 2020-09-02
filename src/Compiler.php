<?php

namespace EllGreen\Pace;

use Illuminate\Contracts\View\Factory;
use Illuminate\Filesystem\Filesystem;

class Compiler
{
    private Factory $factory;
    private Filesystem $filesystem;

    public function __construct(Factory $factory, Filesystem $filesystem)
    {
        $this->factory = $factory;
        $this->filesystem = $filesystem;
    }

    public function compile(string $view, string $path): void
    {
        $this->filesystem->ensureDirectoryExists(dirname($path));

        $renderedView = $this->factory->make($view);
        $this->filesystem->put($path, $renderedView);
    }
}
