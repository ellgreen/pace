<?php

namespace EllGreen\Pace\Tests\Sitemap;

use EllGreen\Pace\Sitemap\Page;
use EllGreen\Pace\Sitemap\PageFactory;
use EllGreen\Pace\Tests\TestCase;
use Illuminate\Container\Container;

class PageFactoryTest extends TestCase
{
    public function testSetPathIsCalledWithPath()
    {
        $factory = new PageFactory($container = $this->mock(Container::class));
        $page = $this->mock(Page::class)
            ->shouldReceive('setPath')
            ->with($path = 'path/to/view')
            ->once()
            ->getMock();

        $container->shouldReceive('make')->with(Page::class)->once()->andReturn($page);

        $factory->path($path);
    }
}
