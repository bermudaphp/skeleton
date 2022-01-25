<?php

namespace Console\Commands\Router;

use Bermuda\App\AppInterface;
use Bermuda\App\Boot\RouterBootstrapper;
use Console\Commands\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Show extends Command
{
    public function __construct(private AppInterface $app)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach((new RouterBootstrapper)($this->app)->toArray() as $route) {
            $rows[] = [$route->getName(), $route->getPath(),
                implode('|', $route->methods())
            ];
        }
        
        (new Table($output))->setHeaders(['Name', 'Path', 'Methods'])
            ->setRows($rows)
            ->render();
        
        return self::SUCCESS;
    }

    public function getName(): string
    {
        return 'router:show';
    }

    public function getDescription(): string
    {
        return 'Show routes list';
    }
}
