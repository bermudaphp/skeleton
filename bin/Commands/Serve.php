<?php

namespace Console\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Serve
 * @package Console\Commands
 */
class Serve extends Command
{
    public function getName(): string
    {
        return 'serve';
    }

    public function getDescription(): string
    {
        return 'Run developer sever';
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        shell_exec('php -S localhost:8000 -t public//');
        return self::SUCCESS;
    }
}
