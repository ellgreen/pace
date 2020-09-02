<?php

namespace EllGreen\Pace\Console\Commands;

use EllGreen\Pace\Builder;
use EllGreen\Pace\Structure;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

class ServeCommand extends Command
{
    protected static $defaultName = 'serve';

    protected function configure()
    {
        $this->setDescription('Serve your Pace static site');

        $this->addOption(
            'prod',
            null,
            InputOption::VALUE_NONE,
            'Serve the files in build_prod'
        );

        $this->addOption(
            'host',
            null,
            InputOption::VALUE_OPTIONAL,
            'Host to serve the site on',
            'localhost'
        );

        $this->addOption(
            'port',
            'p',
            InputOption::VALUE_OPTIONAL,
            'Port to serve the site on',
            8000
        );

        $this->addOption(
            'build',
            'b',
            InputOption::VALUE_NONE,
            'Build the site before serving'
        );
    }

    public function handle(Structure $structure, Builder $builder)
    {
        $host = $this->input->getOption('host');
        $port = $this->input->getOption('port');
        $prod = $this->input->getOption('prod');
        $build = $this->input->getOption('build');

        $buildPath = $structure->path($buildDir = ($prod ? 'build_prod' : 'build'));

        if ($build) {
            $this->info("Building: {$buildDir}\n");

            $builder->build($buildDir);
        }

        $this->info("Serving: {$buildPath}\n");

        /* @var Process $process */
        $process = $this->container->makeWith(Process::class, [
            'command' => ['php', '-S', "{$host}:{$port}", '-t', $buildPath]
        ]);

        // Don't time out command
        $process->setTimeout(null);

        $process->run(fn($type, $buffer) => $this->output->write($buffer));
    }
}
