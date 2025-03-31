<?php

use Bermuda\App\AppInterface;

/**
 * @var AppInterface $app
 */
$app->pipe(Console\CreateCommand::class);
$app->pipe(Console\CreateModule::class);

