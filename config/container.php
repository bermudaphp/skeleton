<?php

declare(strict_types = 1);

return (static function(array $config, callable $containerFactory = null): array {
    if ($containerFactory != null) {
        $container = (static function() use ($containerFactory, $config): Psr\Container\ContainerInterface {
            return $containerFactory($config);
        })();
    } else {
        $builder = new DI\ContainerBuilder(Bermuda\App\is_cli ?
            Bermuda\App\Console::class : Bermuda\App\Server::class
        );

        (new Elie\PHPDI\Config\Config($config))->configureContainer($builder);

        if (!Bermuda\Config\Config::$devMode) {
            $builder->enableCompilation(__DIR__ . '\cache', Bermuda\App\is_cli
                ? 'CompiledConsole' : 'CompiledServer',
                Bermuda\App\is_cli ? Bermuda\App\Compile\CompiledConsole::class
                    : Bermuda\App\Compile\CompiledServer::class
            );
        }

        $container = $builder->build();
    }
    
    return [
        $container instanceof Bermuda\App\AppInterface ? $container
            : $container->get(Bermuda\App\AppInterface::class),
        $container->get(Bermuda\App\Boot\BootstrapperInterface::class)
    ];
})(require 'config\config.php');
