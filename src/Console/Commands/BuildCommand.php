<?php

namespace EllGreen\Pace\Console\Commands;

use EllGreen\Pace\Builder;

class BuildCommand extends Command
{
    /** @var string $defaultName */
    protected static $defaultName = 'build';

    protected function configure()
    {
        $this->setDescription('Builds the static site for production.');
    }

    public function handle(Builder $builder)
    {
        $this->line('Started');

        $builder->build($dir = getcwd().'/public');

        $this->success("Output files to: {$dir}");
    }
}
