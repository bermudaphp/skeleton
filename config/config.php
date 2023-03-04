<?php

declare(strict_types=1);

use Bermuda\Clock\Clock;
use Bermuda\Config\Config;
use Bermuda\Config\ConfigProvider;
use Bermuda\App\AppInterface;
use Bermuda\App\Boot\Bootstrapper;
use Bermuda\App\Boot\BootstrapperInterface;
use Bermuda\Config\PhpFileProvider;
use Psr\Container\ContainerInterface;

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
                    return Bootstrapper::withDefaults($container)->add(
                        new class implements BootstrapperInterface {
                            public function boot(AppInterface $app): AppInterface
                            {
                                if (isset($app->config[ConfigProvider::bootstrap])) {
                                    foreach ($app->config[ConfigProvider::bootstrap] as $callback) {
                                        $callback($app);
                                    }
                                }

                                return $app;
                            }
                        }
                    );
                },
            ];
        }

        protected function getConfig(): array
        {
            return [self::bootstrap => [
                static function(AppInterface $app) {
                    $timezone = $app->config[Config::app_timezone]
                        ?? date_default_timezone_get();

                    Clock::timeZone(new DateTimeZone($timezone));
                    date_default_timezone_set($timezone);
                }
            ]];
        }
    },
);
