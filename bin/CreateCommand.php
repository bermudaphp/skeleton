<?php

namespace Console\Create;

use Bermuda\App\Console\AsCommand;
use Bermuda\App\Console\SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Bermuda\CodeTemplate\CommandTemplate;
use Bermuda\CodeTemplate\NamespaceTemplate;
use Bermuda\CodeTemplate\RenderException;

#[AsCommand]
class CreateCommand extends SymfonyCommand
{
    private string $path;
    public function __construct(
        ?string $path = null
    ) {
        parent::__construct();
        if (!$path) $path = getcwd() . DIRECTORY_SEPARATOR . 'src';
        $this->path = $path;
    }

    protected function configure(): void
    {
        $this->setName('create:command');
        $this->setDescription('Create a new console command');

        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the command class');
    }

    /**
     * @throws RenderException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $template = new CommandTemplate();

        $segments = explode('/', $input->getArgument('name'));
        $cls = array_pop($segments);

        $template->setToken('%namespace%', new NamespaceTemplate($segments));
        $template->setToken('%CommandClassName%', $cls);

        $path = $this->path . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $segments).DIRECTORY_SEPARATOR."$cls.php";
        file_put_contents($path, $template->render());

        $output->writeln("<info>Command $cls successfully created </info>");

        return self::SUCCESS;
    }
}
