<?php

namespace EllGreen\Pace\Tests\Console\Commands;

use EllGreen\Pace\Builder;
use EllGreen\Pace\Console\Commands\BuildCommand;
use EllGreen\Pace\Providers\PaceServiceProvider;
use EllGreen\Pace\Tests\TestCase;
use Illuminate\Container\Container;
use Mockery\MockInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class BuildCommandTest extends TestCase
{
    private MockInterface $builder;
    private BuildCommand $buildCommand;

    public function setUp(): void
    {
        PaceServiceProvider::register($container = new Container, '/root');

        $this->builder = $this->mock(Builder::class);

        $container->instance(Builder::class, $this->builder);

        $this->buildCommand = new BuildCommand($container);
    }

    public function testBuild()
    {
        $this->builder->shouldReceive('build')->with('build')->once();

        $this->buildCommand->run(
            $input = new ArrayInput([]),
            $output = new NullOutput
        );
    }
    public function testBuildCustomDir()
    {
        $this->builder->shouldReceive('build')->with('test')->once();

        $this->buildCommand->run(
            $input = new ArrayInput(['build-path' => 'test']),
            $output = new NullOutput
        );
    }
}
