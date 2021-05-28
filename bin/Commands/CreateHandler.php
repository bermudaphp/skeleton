<?php

namespace Console\Commands;

use Bermuda\String\Str;
use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateHandler
 * @package Console\Commands
 */
final class CreateHandler extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        list($namespace, $classname) = Str::classnameSplit(
            $input->getArgument('classname')
        );

        $filename = $this->getFilename($classname, $namespace);
        $handlerCode = $this->getCode($classname, $namespace);

        file_put_contents($filename, $handlerCode);

        $output->writeln('App\Handler\\' . $input->getArgument('classname') . ' successfully created!');

        return self::SUCCESS;
    }

    public function getName(): string
    {
        return 'create-handler';
    }

    protected function configure()
    {
        $this->addArgument('classname', InputArgument::REQUIRED);
    }

    private function getCode(string $classname, ?string $namespace = null): string
    {
        return (new FileGenerator([
            'classes' => [
                (new ClassGenerator($classname,
                    null, null,
                    null, [RequestHandlerInterface::class],
                    [], [
                        new class extends MethodGenerator
                        {
                            public function generate()
                            {
                                $out = '    /**';
                                $out .= PHP_EOL . '     * @inheritDoc';
                                $out .= PHP_EOL . '     */' . PHP_EOL;
                                $out .= '    public function handle(ServerRequestInterface $request): ResponseInterface';
                                $out .= PHP_EOL . '    {';
                                $out .= PHP_EOL . '        // TODO Implement request handling logic';
                                $out .= PHP_EOL . '    }';

                                return $out;
                            }
                        }
                    ]
                ))->addUse(ServerRequestInterface::class)
                    ->addUse(ResponseInterface::class)
                    ->addUse(RequestHandlerInterface::class)
            ]
        ]))
            ->setNamespace($namespace == null ? 'App\Handler' : 'App\Handler\\' . $namespace)
            ->generate();
    }

    private function getFilename(string $classname, ?string $namespace = null): string
    {
        return APP_ROOT . '\app\Handler' . $namespace . '\\' . $classname . '.php';
    }
}
