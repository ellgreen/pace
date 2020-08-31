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

    public function compile(string $view, string $path)
    {
        if (! $this->filesystem->isDirectory($dir = dirname($path))) {
            $this->filesystem->makeDirectory($dir, $mode = 0755, $recursive = true);
        }

        $renderedView = $this->factory->make($view);
        (new Filesystem)->put($path, $renderedView);
    }
}
