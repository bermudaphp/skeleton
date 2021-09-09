<?php

declare(strict_types=1);

use Throwable;
use Bermuda\App\AppFactory;
use Psr\Container\ContainerInterface;

error_reporting(E_ALL);

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

chdir(dirname(__DIR__));

require getcwd() . '\vendor\autoload.php';

(static function (ContainerInterface $container): void
{
    $app = AppFactory::make($container);
    
    try {
        $app->run();
    } catch(Throwable $e) {
        $app->handleException($e);
    }
})
(require getcwd() . '\config\container.php');
