<?php

declare(strict_types=1);

use Bermuda\App\AppInterface;
use Bermuda\App\Boot\BootstrapperInterface;

error_reporting(E_ALL);
set_error_handler(/**
 * @throws ErrorException
 */ static function(int $errno, string $msg, string $file, int $line): void {
    if ((error_reporting() & $errno)) {
        throw new \ErrorException($msg, 0, $errno, $file, $line);
    }
});

try {
    // Delegate static file requests back to the PHP built-in webserver
    if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
        return false;
    }

    chdir(dirname(__DIR__));

    require 'vendor\autoload.php';

    (static function(AppInterface $app, BootstrapperInterface $bootstrapper): void
    {
        try {
            $bootstrapper->boot($app)->run();
        } catch (Throwable $e) {
            $app->handleException($e);
        }
    })(... require 'config\container.php');

} catch (Throwable $e) {

    $logDir = dirname(__DIR__) . "\logs";
    $logFile = $logDir . '\\' . ($now = new DateTime())->format('Y-m-d').'.log';
    $render = static function(string $template, string|array $params): string {
        is_string($params) ? extract(['msg' => $params]) : extract($params);
        ob_start();
        require_once $template;
        return ob_get_clean();
    };

    if (!file_exists($logFile) && is_writable($logDir)) {
        write:
        $content = sprintf(
            '[%s] Exception: %s, File: %s, Line: %s, Message: %s, Code: %s;',
            $now->format('H:i:s p'),
            $e::class,
            $e->getFile(),
            $e->getLine(),
            $e->getMessage(),
            $e->getCode()
        ) . PHP_EOL ;

        file_put_contents($logFile, $content, FILE_APPEND);
    } elseif (!is_writable($logFile) && chmod($logFile, 0600)) {
        goto write;
    } else {
        if (!chmod($logDir, 0600)) {
            http_response_code(500);
            exit($render('template/error.phtml','The application ended with an error'));
        }

        goto write;
    }

    http_response_code(500);
    exit($render('template/error.phtml', 'The application ended with an error. For detailed information about the error check the log file'));
}

