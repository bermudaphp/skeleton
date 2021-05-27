<?php

/**
 * @var \Bermuda\App\AppInterface $app
 */
$app->pipe(\Bermuda\Router\Middleware\MatchRouteMiddleware::class);
$app->pipe(\Bermuda\Router\Middleware\DispatchRouteMiddleware::class);
