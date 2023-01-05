<?php

declare(strict_types = 1);

use const Bermuda\App\is_cli;
use Psr\Container\ContainerInterface;
use Bermuda\App\{AppInterface, Boot\BootstrapperInterface, Server, Console};

return (static function(array $config, callable $containerFactory = null): array {
    if ($containerFactory != null) {
        $container = (static function() use ($containerFactory, $config): ContainerInterface {
            return $containerFactory($config);
        })();
    } else {
        $builder = new DI\ContainerBuilder(\Bermuda\App\is_cli ? Console::class : Server::class);
        (new Elie\PHPDI\Config\Config($config))->configureContainer($builder);
        $container = $builder->build();
    }
    
    return [
        $container instanceof AppInterface ? $container 
            : $container->get(AppInterface::class),
        $container->get(BootstrapperInterface::class)
    ];
})(require 'config/config.php');
