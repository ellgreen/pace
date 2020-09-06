<?php

namespace EllGreen\Pace\Tests\Console\Commands;

use EllGreen\Pace\Builder;
use EllGreen\Pace\Console\Commands\ServeCommand;
use EllGreen\Pace\Providers\PaceServiceProvider;
use EllGreen\Pace\Tests\TestCase;
use Illuminate\Container\Container;
use Mockery\MockInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Process\Process;

class ServeCommandTest extends TestCase
{
    private ServeCommand $serveCommand;
    private Container $container;

    protected function setUp(): void
    {
        PaceServiceProvider::register($this->container = new Container, '/root');

        $this->serveCommand = new ServeCommand($this->container);
    }

    public function testServeDevelopment()
    {
        $this->commandShouldBe(['php', '-S', 'localhost:8000', '-t', '/root/build']);

        $this->serveCommand->run(
            $input = new ArrayInput([]),
            $output = new NullOutput
        );
    }

    public function testServeProduction()
    {
        $this->commandShouldBe(['php', '-S', 'localhost:8000', '-t', '/root/build_prod']);

        $this->serveCommand->run(
            $input = new ArrayInput(['--prod' => true]),
            $output = new NullOutput
        );
    }

    public function testServeWithBuild()
    {
        $this->commandShouldBe(['php', '-S', 'localhost:8000', '-t', '/root/build']);

        $this->container->instance(Builder::class, tap(
            $this->mock(Builder::class),
            function (MockInterface $builder) {
                $builder->shouldReceive('build')->once();
            }
        ));

        $this->serveCommand->run(
            $input = new ArrayInput(['--build' => true]),
            $output = new NullOutput
        );
    }

    private function commandShouldBe(array $command)
    {
        $this->container->bind(Process::class, function ($_, array $parameters) use ($command) {
            $this->assertSame([
                'command' => $command
            ], $parameters);

            return tap($this->mock(Process::class), function (MockInterface $process) {
                $process->shouldReceive('setTimeout')->once();
                $process->shouldReceive('run')->once();
            });
        });
    }
}
