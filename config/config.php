<?php

declare(strict_types=1);

use Bermuda\Config\Config;
use Laminas\ConfigAggregator\PhpFileProvider;

Config::$devMode = true;
Config::$cacheFile = APP_ROOT . '\config\cached-config.php';

return Config::merge(
    new Bermuda\RequestHandlerRunner\ConfigProvider(),
    new Bermuda\Router\ConfigProvider(),
    new Bermuda\Pipeline\ConfigProvider(),
    new Bermuda\Templater\ConfigProvider(),
    new Bermuda\MiddlewareFactory\ConfigProvider(),
    new Bermuda\ErrorHandler\ConfigProvider(),
    
    new PhpFileProvider(APP_ROOT  . '/config/autoload/{{,*.}global,{,*.}local}.php'),
    new PhpFileProvider(APP_ROOT  . '/config/development.config.php'),
    
    // App config provider
    new class extends \Bermuda\Config\ConfigProvider
    {
        /**
         * An associative array that maps a service name to a factory class name, or any callable.
         * Factory classes must be instantiable without arguments, and callable once instantiated (i.e., implement the __invoke() method).
         * @return array
         */
        protected function getFactories(): array
        {
            return [
                \Bermuda\App\AppInterface::class => \Bermuda\App\AppFactory::class,
                \Bermuda\App\Boot\BootstrapperInterface::class => static function(\Psr\Container\ContainerInterface $container): \Bermuda\App\Boot\Bootstrapper
                {
                    return \Bermuda\App\Boot\Bootstrapper::makeOf([
                        new \Bermuda\App\Boot\RouterBootstrapper(),
                        new \Bermuda\App\Boot\PipelineBootstrapper(),
                    ]);
                },
                \App\Handler\HomePageHandler::class => \App\Factory\HomePageHandlerFactory::class
           ];
       }
    },
);
