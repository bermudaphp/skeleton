<?php

use Bermuda\App\AppInterface;


/**
 * @var AppInterface $app
 */

$app->pipe(Console\Commands\HelloWorld::class);
