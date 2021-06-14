<?php

namespace Console\Commands\Make;

use Bermuda\String\Str;
use Console\Commands\Command;
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
 * Class Handler
 * @package Console\Commands
 */
final class Handler extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        list($namespace, $classname) = Str::classnameSplit(
            $input->getArgument('classname')
        );

        $dir = $this->getDirname($namespace);

        if (!$this->directoryExists($dir))
        {
            $this->mkDir($dir);
        }

        $filename = $this->getFilename($dir, $classname);
        $handlerCode = $this->getCode($classname, $namespace);

        $this->writeFile($filename, $handlerCode);

        $output->writeln('App\Handler\\' . $input->getArgument('classname') . ' successfully created!');

        return self::SUCCESS;
    }

    public function getName(): string
    {
        return 'make:handler';
    }

    protected function configure()
    {
        $this->addArgument('classname', InputArgument::REQUIRED);
    }

    private function directoryExists(string $dir): bool
    {
        return is_dir($dir);
    }

    private function mkDir(string $dir): void
    {
        if (!mkdir($dir))
        {
            throw new \RuntimeException('Failed to create directory: ' . $dir);
        };
    }

    private function writeFile(string $filename, string $content)
    {
        if (file_put_contents($filename, $content) === false)
        {
            throw new \RuntimeException('Failed to write file: ' . $filename);
        }
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

    private function getDirname(?string $namespace = null): string
    {
        !$namespace ?: $namespace = '\\' . $namespace;
        return APP_ROOT . '\app\Handler' . $namespace . '\\';
    }

    private function getFilename(string $dirname, string $classname): string
    {
        return $dirname . $classname . '.php';
    }
}
