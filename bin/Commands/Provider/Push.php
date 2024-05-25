<?php

namespace Console\Commands\Provider;

use Bermuda\Stdlib\StrWrp;
use Bermuda\Stdlib\ClsHelper;
use Console\Commands\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Push extends Command
{
    public function getName(): string
    {
        return 'provider:push';
    }

    protected function configure()
    {
        $this->addArgument('provider', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = file_get_contents('config/config.php');

        $name = $input->getArgument('provider');

        if (!ClsHelper::isValidName($name)) {
            throw new InvalidArgumentException("Invalid provider name [$name]", $name);
        }
        
        list($startOfString, $endOfString) = (new StrWrp($contents))->break('Config::merge(');
        $contents = $startOfString->append($endOfString->prepend(PHP_EOL . "    new $name,"));

        file_put_contents('config/config.php', $contents);
        $output->writeln(sprintf('Provider: %s successfully registered in config/config.php', $name));

        return self::SUCCESS;
    }
}
