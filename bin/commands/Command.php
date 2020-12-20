<?php

namespace Console\Commands;


use Bermuda\App\Console\CommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;


/**
 * Class Command
 * @package Console\Commands
 */
abstract class Command extends SymfonyCommand implements CommandInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) parent::getName();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return parent::getDescription();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        return $this->run($input, $output);
    }
}
