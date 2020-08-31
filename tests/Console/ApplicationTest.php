<?php

namespace EllGreen\Pace\Tests\Console;

use EllGreen\Pace\Console\Application;
use EllGreen\Pace\Console\Commands\Command;
use EllGreen\Pace\Tests\TestCase;
use Illuminate\Container\Container;

class ApplicationTest extends TestCase
{
    public function testRegisterCommands()
    {
        $application = $this->mock(Application::class)->makePartial();
        $application->shouldReceive('add')->once();

        $container = $this->mock(Container::class);

        $container->shouldReceive('make')->once()->andReturn($this->mock(Command::class));

        $application->registerCommands($container);
    }

    public function testGetNamespace()
    {
        $this->assertSame('App\\', (new Application)->getNamespace());
    }
}
