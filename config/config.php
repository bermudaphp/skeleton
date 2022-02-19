<?php

declare(strict_types=1);

use Bermuda\Config\Config;
use Bermuda\Config\ConfigProvider;
use Bermuda\App\Boot\Bootstrapper;
use Bermuda\App\Boot\BootstrapperInterface;
use Laminas\ConfigAggregator\PhpFileProvider;
use Psr\Container\ContainerInterface;

Config::$devMode = true;
Config::$cacheFile = '\config\cached-config.php';

return Config::merge(
    new Bermuda\App\ConfigProvider,
    new Bermuda\PSR7ServerFactory\ConfigProvider,
    new Bermuda\Router\ConfigProvider,
    new Bermuda\Pipeline\ConfigProvider,
    new Bermuda\MiddlewareFactory\ConfigProvider,
    new Bermuda\ErrorHandler\ConfigProvider,

    new PhpFileProvider('./config/autoload/{{,*.}global,{,*.}local}.php'),
    new PhpFileProvider('./config/development.config.php'),

    // App config provider
    new class extends ConfigProvider {
        /**
         * An associative array that maps a service name to a factory class name, or any callable.
         * Factory classes must be instantiable without arguments, and callable once instantiated (i.e., implement the __invoke() method).
         * @return array
         */
        protected function getFactories(): array
        {
            return [
                BootstrapperInterface::class => static function(ContainerInterface $container): Bootstrapper {
                    return Bootstrapper::withDefaults($container);
                }
            ];
        }
    },
);
