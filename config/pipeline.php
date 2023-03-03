<?php

/**
 * @var \Bermuda\App\AppInterface $app
 */
$app->pipe(\Bermuda\ErrorHandler\ErrorHandlerMiddleware::class);
$app->pipe(\Bermuda\Router\Middleware\MatchRouteMiddleware::class);
$app->pipe(\Tuupola\Middleware\CorsMiddleware::class);
$app->pipe(\Bermuda\Router\Middleware\DispatchRouteMiddleware::class);
