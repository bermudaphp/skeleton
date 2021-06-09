<?php

use Bermuda\App\AppInterface;

/**
 * @var AppInterface $app
 */
$app->pipe(Console\Commands\ShowRoutes::class);
$app->pipe(Console\Commands\CreateHandler::class);
