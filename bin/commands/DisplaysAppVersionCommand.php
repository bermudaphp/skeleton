<?php

namespace App\Console\Commands;

use Bermuda\App\AppInterface;
use Bermuda\App\Console\CommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class DisplaysAppVersionCommand
 * @package App\Console\Commands
 */
final class DisplaysAppVersionCommand implements CommandInterface
{
    private AppInterface $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln($this->app->version());
        return self::success;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'version';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Displays application current version';
    }
}
