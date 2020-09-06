<?php

namespace EllGreen\Pace\Sitemap;

use Illuminate\Container\Container;

class PageFactory
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function path(string $path)
    {
        return tap($this->container->make(Page::class))
            ->setPath($path);
    }
}
