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

    private Container $container;
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

        $this->style->text($message);
    }

    public function success(string $message)
    {
        $this->style->success($message);
    }

    public function warning(string $message)
    {
        $this->style->warning($message);
    }

    public function error(string $message)
    {
        $this->style->error($message);
    }
}
