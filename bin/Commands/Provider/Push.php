<?php

namespace Console\Commands\Provider;

use Bermuda\String\_Class;
use Bermuda\String\_String;
use Console\Commands\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Bermuda\String\_string;

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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = file_get_contents('config/config.php');

        $name = $input->getArgument('provider');

        if (!_Class::isValidName($name)) {
            throw new InvalidArgumentException("Invalid provider name [$name]", $name);
        }

        /**
         * @var _String $startOfString
         * @var _String $endOfString
         */
        list($startOfString, $endOfString) = _string($contents)->break('Config::merge(');
        $contents = $startOfString->append($endOfString->prepend(PHP_EOL . "    new $name,"));

        file_put_contents('config/config.php', $contents);
        $output->writeln(sprintf('Provider: %s successfully registered in config/config.php', $name));

        return self::SUCCESS;
    }
}
