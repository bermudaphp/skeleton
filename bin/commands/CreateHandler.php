<?php

namespace Console\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateHandler
 * @package Console\Commands
 */
final class CreateHandler extends Command
{
    protected static $defaultName = 'create::handler';
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        list($namespace, $classname) = $this->splitClassname($input->getArgument('classname'));
        $filename = APP_ROOT . '\app\Handler' . $namespace. '\\' . $classname . '.php';

        $handlerCode = $this->getCode();
        $handlerCode = str_replace([':namespace', ':classname', ':template'], [
            $namespace, $classname, $input->getArgument('template') ?? ''
        ], $handlerCode);

        file_put_contents($filename, $handlerCode);

        $output->writeln('App\Handler\\' . $input->getArgument('classname') . ' successfully created!');

        return self::SUCCESS;
    }

    protected function configure()
    {
        $this->addArgument('classname', InputArgument::REQUIRED);
        $this->addArgument('template', InputArgument::OPTIONAL);
    }

    /**
     * @param string $classname
     * @return array
     */
    private function splitClassname(string $classname): array
    {
        $result = explode('\\', $classname);
        $classname = array_pop($result);

        return ['\\' . implode('\\', $result), $classname];
    }

    private function getCode(): string
    {
        return "<?php

namespace App\\Handler:namespace;

use Psr\\Http\\Message\\ResponseInterface;
use Psr\\Http\Message\\ServerRequestInterface;
use Psr\\Http\\Server\\RequestHandlerInterface;
use function Bermuda\\view;

/**
 * Class :classname
 * @package App\\Handler:namespace
 */
final class :classname implements RequestHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface \$request): ResponseInterface
    {
    
    }
}";

    }
}
