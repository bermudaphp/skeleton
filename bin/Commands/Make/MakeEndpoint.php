<?php

namespace Console\Commands\Make;

use Bermuda\String\_Class;
use Console\Commands\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Bermuda\String\_string;

class MakeEndpoint extends Command
{
    public function getName(): string
    {
        return 'make:endpoint';
    }

    protected function configure()
    {
        $this->addArgument('endpoint', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $endpointName = $input->getArgument('endpoint');
        if (!_Class::isValidName($endpointName)) {
            throw new InvalidArgumentException("Invalid provider name [$endpointName]", $endpointName);
        }

        $template = <<<'EOD'
<?php 

namespace App\Endpoint%ENDPOINT_NAMESPACE%;

use Bermuda\Router\Resource;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class %ENDPOINT_NAME% extends Resource
{
    protected static bool $registerOptions = true;

    public static function getName(): string
    {
        return '%ROUTE_NAME%';
    }
    
    public static function getPathPrefix(): string
    {
        return '/%ROUTE_PATH%';
    }

    public function get(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement get() method.
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement create() method.
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement update() method.
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement delete() method.
    }

    public function options(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement options() method.
    }
}
EOD;
        $segments = explode('\\', $endpointName);
        $nameSegment = array_pop($segments);

        $before = _string($nameSegment)->before('Endpoint');
        $endpointNamespace = count($segments) > 0 ? implode('\\', $segments) : '';

        $content = str_replace(
            ['%ENDPOINT_NAME%', '%ROUTE_NAME%', '%ROUTE_PATH%', '%ENDPOINT_NAMESPACE%'],
            [$nameSegment, $before, strtolower($before), $endpointNamespace],
            $template
        );

        file_put_contents("app\Endpoint\\$endpointName.php", $content);
        $output->writeln(sprintf('Endpoint: %s successfully created.', $endpointName));

        return self::SUCCESS;
    }
}
