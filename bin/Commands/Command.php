<?php

namespace Console\Commands;

use Bermuda\App\Console\Asker\Asker;
use Bermuda\App\Console\CommandInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SymfonyCommand implements CommandInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)parent::getName();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return parent::getDescription();
    }

    /**
     * @inerhitDoc
     * @throws \Exception
     */
    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        return $this->run($input, $output);
    }
}
