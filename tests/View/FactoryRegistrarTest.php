<?php

namespace EllGreen\Pace\Tests\View;

use EllGreen\Pace\Structure;
use EllGreen\Pace\Tests\TestCase;
use EllGreen\Pace\View\FactoryRegistrar;
use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory as FactoryContract;
use Illuminate\View\Factory;
use org\bovigo\vfs\vfsStream;
use Spatie\Snapshots\MatchesSnapshots;

class FactoryRegistrarTest extends TestCase
{
    use MatchesSnapshots;

    private Factory $factory;

    protected function setUp(): void
    {
        $vfs = vfsStream::setup();

        $factoryRegistrar = new FactoryRegistrar(
            $container = Container::setInstance(new Container),
            new Structure($vfs->url())
        );

        $this->factory = $factoryRegistrar->register();
        $container->instance(FactoryContract::class, $this->factory);
        $container->instance('view', $this->factory);
        $container->instance(Application::class, tap($this->mock(Application::class), function ($app) {
           $app->shouldReceive('getNamespace')->andReturn('App\\');
        }));

        vfsStream::copyFromFileSystem(__DIR__.'/../data', $vfs);
    }

    public function testRegisterAndRenderView()
    {
        $rendered = $this->factory->make('pages.index')->render();
        $this->assertMatchesHtmlSnapshot($rendered);
    }

    public function testRegisterAndRenderViewWithComponent()
    {
        $rendered = $this->factory->make('pages.about')->render();
        $this->assertMatchesHtmlSnapshot($rendered);
    }
}
