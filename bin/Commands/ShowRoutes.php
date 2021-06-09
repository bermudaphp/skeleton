<?php

namespace Console\Commands;

use Bermuda\App\Boot\RouterBootstrapper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Bermuda\app;

/**
 * Class ShowRoutes
 * @package Console\Commands
 */
final class ShowRoutes extends Command
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
        return 'show-routes';
    }

    public function getDescription(): string
    {
        return 'Show routes list';
    }
}
