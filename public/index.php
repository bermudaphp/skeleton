<?php


declare(strict_types=1);


error_reporting(E_ALL);

if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__)
{
    return false;
}

define('APP_ROOT', dirname(__DIR__));
require APP_ROOT . '/vendor/autoload.php';

(function()
{
    $app = (new \Bermuda\App\AppFactory())(require APP_ROOT . '/config/container.php');

    require APP_ROOT . '/config/routes.php';
    require APP_ROOT . '/config/pipeline.php';

    $app->run();
})();
