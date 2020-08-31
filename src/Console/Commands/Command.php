<?php

namespace EllGreen\Pace\Console\Commands;

use Illuminate\Container\Container;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class Command extends SymfonyCommand
{
    public InputInterface $input;
    public OutputInterface $output;

    protected Container $container;
    private SymfonyStyle $style;

    public function __construct(Container $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->style = new SymfonyStyle($this->input, $this->output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->container->call([$this, 'handle']);

        return static::SUCCESS;
    }

    public function line(string $message)
    {
        $this->output->writeln($message);
    }

    public function info(string $message)
    {
        $this->line("<info>{$message}</info>");
    }
}
