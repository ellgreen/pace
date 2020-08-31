<?php

namespace EllGreen\Pace\Console\Commands;

use EllGreen\Pace\Pace;

class BuildCommand extends Command
{
    /** @var string $defaultName */
    protected static $defaultName = 'build';

    protected function configure()
    {
        $this->setDescription('Builds the static site for production.');
    }

    public function handle(Pace $pace)
    {
        $pace->build();

        $this->info("Pace build complete");
    }
}
