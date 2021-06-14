<?php

declare(strict_types=1);

error_reporting(E_ALL);

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__)
{
    return false;
}

define('APP_ROOT', dirname(__DIR__));
require APP_ROOT . '\vendor\autoload.php';

(static function (\Psr\Container\ContainerInterface $container): void
{
    $app = Bermuda\App\AppFactory::make($container);
    
    try {
        $app->boot()->run();
    }
    
    catch(\Throwable $e)
    {
        $app->handleException($e);
    }
})
(require APP_ROOT . '\config\container.php');
