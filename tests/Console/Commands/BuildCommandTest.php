<?php

namespace EllGreen\Pace\Tests\Console\Commands;

use EllGreen\Pace\Builder;
use EllGreen\Pace\Console\Commands\BuildCommand;
use EllGreen\Pace\Pace;
use EllGreen\Pace\Tests\TestCase;
use Illuminate\Container\Container;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class BuildCommandTest extends TestCase
{
    public function testBuild()
    {
        Pace::register($container = new Container, '/root');

        $builder = $this->mock(Builder::class);
        $builder->shouldReceive('build')->once();

        $container->instance(Builder::class, $builder);

        $buildCommand = new BuildCommand($container);

        $buildCommand->run(
            $input = new ArrayInput([]),
            $output = new NullOutput
        );
    }
}
