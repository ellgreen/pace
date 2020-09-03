<?php

namespace EllGreen\Pace\View\Helpers;

use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Str;

class Sharer
{
    private Container $container;
    private Factory $factory;

    protected array $helpers = [
        Mix::class,
    ];

    public function __construct(Container $container, Factory $factory)
    {
        $this->container = $container;
        $this->factory = $factory;
    }

    public function share()
    {
        foreach ($this->helpers as $helper) {
            $key = Str::of($helper)->afterLast('\\')->snake()->__toString();

            $this->factory->share($key, $this->container->make($helper));
        }
    }
}
