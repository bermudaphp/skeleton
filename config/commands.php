<?php

use Bermuda\App\AppInterface;

/**
 * @var AppInterface $app
 */
$app->pipe(Console\Commands\Serve::class);
$app->pipe(Console\Commands\Router\Show::class);
$app->pipe(Console\Commands\Make\Handler::class);

