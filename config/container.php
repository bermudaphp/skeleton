<?php

declare(strict_types = 1);

use Bermuda\ErrorHandler\ErrorHandlerInterface;
use Elie\PHPDI\Config\Config;
use Elie\PHPDI\Config\ConfigInterface;
use Bermuda\App\{AppInterface, Boot\BootstrapperInterface, Server, Console};
use function Bermuda\App\is_cli;

return (static function(ConfigInterface $config, string $containerClass = null): array {
    $builder = new DI\ContainerBuilder($containerClass ?? is_cli() ? Console::class : Server::class);
    $config->configureContainer($builder);
    $container = $builder->build();
    
    return [
        $$container instanceof AppInterface ? $container : $container->get(AppInterface::class),
        $container->get(ErrorHandlerInterface::class),
        $container->get(BootstrapperInterface::class)
    ];
})(new Config(require 'config\config.php'));

