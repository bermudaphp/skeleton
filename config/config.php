<?php

declare(strict_types=1);

use Bermuda\App\Boot\Bootable;
use Bermuda\App\Boot\BootableCollector;
use Bermuda\Config\AttributeProvider;
use Bermuda\Config\Config;
use Bermuda\Config\ConfigProvider;
use Bermuda\Config\PhpFileProvider;

Config::$devMode = true;
Config::$cacheFile = __DIR__ . '\cache\config.php';

if (!Config::$devMode && Config::$cacheFile) {
    return Config::fromCache(Config::$cacheFile);
}

return Config::merge(
    new Bermuda\App\ConfigProvider,
    new Bermuda\HTTP\ConfigProvider,
    new Bermuda\Detector\ConfigProvider,
    new Bermuda\PSR7ServerFactory\ConfigProvider,
    new Bermuda\Router\ConfigProvider,
    new Bermuda\Pipeline\ConfigProvider,
    new Bermuda\MiddlewareFactory\ConfigProvider,
    new Bermuda\ErrorHandler\ConfigProvider,

    new PhpFileProvider('./config/autoload/{{,*.}global,{,*.}local}.php'),
    new PhpFileProvider('./config/development.config.php'),
    new AttributeProvider('./src'),

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
                Bootable::class => [BootableCollector::class, 'withDefaults']
            ];
        }

        protected function getInvokables(): array
        {
            return [\Console\CreateCommand::class, \Console\CreateModule::class];
        }
    },
);
