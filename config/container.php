<?php

declare(strict_types = 1);

return (static function(array $config, ?callable $containerFactory = null): array {
    if ($containerFactory != null) {
        $container = (static function() use ($containerFactory, $config): Psr\Container\ContainerInterface {
            return $containerFactory($config);
        })();
    } else {
        $builder = new DI\ContainerBuilder(PHP_SAPI == 'cli' ?
            Bermuda\App\ConsoleApp::class : Bermuda\App\ServerApp::class
        );

        new Elie\PHPDI\Config\Config($config)->configureContainer($builder);

        if (!Bermuda\Config\Config::$devMode) {
            $builder->enableCompilation(__DIR__ . '\cache', PHP_SAPI == 'cli'
                ? 'CompiledConsole' : 'CompiledServer',
                PHP_SAPI == 'cli' ? Bermuda\App\Compile\CompiledConsole::class
                    : Bermuda\App\Compile\CompiledServer::class
            );
        }

        $container = $builder->build();
    }

    if (isset($config[Bermuda\App\ConfigProvider::CONFIG_KEY_CONTAINERS])
        && $container instanceof Bermuda\App\ContainerCollectorInterface
    ) {
        /**
         * @var \Psr\Container\ContainerInterface&\Bermuda\App\ContainerCollectorInterface $container
         */
        foreach ($config[Bermuda\App\ConfigProvider::CONFIG_KEY_CONTAINERS] as $containerEntry) {
            $container->addContainer($container->get($containerEntry));
        }
    }
    
    return [
        $container->get(Bermuda\App\AppInterface::class),
        $container->get(Bermuda\App\Boot\Bootable::class)
    ];
})(require 'config\config.php');
