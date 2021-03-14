<?php

namespace Console\Commands;

use Bermuda\App\AppInterface;
use Bermuda\App\Console\CommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HelloWorld
 * @package App\Console\Commands
 */
final class HelloWorld implements CommandInterface
{
    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Hello world!');
        return self::success;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'hello-world';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Simple command example';
    }
}
