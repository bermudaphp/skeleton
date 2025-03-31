<?php

namespace Console;

use Bermuda\App\Console\AsCommand;
use Bermuda\App\Console\SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Bermuda\CodeTemplate\Module\ConfigProviderTemplate;
use Bermuda\CodeTemplate\Module\EntityDataTemplate;
use Bermuda\CodeTemplate\Module\EntityFactoryInterfaceTemplate;
use Bermuda\CodeTemplate\Module\EntityRepositoryInterfaceTemplate;
use Bermuda\CodeTemplate\Module\EntityRepositoryTemplate;
use Bermuda\CodeTemplate\Module\EntityTemplate;
use Bermuda\CodeTemplate\NamespaceTemplate;
use Bermuda\CodeTemplate\RenderException;
use Bermuda\CodeTemplate\Template;

#[AsCommand]
class CreateModule extends SymfonyCommand
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
        $this->setName('create:module');
        $this->setDescription('Create a new module');

        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the module');
    }

    /**
     * @throws RenderException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $segments = explode('/', str_replace(['\\', '/'], '/', $input->getArgument('name')));
        $cls = array_pop($segments);

        $path = $this->path . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $segments) . DIRECTORY_SEPARATOR . $cls;

        $namespace = new NamespaceTemplate(array_merge($segments, [$cls]));

        if (!is_dir($path)) mkdir($path);

        $cls = ucfirst($cls);
        foreach ($this->getTemplates() as $name => $template) {

            $template->setToken('%namespace%', $namespace);
            $template->setToken('%EntityClass%', $cls);

            $filename = $name === 'ConfigProvider' ? $path.DIRECTORY_SEPARATOR."ConfigProvider.php"
                : $path.DIRECTORY_SEPARATOR."$cls$name.php";

            file_put_contents($filename, $template->render());
        }

        $output->writeln("<info>Module $cls successfully created </info>");

        return self::SUCCESS;
    }

    /**
     * @return Template[]
     */
    protected function getTemplates(): array
    {
        return [
            '' => new EntityTemplate(),
            'Data' => new EntityDataTemplate(),
            'Repository' => new EntityRepositoryTemplate(),
            'RepositoryInterface' => new EntityRepositoryInterfaceTemplate(),
            'FactoryInterface' => new EntityFactoryInterfaceTemplate(),
            'ConfigProvider' => new ConfigProviderTemplate()
        ];
    }
}
