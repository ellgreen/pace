<?php

namespace EllGreen\Pace\Console;

use EllGreen\Pace\Console\Commands\BuildCommand;
use EllGreen\Pace\Console\Commands\ServeCommand;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{
    protected function commands(): array
    {
        return [
            BuildCommand::class,
            ServeCommand::class,
        ];
    }

    public function registerCommands(Container $container): void
    {
        foreach ($this->commands() as $command) {
            $this->add($container->make($command));
        }
    }

    public function getNamespace()
    {
        return 'App\\';
    }
}
