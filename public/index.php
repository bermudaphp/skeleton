<?php

declare(strict_types=1);

use Bermuda\App\AppInterface;
use Bermuda\App\Boot\BootstrapperInterface;
use Psr\Container\ContainerInterface;

error_reporting(E_ALL);

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

chdir(dirname(__DIR__));

require 'vendor\autoload.php';

(static function (ContainerInterface $container): void {
    $app = $container instanceof AppInterface
        ? $container : $container->get(AppInterface::class);
    
    try {
        $app->get(BootstrapperInterface::class)
            ->boot($app); $app->run();
    } catch(Throwable $e) {
        $app->handleException($e);
    }
})
(require 'config\container.php');
