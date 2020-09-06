<?php

namespace EllGreen\Pace\Tests\View\Helpers;

use EllGreen\Pace\Tests\TestCase;
use EllGreen\Pace\View\Helpers\Sharer;
use Illuminate\Container\Container;
use Illuminate\View\Factory;

class SharerTest extends TestCase
{
    public function testShare()
    {
        $sharer = new Sharer(
            $container = $this->mock(Container::class),
            $factory = $this->mock(Factory::class)
        );

        $container->shouldReceive('make')->atLeast()->once();
        $factory->shouldReceive('share')->atLeast()->once();

        $sharer->share();
    }
}
