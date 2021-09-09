<?php

use Bermuda\Router\RouteMap;

/**
 * @var RouteMap $routes
 */
$routes->get('home', '/', \App\Handler\HomePageHandler::class);
