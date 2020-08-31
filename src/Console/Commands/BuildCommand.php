<?php

namespace EllGreen\Pace\Console\Commands;

use Carbon\Carbon;
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
        $start = Carbon::now();

        $builder->build();

        $duration = $start->diffInMilliseconds();

        $this->info("Pace build complete ({$duration}ms)");
    }
}
