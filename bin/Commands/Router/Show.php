<?php

namespace Console\Commands\Router;

use function Bermuda\app;
use Bermuda\App\Boot\RouterBootstrapper;
use Console\Commands\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Show
 * @package Console\Commands
 */
final class Show extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rows = [];
        $routes = (($app = app())->get(RouterBootstrapper::class))($app);

        foreach($routes->toArray() as $route)
        {
            $rows[] = [$route->getName(),
                $route->getPath(),
                implode('|', $route->methods())
            ];
        }

        ($table = new Table($output))
            ->setHeaders(['Name', 'Path', 'Methods'])
            ->setRows($rows);

        $table->render();

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
