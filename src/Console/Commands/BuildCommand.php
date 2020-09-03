<?php

namespace EllGreen\Pace\Console\Commands;

use Carbon\Carbon;
use EllGreen\Pace\Builder;
use Symfony\Component\Console\Input\InputArgument;

class BuildCommand extends Command
{
    /** @var string $defaultName */
    protected static $defaultName = 'build';

    protected function configure()
    {
        $this->setDescription('Builds the static site for production.');

        $this->addArgument(
            'build-path',
            InputArgument::OPTIONAL,
            'Path to build files to',
            'build'
        );
    }

    public function handle(Builder $builder)
    {
        $buildPath = $this->input->getArgument('build-path');

        $start = Carbon::now();

        $builder->build($buildPath);

        $duration = $start->diffInMilliseconds();

        $this->info("Pace build complete [{$buildPath}] ({$duration}ms)");
    }
}
