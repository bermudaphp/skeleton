<?php

declare(strict_types=1);

use Bermuda\App\Boot\Finder;
use Bermuda\App\Listeners\RegisterRouteListener;
use Bermuda\Config\AttributeProvider;
use Bermuda\Config\Config;
use Bermuda\Config\ConfigProvider;
use Bermuda\Config\PhpFileProvider;

Config::$devMode = true;

return Config::merge(
    new Bermuda\App\ConfigProvider,
    new Bermuda\HTTP\ConfigProvider,
    new Bermuda\Detector\ConfigProvider,
    new Bermuda\PSR7ServerFactory\ConfigProvider,
    new Bermuda\Router\ConfigProvider,
    new Bermuda\Pipeline\ConfigProvider,
    new Bermuda\MiddlewareFactory\ConfigProvider,
    new Bermuda\ErrorHandler\ConfigProvider,
    new Bermuda\ClassFinder\ConfigProvider,

    new PhpFileProvider('./config/autoload/{{,*.}global,{,*.}local}.php'),
    new PhpFileProvider('./config/development.config.php'),
    new AttributeProvider('./src'),

    // App config provider
     new class extends ConfigProvider {
        protected function getInvokables(): array
        {
            return [\Console\CreateCommand::class];
        }

        protected function getConfig(): array
        {
            return [
                Bermuda\ClassFinder\ConfigProvider::CONFIG_KEY_LISTENERS => [
                    RegisterRouteListener::class,
                ],
                Finder::CONFIG_KEY_DIRS => [getcwd().'/src'],
            ];
        }
    },
);
