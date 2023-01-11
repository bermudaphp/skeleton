<?php

declare(strict_types = 1);

use Bermuda\App\{AppInterface, Boot\BootstrapperInterface, Server, Console};
use Psr\Container\ContainerInterface;
use const Bermuda\App\is_cli;

return (static function(array $config, callable $containerFactory = null): array {

    if ($containerFactory != null) {
        $container = (static function() use ($containerFactory, $config): ContainerInterface {
            return $containerFactory($config);
        })();
    } else {
        $builder = new DI\ContainerBuilder(is_cli ? Console::class : Server::class);
        (new Elie\PHPDI\Config\Config($config))->configureContainer($builder);

        if (!\Bermuda\Config\Config::$devMode) {
            $builder->enableCompilation(__DIR__ . '\cache', is_cli ? 'CompiledConsole' : 'CompiledServer',
                is_cli ? \Bermuda\App\CompiledConsole::class
                    : \Bermuda\App\CompiledServer::class
            );
        }

        $container = $builder->build();
    }
    
    return [
        $container instanceof AppInterface ? $container 
            : $container->get(AppInterface::class),
        $container->get(BootstrapperInterface::class)
    ];
})(require 'config\config.php');
