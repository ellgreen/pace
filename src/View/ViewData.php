<?php

namespace EllGreen\Pace\View;

use Illuminate\Contracts\View\Factory;

class ViewData
{
    private Mix $mix;
    private Factory $factory;

    public function __construct(Mix $mix, Factory $factory)
    {
        $this->mix = $mix;
        $this->factory = $factory;
    }

    public function share(string $buildPath): void
    {
        $this->shareMix($buildPath);
    }

    private function shareMix(string $buildPath): void
    {
        $mix = fn(string $path) => $this->mix->path($path, $buildPath);

        $this->factory->share('mix', $mix);
    }
}
