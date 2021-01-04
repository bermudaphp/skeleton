<?php

declare(strict_types=1);

error_reporting(E_ALL);

if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__)
{
    return false;
}

define('APP_ROOT', dirname(__DIR__));
require APP_ROOT . '\vendor\autoload.php';

(static function(\Psr\Container\ContainerInterface $container): void
{
    ($app = $container->get(Bermuda\App\AppInterface::class))
    ->get(Bermuda\App\Boot\Bootstrapper::class)
        ->boot($app);

    $app->run();
})

(require APP_ROOT . '\config\container.php');
