<?php

declare(strict_types = 1);

use Elie\PHPDI\Config\Config;
use Elie\PHPDI\Config\ConfigInterface;
use Bermuda\App\{Server, Console};
use Psr\Container\ContainerInterface;
use function Bermuda\App\is_cli;

return (static function(ConfigInterface $config, string $containerClass): ContainerInterface {
        $config->configureContainer($builder = new DI\ContainerBuilder($containerClass));
        return $builder->build();
})(new Config(require 'config\config.php'), is_cli() ? Console::class : Server::class);

