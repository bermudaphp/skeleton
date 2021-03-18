<?php

use Bermuda\App\AppInterface;

/**
 * @var AppInterface $app
 */

$app->pipe(Console\Commands\HelloWorld::class);
$app->pipe(Console\Commands\CreateHandler::class);
