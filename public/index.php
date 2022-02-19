<?php

declare(strict_types=1);

use Bermuda\App\AppInterface;
use Bermuda\App\Boot\BootstrapperInterface;
use Bermuda\ErrorHandler\ErrorHandlerInterface;

error_reporting(E_ALL);

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

chdir(dirname(__DIR__));

require 'vendor\autoload.php';

(static function(AppInterface $app, ErrorHandlerInterface $handler, BootstrapperInterface $b): void
{
    try {
        $b->boot($app)->run();
    } catch (Throwable $e) {
        $handler->handleException($e);
    }
})(... require 'config\container.php');
