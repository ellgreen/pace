<?php

namespace EllGreen\Pace\Tests;

use EllGreen\Pace\Builder;
use EllGreen\Pace\Pace;
use EllGreen\Pace\Structure;
use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory;

class PaceTest extends TestCase
{
    public function testRegister()
    {
        $container = new Container;

        Pace::register($container, '/root');

        $this->assertTrue($container->has(Structure::class));
        $this->assertTrue($container->has(Factory::class));
        $this->assertTrue($container->has('view'));
    }
}
