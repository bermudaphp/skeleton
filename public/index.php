<?php

declare(strict_types=1);

use Bermuda\App\Boot\Bootable;
use Bermuda\App\AppInterface;

error_reporting(E_ALL);
set_error_handler(static function(int $errno, string $msg, string $file, int $line): never {
    throw new \ErrorException($msg, 0, $errno, $file, $line);
});

try {
    // Delegate static file requests back to the PHP built-in webserver
    if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
        return false;
    }

    chdir(dirname(__DIR__));

    require 'vendor\autoload.php';

    (static function(AppInterface $app, Bootable $bootable): void
    {
        try {
            $app->run($bootable);
        } catch (Throwable $e) {
            $app->errorHandler->handleException($e);
        }
    })(... require 'config\container.php');

} catch (Throwable $e) {
    new ErrorHandler()->handle($e, Bermuda\Config\Config::$devMode);
}

