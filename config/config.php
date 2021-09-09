<?php

declare(strict_types=1);

use Bermuda\App\AppFactory;
use Bermuda\App\AppInterface;
use Bermuda\App\Boot\Bootstrapper;
use Bermuda\App\Boot\BootstrapperInterface;
use Bermuda\Config\Config;
use Bermuda\Config\ConfigProvider;
use Laminas\ConfigAggregator\PhpFileProvider;
use Monolog\{Handler\StreamHandler, Logger};
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use App\Factory\HomePageHandlerFactory;
use App\Handler\HomePageHandler;

use function Bermuda\App\{is_console_sapi, path};

Config::$devMode = true;
Config::$cacheFile = APP_ROOT . '\config\cached-config.php';

return Config::merge(
    new Bermuda\PSR7ServerFactory\ConfigProvider(),
    new Bermuda\Router\ConfigProvider(),
    new Bermuda\Pipeline\ConfigProvider(),
    new Bermuda\Templater\ConfigProvider(),
    new Bermuda\MiddlewareFactory\ConfigProvider(),
    new Bermuda\ErrorHandler\ConfigProvider(),

    new PhpFileProvider(APP_ROOT . '/config/autoload/{{,*.}global,{,*.}local}.php'),
    new PhpFileProvider(APP_ROOT . '/config/development.config.php'),

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
                AppInterface::class => AppFactory::class,
                BootstrapperInterface::class => static function (ContainerInterface $container): Bootstrapper {
                    return Bootstrapper::makeDefault($container);
                },
                LoggerInterface::class => static function (ContainerInterface $container) {
                    $is_console = is_console_sapi();
                    return (new Logger($is_console ? 'console' : 'server'))->pushHandler(
                        new StreamHandler(path()->append('logs', $is_console ? 'console.log' : 'server.log'))
                    );
                },
                HomePageHandler::class => HomePageHandlerFactory::class,
            ];
        }
    },
);
