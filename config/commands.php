<?php

use Bermuda\App\AppInterface;


/**
 * @var AppInterface $app
 */

$app->pipe(Console\DisplaysAppVersionCommand::class);
